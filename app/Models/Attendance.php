<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'office_location_id',
        'qr_code_id',
        'attendance_date',
        'check_in',
        'check_out',
        'scan_latitude',
        'scan_longitude',
        'distance',
        'status',
    ];

    protected $casts = [
        'attendance_date' => 'date',
        'check_in' => 'datetime:H:i',
        'check_out' => 'datetime:H:i',
    ];

    // Relasi
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function officeLocation()
    {
        return $this->belongsTo(OfficeLocation::class);
    }

    public function qrCode()
    {
        return $this->belongsTo(QrCode::class);
    }

    public function logs()
    {
        return $this->hasMany(AttendanceLog::class);
    }
}
