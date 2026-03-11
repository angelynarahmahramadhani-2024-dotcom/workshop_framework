<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SelectKotaController extends Controller
{
    /**
     * Halaman Select Kota - Study Case 4
     * 2 Card dengan select dropdown untuk menampilkan kota terpilih
     */
    public function index()
    {
        return view('select-kota.index');
    }
}
