<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceSession extends Model
{

    use HasFactory;

    protected $table = "attendance_session";
    protected $fillable = [
        'phone',
        'date',
        'amount',
        'bill_code',
        'status',
    ];

    public function getPhone()
    {
        $middle_string = "";
        $length        = strlen($this->phone);
        if ($length < 3) {
            return $length == 1 ? "*" : "*".substr($this->phone, -1);
        } else {
            $part_size        = floor($length / 3);
            $middle_part_size = $length - ($part_size * 2);
            for ($i = 0; $i < $middle_part_size; $i++) {
                $middle_string .= "*";
            }
            return substr($this->phone, 0, $part_size).$middle_string.substr($this->phone, -$part_size);
        }
    }

    public function usersAttendanceSession()
    {
        return $this->hasMany(UserAttendanceSession::class, 'session_id');
    }

}
