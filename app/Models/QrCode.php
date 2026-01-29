<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        'valid_date' => 'date',
        'valid_from' => 'datetime:H:i',
        'valid_until' => 'datetime:H:i',
        'is_active' => 'boolean',
    ];

    // Relasi
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }
}
