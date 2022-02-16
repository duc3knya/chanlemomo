<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LichSuChoiMomo extends Model
{
    use HasFactory;

    public function getSdtHiddenAttribute()
    {
        $middle_string = "";
        $length        = strlen($this->sdt);
        if ($length < 3) {
            return $length == 1 ? "*" : "*".substr($this->sdt, -1);
        } else {
            $part_size        = floor($length / 3);
            $middle_part_size = $length - ($part_size * 2);
            for ($i = 0; $i < $middle_part_size; $i++) {
                $middle_string .= "*";
            }
            return substr($this->sdt, 0, $part_size).$middle_string.substr($this->sdt, -$part_size);
        }
    }
}
