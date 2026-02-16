<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BukuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil ID kategori
        $novel = DB::table('kategori')->where('nama_kategori', 'Novel')->first();
        $biografi = DB::table('kategori')->where('nama_kategori', 'Biografi')->first();

        DB::table('buku')->insert([
            [
                'kode' => 'NV-01',
                'judul' => 'Home Sweet Loan',
                'pengarang' => 'Almira Bastari',
                'idkategori' => $novel->idkategori
            ],
            [
                'kode' => 'BO-01',
                'judul' => 'Mohammad Hatta, Untuk Negeriku',
                'pengarang' => 'Taufik Abdullah',
                'idkategori' => $biografi->idkategori
            ],
            [
                'kode' => 'NV-02',
                'judul' => 'Keajaiban Toko Kelontong Namiya',
                'pengarang' => 'Keigo Higashino',
                'idkategori' => $novel->idkategori
            ],
        ]);
    }
}
