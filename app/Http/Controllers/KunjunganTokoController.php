<?php

namespace App\Http\Controllers;

use App\Models\LokasiToko;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Picqer\Barcode\BarcodeGeneratorPNG;

class KunjunganTokoController extends Controller
{
    /**
     * Halaman utama kunjungan toko
     */
    public function index()
    {
        $tokoList = LokasiToko::orderBy('nama_toko')->get();

        return view('kunjungan-toko.index', compact('tokoList'));
    }

    /**
     * Simpan lokasi toko baru (auto-generate barcode 6 digit)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => ['required', 'string', 'max:50'],
            'latitude'  => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'accuracy'  => ['required', 'numeric', 'min:0'],
        ]);

        // Auto-generate barcode 6 digit unik
        $barcode = $this->generateBarcode();

        LokasiToko::create([
            'barcode'   => $barcode,
            'nama_toko' => $validated['nama_toko'],
            'latitude'  => $validated['latitude'],
            'longitude' => $validated['longitude'],
            'accuracy'  => $validated['accuracy'],
        ]);

        return redirect()->route('kunjungan-toko.index')
            ->with('success', "Toko berhasil ditambahkan dengan barcode: {$barcode}");
    }

    /**
     * Cetak barcode toko sebagai gambar PNG (inline)
     */
    public function cetakBarcode(string $barcode)
    {
        $toko = LokasiToko::findOrFail($barcode);

        $generator = new BarcodeGeneratorPNG();
        $barcodeImage = $generator->getBarcode(
            $toko->barcode,
            $generator::TYPE_CODE_128,
            2,
            60
        );

        return response($barcodeImage, 200, [
            'Content-Type' => 'image/png',
            'Content-Disposition' => 'inline; filename="barcode-' . $toko->barcode . '.png"',
        ]);
    }

    /**
     * AJAX: Cari toko berdasarkan barcode
     */
    public function findByBarcode(Request $request): JsonResponse
    {
        $request->validate([
            'barcode' => ['required', 'string', 'max:6'],
        ]);

        $toko = LokasiToko::find($request->input('barcode'));

        if (!$toko) {
            return response()->json([
                'status' => 'error',
                'message' => 'Toko dengan barcode tersebut tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $toko,
        ]);
    }

    /**
     * AJAX: Verifikasi kunjungan — hitung jarak Haversine, bandingkan dengan threshold
     */
    public function verifyVisit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'barcode'        => ['required', 'string', 'max:6'],
            'sales_lat'      => ['required', 'numeric'],
            'sales_lng'      => ['required', 'numeric'],
            'sales_accuracy' => ['required', 'numeric', 'min:0'],
        ]);

        $toko = LokasiToko::find($validated['barcode']);

        if (!$toko) {
            return response()->json([
                'status' => 'error',
                'message' => 'Toko tidak ditemukan.',
            ], 404);
        }

        // Hitung jarak menggunakan formula Haversine
        $jarakAktual = $this->haversine(
            $toko->latitude,
            $toko->longitude,
            (float) $validated['sales_lat'],
            (float) $validated['sales_lng']
        );

        // Threshold = jarak max + accuracy toko + accuracy sales
        $jarakMax = 300; // 300 meter
        $thresholdEfektif = $jarakMax + $toko->accuracy + (float) $validated['sales_accuracy'];

        $diterima = $jarakAktual <= $thresholdEfektif;

        return response()->json([
            'status' => 'success',
            'data' => [
                'toko' => $toko,
                'jarak_aktual' => round($jarakAktual, 2),
                'jarak_max' => $jarakMax,
                'threshold_efektif' => round($thresholdEfektif, 2),
                'accuracy_toko' => $toko->accuracy,
                'accuracy_sales' => (float) $validated['sales_accuracy'],
                'sales_lat' => (float) $validated['sales_lat'],
                'sales_lng' => (float) $validated['sales_lng'],
                'diterima' => $diterima,
            ],
        ]);
    }

    /**
     * Formula Haversine — menghitung jarak antara 2 titik koordinat (dalam meter)
     */
    private function haversine(float $lat1, float $lng1, float $lat2, float $lng2): float
    {
        $R = 6371000; // radius bumi dalam meter

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) ** 2
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLng / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $R * $c;
    }

    /**
     * Generate barcode 6 digit unik
     */
    private function generateBarcode(): string
    {
        do {
            $barcode = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (LokasiToko::where('barcode', $barcode)->exists());

        return $barcode;
    }
}
