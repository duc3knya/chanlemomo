<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo',
        'linkvideoyoutube',
        'zalo',
        'baotri',
        'script',
        'color_header',
        'color_footer',
        'color_table',
        'color_table2',
        'on_chanle',
        'on_taixiu',
        'on_chanle2',
        'on_gap3',
        'on_tong3so',
        'on_1phan3',
        'on_nohu',
        'on_trathuongtuan',
        'on_diemdanh',
        'on_diemdanh_ngay'
    ];
}
