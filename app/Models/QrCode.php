<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class QrCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'valid_date',
        'valid_from',
        'valid_until',
        'is_active',
    ];

    protected $casts = [
        'valid_date'  => 'date',
        'valid_from'  => 'datetime',
        'valid_until' => 'datetime',
        'is_active'   => 'boolean',
    ];

    /**
     * Generate token QR (time-based, 15 detik)
     */
    public function generateToken(?Carbon $time = null): string
    {
        $time = ($time ?? Carbon::now('Asia/Jakarta'))
            ->copy()
            ->setTimezone('Asia/Jakarta');

        $interval = floor($time->timestamp / 15); // 15 detik
        $raw = $this->id . '|' . $this->code . '|' . $interval;

        return hash('sha256', $raw);
    }

    /**
     * Validasi token QR (toleransi ±15 detik)
     */
    public function isValidToken(string $token): bool
    {
        foreach ([-1, 0, 1] as $offset) {
            $time = Carbon::now('Asia/Jakarta')->addSeconds($offset * 15);

            if (hash_equals($this->generateToken($time), $token)) {
                return true;
            }
        }

        return false;
    }

    // ================== RELATION ==================
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
