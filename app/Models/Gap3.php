<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gap3 extends Model
{
    use HasFactory;

    protected $fillable = [
        'min',
        'max',
        'sdt',
        'tile1',
        'tile2',
        'tile3'
    ];
}
