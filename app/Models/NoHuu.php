<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NoHuu extends Model
{
    use HasFactory;

    protected $fillable = [
        'tiencuoc',
        'tienmacdinh',
        'ptvaohu',
    ];
}
