<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Picqer\Barcode\BarcodeGeneratorPNG;

class BarangController extends Controller
{
    /**
     * Constructor - middleware auth
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::orderBy('timestamp', 'desc')->get();
        return view('barang.index', compact('barang'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('barang.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|integer|min:0'
        ]);

        Barang::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $barang = Barang::findOrFail($id);
        return view('barang.edit', compact('barang'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:50',
            'harga' => 'required|integer|min:0'
        ]);

        $barang = Barang::findOrFail($id);
        $barang->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
        ]);

        return redirect()->route('barang.index')->with('success', 'Barang berhasil diupdate');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $barang = Barang::findOrFail($id);
        $barang->delete();

        return redirect()->route('barang.index')->with('success', 'Barang berhasil dihapus');
    }

    /**
     * Show form for printing labels
     */
    public function printForm()
    {
        $barang = Barang::orderBy('nama')->get();
        return view('barang.print-form', compact('barang'));
    }

    /**
     * Generate PDF label
     */
    public function printLabel(Request $request)
    {
        $request->validate([
            'barang_ids' => 'required|array',
            'barang_ids.*' => 'exists:barang,id_barang',
            'x' => 'required|integer|min:1|max:5',
            'y' => 'required|integer|min:1|max:8'
        ]);

        $barangs = Barang::whereIn('id_barang', $request->barang_ids)->get();
        $x = $request->x;
        $y = $request->y;

        // Generate barcode untuk setiap barang
        $generator = new BarcodeGeneratorPNG();
        foreach ($barangs as $barang) {
            $barcodeData = $generator->getBarcode(
                (string) $barang->id_barang,
                $generator::TYPE_CODE_128,
                2,  // width factor
                40  // height in pixels
            );
            $barang->barcode_base64 = base64_encode($barcodeData);
        }

        $pdf = Pdf::loadView('barang.label-108', compact('barangs', 'x', 'y'))
                  ->setPaper('a4', 'portrait');

        // Stream PDF ke browser (form menggunakan target="_blank")
        return $pdf->stream('label-harga-' . date('YmdHis') . '.pdf');
    }
}
