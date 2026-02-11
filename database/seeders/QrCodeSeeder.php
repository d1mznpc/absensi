<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QrCode;
use Carbon\Carbon;

class QrCodeSeeder extends Seeder
{
    public function run(): void
    {
        $today = Carbon::today('Asia/Jakarta');

        QrCode::updateOrCreate(
            [
                'code'       => 'QR-ABSENSI',
                'valid_date' => $today,
            ],
            [
                'valid_from'  => $today->copy()->startOfDay(), // 00:00:00
                'valid_until' => $today->copy()->endOfDay(),   // 23:59:59
                'is_active'   => true,
            ]
        );
    }
}
