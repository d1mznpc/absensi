<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin / Bos
        User::create([
            'name' => 'Bos Absensi',
            'email' => 'admin@absensi.test',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Karyawan
        User::create([
            'name' => 'Karyawan Satu',
            'email' => 'karyawan@absensi.test',
            'password' => Hash::make('password'),
            'role' => 'user',
        ]);
    }
}
