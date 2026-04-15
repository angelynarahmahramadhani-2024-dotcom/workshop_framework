<?php

namespace Database\Seeders;

use App\Models\Vendor;
use Illuminate\Database\Seeder;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['nama_vendor' => 'Warung Bu Sari'],
            ['nama_vendor' => 'Kantin Pak Budi'],
            ['nama_vendor' => 'Minuman Segar Jaya'],
        ];

        foreach ($vendors as $vendor) {
            Vendor::firstOrCreate([
                'nama_vendor' => $vendor['nama_vendor'],
            ]);
        }
    }
}
