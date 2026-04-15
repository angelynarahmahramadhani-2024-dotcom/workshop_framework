<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Pesanan;
use App\Models\Vendor;
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
}
