<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Menciptakan Akun Super Admin Otomatis
        User::create([
            'name'     => 'Arif Rahman Hakim',
            'email'    => 'admin@big.com',
            // Silakan ganti 'password123' di bawah ini dengan password yang Anda inginkan
            'password' => Hash::make('12345678'),
            'role'     => 'admin',
        ]);

        // Anda juga bisa menambahkan akun dummy untuk testing arsiparis di sini jika mau
        // User::create([
        //     'name'     => 'Budi (UK)',
        //     'email'    => 'budi@big.com',
        //     'password' => Hash::make('password123'),
        //     'role'     => 'arsiparis_uk',
        // ]);
    }
}
