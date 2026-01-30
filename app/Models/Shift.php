<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Shift extends Model
{
    protected $fillable = [
        'name',
        'start_time', 
        'end_time',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'employee_shift')
                    ->withPivot('shift_date')
                    ->withTimestamps();
    }
}
