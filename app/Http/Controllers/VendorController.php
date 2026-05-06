<?php

namespace App\Http\Controllers;

use App\Models\DetailPesanan;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Halaman daftar master menu (vendor)
     */
    public function menuIndex(Request $request)
    {
        $vendorFilter = $request->query('idvendor');

        $vendors = Vendor::orderBy('nama_vendor')->get(['id_vendor', 'nama_vendor']);
        $menuQuery = Menu::with('vendor')->orderByDesc('created_at');

        if (!empty($vendorFilter)) {
            $menuQuery->where('idvendor', (int) $vendorFilter);
        }

        $menu = $menuQuery->get();

        return view('vendor.menu.index', [
            'vendors' => $vendors,
            'menu' => $menu,
            'vendorFilter' => $vendorFilter,
        ]);
    }

    /**
     * Simpan menu baru
     */
    public function menuStore(Request $request)
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'idvendor' => ['required', 'integer', 'exists:vendor,id_vendor'],
            'path_gambar' => ['nullable', 'string', 'max:255'],
        ]);

        Menu::create($validated);

        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    /**
     * Halaman edit menu
     */
    public function menuEdit(string $id)
    {
        $menuItem = Menu::findOrFail($id);
        $vendors = Vendor::orderBy('nama_vendor')->get(['id_vendor', 'nama_vendor']);

        return view('vendor.menu.edit', compact('menuItem', 'vendors'));
    }

    /**
     * Update menu
     */
    public function menuUpdate(Request $request, string $id)
    {
        $validated = $request->validate([
            'nama_menu' => ['required', 'string', 'max:255'],
            'harga' => ['required', 'integer', 'min:0'],
            'idvendor' => ['required', 'integer', 'exists:vendor,id_vendor'],
            'path_gambar' => ['nullable', 'string', 'max:255'],
        ]);

        $menuItem = Menu::findOrFail($id);
        $menuItem->update($validated);

        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    /**
     * Hapus menu
     */
    public function menuDestroy(string $id)
    {
        $menuItem = Menu::findOrFail($id);
        $menuItem->delete();

        return redirect()->route('vendor.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

    /**
     * Halaman pesanan lunas (untuk vendor)
     */
    public function paidOrders(Request $request)
    {
        $vendorFilter = $request->query('idvendor');

        $vendors = Vendor::orderBy('nama_vendor')->get(['id_vendor', 'nama_vendor']);

        $ordersQuery = Pesanan::with(['detail.menu.vendor'])
            ->where('status_bayar', 1) // lunas
            ->orderByDesc('timestamp');

        if (!empty($vendorFilter)) {
            $ordersQuery->whereHas('detail.menu', function ($q) use ($vendorFilter) {
                $q->where('idvendor', (int) $vendorFilter);
            });
        }

        $orders = $ordersQuery->get();

        return view('vendor.orders.lunas', [
            'vendors' => $vendors,
            'orders' => $orders,
            'vendorFilter' => $vendorFilter,
        ]);
    }

    /**
     * Halaman scan QR Code pesanan (untuk vendor)
     */
    public function scanQrCode()
    {
        return view('vendor.orders.scan-qr');
    }

    /**
     * AJAX: Ambil detail pesanan berdasarkan idpesanan (hasil scan QR)
     */
    public function getOrderByQr(Request $request): JsonResponse
    {
        $request->validate([
            'idpesanan' => ['required', 'integer'],
        ]);

        $pesanan = Pesanan::with(['detail.menu.vendor'])
            ->find($request->integer('idpesanan'));

        if (!$pesanan) {
            return response()->json([
                'status' => 'error',
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        // Susun data detail pesanan
        $items = $pesanan->detail->map(function ($detail) {
            return [
                'nama_menu' => $detail->menu->nama_menu ?? '-',
                'vendor' => $detail->menu->vendor->nama_vendor ?? '-',
                'jumlah' => $detail->jumlah,
                'harga' => $detail->harga,
                'subtotal' => $detail->subtotal,
                'catatan' => $detail->catatan,
            ];
        });

        return response()->json([
            'status' => 'success',
            'data' => [
                'idpesanan' => $pesanan->idpesanan,
                'nama' => $pesanan->nama,
                'total' => $pesanan->total,
                'status_bayar' => $pesanan->status_bayar,
                'status_bayar_label' => $pesanan->status_bayar_label,
                'metode_bayar_label' => $pesanan->metode_bayar_label,
                'paid_at' => $pesanan->paid_at,
                'items' => $items,
            ],
        ]);
    }
}
