<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Barang;
use Illuminate\Support\Facades\DB;

class BarangSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $barangs = [
            ['nama' => 'Pensil 2B', 'harga' => 2500],
            ['nama' => 'Penghapus', 'harga' => 1500],
            ['nama' => 'Penggaris 30cm', 'harga' => 3000],
            ['nama' => 'Buku Tulis 38 Lembar', 'harga' => 5000],
            ['nama' => 'Spidol Whiteboard', 'harga' => 8000],
            ['nama' => 'Ballpoint Biru', 'harga' => 2000],
            ['nama' => 'Kertas HVS A4 1 Rim', 'harga' => 45000],
            ['nama' => 'Gunting Kecil', 'harga' => 7500],
            ['nama' => 'Lem Kertas', 'harga' => 4000],
            ['nama' => 'Stapler + Isi', 'harga' => 12000],
            ['nama' => 'Correction Tape', 'harga' => 6500],
            ['nama' => 'Amplop Coklat', 'harga' => 1000],
        ];

        foreach ($barangs as $barang) {
            Barang::create($barang);
        }
    }
}
