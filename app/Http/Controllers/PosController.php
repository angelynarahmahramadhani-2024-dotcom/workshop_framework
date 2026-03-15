<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Barang;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
{
    // Halaman POS versi jQuery AJAX
    public function index()
    {
        return view('pos.index');
    }

    // Halaman POS versi Axios
    public function indexAxios()
    {
        return view('pos.index-axios');
    }

    // AJAX: cari barang berdasarkan kode
    public function cariBarang(Request $request)
    {
        $barang = Barang::where('id_barang', $request->id_barang)->first();

        if (!$barang) {
            return response()->json([
                'status'  => 'error',
                'code'    => 404,
                'message' => 'Barang tidak ditemukan',
                'data'    => null,
            ]);
        }

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Barang ditemukan',
            'data'    => $barang,
        ]);
    }

    // AJAX: simpan transaksi ke database
    public function bayar(Request $request)
    {
        // jQuery kirim items sebagai JSON string, Axios kirim sebagai array langsung
        $rawItems = $request->input('items', []);
        $items = is_string($rawItems) ? json_decode($rawItems, true) : $rawItems;

        if (empty($items)) {
            return response()->json([
                'status'  => 'error',
                'code'    => 400,
                'message' => 'Tidak ada item dalam transaksi',
            ]);
        }

        DB::beginTransaction();
        try {
            $total = collect($items)->sum('subtotal');

            $penjualan = Penjualan::create([
                'total' => $total,
            ]);

            foreach ($items as $item) {
                PenjualanDetail::create([
                    'id_penjualan' => $penjualan->id_penjualan,
                    'id_barang'    => $item['id_barang'],
                    'jumlah'       => $item['jumlah'],
                    'subtotal'     => $item['subtotal'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status'  => 'success',
                'code'    => 200,
                'message' => 'Pembayaran berhasil disimpan',
                'data'    => [
                    'id_penjualan' => $penjualan->id_penjualan,
                    'total'        => $total,
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'code'    => 500,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ]);
        }
    }
}
