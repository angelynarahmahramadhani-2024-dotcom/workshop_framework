<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::firstOrCreate(
            ['email' => 'angelynarahmah@mail.com'],
            [
                'name' => 'Angel',
                'password' => Hash::make('123456'),
                'role' => 'vendor',
            ]
        );

        // Seed Kategori, Buku, dan data vendor/menu
        $this->call([
            KategoriSeeder::class,
            VendorSeeder::class,
            BarangSeeder::class,
            BukuSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
