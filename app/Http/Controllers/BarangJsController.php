<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BarangJsController extends Controller
{
    /**
     * Halaman Form Validasi - Study Case 2
     * Form dengan validasi JavaScript untuk tambah barang (tidak tersimpan ke database)
     */
    public function formValidasi()
    {
        return view('barang-js.form-validasi');
    }

    /**
     * Halaman DataTables Demo - Study Case 3 & 4
     * Tabel interaktif dengan DataTables dan form select kota
     */
    public function datatables()
    {
        return view('barang-js.datatables');
    }
}
