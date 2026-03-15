<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Provinsi;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;

class SelectKotaController extends Controller
{
    public function index()
    {
        return view('select-kota.index');
    }

    // Halaman cascading wilayah (jQuery AJAX)
    public function wilayah()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('select-kota.wilayah', compact('provinsi'));
    }

    // Halaman cascading wilayah (Axios)
    public function wilayahAxios()
    {
        $provinsi = Provinsi::orderBy('nama')->get();
        return view('select-kota.wilayah', compact('provinsi'));
    }

    // AJAX: ambil kota berdasarkan provinsi
    public function getKota(Request $request)
    {
        $kota = Kota::where('kode_provinsi', $request->kode_provinsi)
            ->orderBy('nama')
            ->get(['kode', 'nama']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kota berhasil diambil',
            'data'    => $kota,
        ]);
    }

    // AJAX: ambil kecamatan berdasarkan kota
    public function getKecamatan(Request $request)
    {
        $kecamatan = Kecamatan::where('kode_kota', $request->kode_kota)
            ->orderBy('nama')
            ->get(['kode', 'nama']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kecamatan berhasil diambil',
            'data'    => $kecamatan,
        ]);
    }

    // AJAX: ambil kelurahan berdasarkan kecamatan
    public function getKelurahan(Request $request)
    {
        $kelurahan = Kelurahan::where('kode_kecamatan', $request->kode_kecamatan)
            ->orderBy('nama')
            ->get(['kode', 'nama']);

        return response()->json([
            'status'  => 'success',
            'code'    => 200,
            'message' => 'Data kelurahan berhasil diambil',
            'data'    => $kelurahan,
        ]);
    }
}

