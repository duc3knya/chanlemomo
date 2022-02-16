<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSetting extends Model
{
    use HasFactory;
    protected $table ="attendance_settings";
    protected $fillable = [
        'win_rate',
        'bot_rate',
        'start_time',
        'end_time',
        'money_min',
        'money_max',
        'time_each',
        'setphonewin'
    ];
}
