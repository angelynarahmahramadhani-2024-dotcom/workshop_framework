<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CustomerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Halaman Data Customer (tabel)
     */
    public function index()
    {
        $customers = Customer::with(['provinsi', 'kota', 'kecamatan', 'kelurahan'])
            ->latest()
            ->get();

        return view('customer.index', compact('customers'));
    }

    /**
     * Form Tambah Customer 1 (foto disimpan sebagai BLOB)
     */
    public function create1()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('customer.create1', compact('provinsi'));
    }

    /**
     * Simpan Customer 1 (foto sebagai BLOB / base64)
     */
    public function store1(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'alamat'         => 'nullable|string',
            'kode_provinsi'  => 'nullable|exists:provinsi,kode',
            'kode_kota'      => 'nullable|exists:kota,kode',
            'kode_kecamatan' => 'nullable|exists:kecamatan,kode',
            'kode_kelurahan' => 'nullable|exists:kelurahan,kode',
            'foto_blob'      => 'nullable|string', // base64 data URL dari kamera
        ]);

        Customer::create([
            'nama'           => $request->nama,
            'alamat'         => $request->alamat,
            'kode_provinsi'  => $request->kode_provinsi,
            'kode_kota'      => $request->kode_kota,
            'kode_kecamatan' => $request->kode_kecamatan,
            'kode_kelurahan' => $request->kode_kelurahan,
            'foto_blob'      => $request->foto_blob, // Simpan base64 langsung
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (foto BLOB).');
    }

    /**
     * Form Tambah Customer 2 (foto disimpan sebagai file path)
     */
    public function create2()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('customer.create2', compact('provinsi'));
    }

    /**
     * Simpan Customer 2 (foto sebagai file path)
     */
    public function store2(Request $request)
    {
        $request->validate([
            'nama'           => 'required|string|max:100',
            'alamat'         => 'nullable|string',
            'kode_provinsi'  => 'nullable|exists:provinsi,kode',
            'kode_kota'      => 'nullable|exists:kota,kode',
            'kode_kecamatan' => 'nullable|exists:kecamatan,kode',
            'kode_kelurahan' => 'nullable|exists:kelurahan,kode',
            'foto_base64'    => 'nullable|string', // base64 data URL dari kamera
        ]);

        $fotoPath = null;

        if ($request->filled('foto_base64')) {
            // Decode base64 dan simpan sebagai file
            $base64 = $request->foto_base64;

            // Ambil bagian data setelah "data:image/..."
            if (preg_match('/^data:image\/(\w+);base64,/', $base64, $matches)) {
                $extension = $matches[1]; // png, jpeg, etc.
                $base64 = substr($base64, strpos($base64, ',') + 1);
                $imageData = base64_decode($base64);

                $filename = 'customer_' . time() . '_' . uniqid() . '.' . $extension;
                $path = 'customer_photos/' . $filename;

                Storage::disk('public')->put($path, $imageData);
                $fotoPath = $path;
            }
        }

        Customer::create([
            'nama'           => $request->nama,
            'alamat'         => $request->alamat,
            'kode_provinsi'  => $request->kode_provinsi,
            'kode_kota'      => $request->kode_kota,
            'kode_kecamatan' => $request->kode_kecamatan,
            'kode_kelurahan' => $request->kode_kelurahan,
            'foto_path'      => $fotoPath,
        ]);

        return redirect()->route('customer.index')->with('success', 'Customer berhasil ditambahkan (foto file path).');
    }

    /**
     * Serve foto dari BLOB (base64) untuk ditampilkan di <img>
     */
    public function showFoto($id)
    {
        $customer = Customer::findOrFail($id);

        if (!$customer->foto_blob) {
            abort(404, 'Foto tidak ditemukan.');
        }

        // foto_blob berisi data:image/png;base64,...
        // Langsung return sebagai response image
        if (preg_match('/^data:image\/(\w+);base64,/', $customer->foto_blob, $matches)) {
            $mimeType = 'image/' . $matches[1];
            $base64Data = substr($customer->foto_blob, strpos($customer->foto_blob, ',') + 1);
            $imageData = base64_decode($base64Data);

            return response($imageData, 200)->header('Content-Type', $mimeType);
        }

        abort(404, 'Format foto tidak valid.');
    }
}
