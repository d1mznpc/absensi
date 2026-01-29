<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'attendance_id',
        'action',
        'latitude',
        'longitude',
        'distance',
    ];

    // Relasi
    public function attendance()
    {
        return $this->belongsTo(Attendance::class);
    }
}
