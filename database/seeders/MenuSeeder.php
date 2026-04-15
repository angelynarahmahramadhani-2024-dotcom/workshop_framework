<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\Vendor;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $warungBuSari = Vendor::where('nama_vendor', 'Warung Bu Sari')->first();
        $kantinPakBudi = Vendor::where('nama_vendor', 'Kantin Pak Budi')->first();
        $minumanSegarJaya = Vendor::where('nama_vendor', 'Minuman Segar Jaya')->first();

        $items = [
            // Warung Bu Sari
            ['nama_menu' => 'Nasi Goreng Spesial', 'harga' => 18000, 'idvendor' => $warungBuSari?->id_vendor],
            ['nama_menu' => 'Mie Ayam Bakso', 'harga' => 15000, 'idvendor' => $warungBuSari?->id_vendor],
            ['nama_menu' => 'Soto Ayam', 'harga' => 16000, 'idvendor' => $warungBuSari?->id_vendor],
            ['nama_menu' => 'Nasi Uduk', 'harga' => 12000, 'idvendor' => $warungBuSari?->id_vendor],

            // Kantin Pak Budi
            ['nama_menu' => 'Ayam Geprek', 'harga' => 20000, 'idvendor' => $kantinPakBudi?->id_vendor],
            ['nama_menu' => 'Pisang Goreng Keju', 'harga' => 10000, 'idvendor' => $kantinPakBudi?->id_vendor],
            ['nama_menu' => 'Bakso Beranak', 'harga' => 18000, 'idvendor' => $kantinPakBudi?->id_vendor],
            ['nama_menu' => 'Chicken Katsu', 'harga' => 22000, 'idvendor' => $kantinPakBudi?->id_vendor],

            // Minuman Segar Jaya
            ['nama_menu' => 'Es Teh Manis', 'harga' => 5000, 'idvendor' => $minumanSegarJaya?->id_vendor],
            ['nama_menu' => 'Jus Alpukat', 'harga' => 12000, 'idvendor' => $minumanSegarJaya?->id_vendor],
            ['nama_menu' => 'Es Jeruk Segar', 'harga' => 7000, 'idvendor' => $minumanSegarJaya?->id_vendor],
            ['nama_menu' => 'Kopi Susu Gula Aren', 'harga' => 15000, 'idvendor' => $minumanSegarJaya?->id_vendor],
        ];

        foreach ($items as $item) {
            Menu::firstOrCreate(
                ['nama_menu' => $item['nama_menu'], 'idvendor' => $item['idvendor']],
                ['harga' => $item['harga']]
            );
        }
    }
}
