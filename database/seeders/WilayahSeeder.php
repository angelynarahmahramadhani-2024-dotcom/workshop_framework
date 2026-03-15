<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class WilayahSeeder extends Seeder
{
    public function run(): void
    {
        $this->command->info('Seeding provinsi...');
        $this->seedFromCsv(
            database_path('data/provinces.csv'),
            'provinsi',
            fn($row) => ['kode' => $row[0], 'nama' => $row[1]]
        );

        $this->command->info('Seeding kota...');
        $this->seedFromCsv(
            database_path('data/regencies.csv'),
            'kota',
            fn($row) => ['kode' => $row[0], 'kode_provinsi' => $row[1], 'nama' => $row[2]]
        );

        $this->command->info('Seeding kecamatan...');
        $this->seedFromCsv(
            database_path('data/districts.csv'),
            'kecamatan',
            fn($row) => ['kode' => $row[0], 'kode_kota' => $row[1], 'nama' => $row[2]]
        );

        $this->command->info('Seeding kelurahan (ini butuh waktu)...');
        $this->seedFromCsv(
            database_path('data/villages.csv'),
            'kelurahan',
            fn($row) => ['kode' => $row[0], 'kode_kecamatan' => $row[1], 'nama' => $row[2]]
        );

        $this->command->info('Wilayah seeding selesai!');
    }

    private function seedFromCsv(string $filePath, string $table, callable $mapper): void
    {
        DB::table($table)->truncate();

        $handle = fopen($filePath, 'r');
        fgetcsv($handle, 0, ';'); // skip header

        $chunk = [];
        $chunkSize = 500;

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) < 2) continue;
            $chunk[] = $mapper($row);

            if (count($chunk) >= $chunkSize) {
                DB::table($table)->insert($chunk);
                $chunk = [];
            }
        }

        if (!empty($chunk)) {
            DB::table($table)->insert($chunk);
        }

        fclose($handle);
    }
}
