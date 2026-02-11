<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\OfficeLocation;

class OfficeLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        OfficeLocation::create([
            'name'      => 'Smakzie',
            'latitude'  => -6.827071984176417, // Ganti dengan latitude Anda
            'longitude' => 107.13729971541237, // Ganti dengan longitude Anda
            'radius'    => 100,
        ]);
        OfficeLocation::create([
            'name'      => 'Rumah',
            'latitude'  => -6.817426714516223, // Ganti dengan latitude Anda
            'longitude' => 107.09829925804642, // Ganti dengan longitude Anda
            'radius'    => 100,
        ]);
    }
}
