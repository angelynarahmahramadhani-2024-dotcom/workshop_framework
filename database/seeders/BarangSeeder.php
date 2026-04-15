<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use App\Models\Vendor;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $warungBuSari = Vendor::where('nama_vendor', 'Warung Bu Sari')->first();
        $kantinPakBudi = Vendor::where('nama_vendor', 'Kantin Pak Budi')->first();
        $minumanSegarJaya = Vendor::where('nama_vendor', 'Minuman Segar Jaya')->first();

        $barangs = [
            ['nama' => 'Nasi Goreng Spesial', 'harga' => 18000, 'id_vendor' => $warungBuSari?->id_vendor],
            ['nama' => 'Mie Ayam', 'harga' => 15000, 'id_vendor' => $warungBuSari?->id_vendor],
            ['nama' => 'Es Teh Manis', 'harga' => 5000, 'id_vendor' => $minumanSegarJaya?->id_vendor],
            ['nama' => 'Jus Alpukat', 'harga' => 12000, 'id_vendor' => $minumanSegarJaya?->id_vendor],
            ['nama' => 'Ayam Geprek', 'harga' => 20000, 'id_vendor' => $kantinPakBudi?->id_vendor],
            ['nama' => 'Pisang Goreng', 'harga' => 8000, 'id_vendor' => $kantinPakBudi?->id_vendor],
        ];

        foreach ($barangs as $barang) {
            Barang::firstOrCreate(
                ['nama' => $barang['nama'], 'id_vendor' => $barang['id_vendor'] ?? null],
                ['harga' => $barang['harga']]
            );
        }
    }
}
