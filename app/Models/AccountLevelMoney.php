<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class AccountLevelMoney extends Model
{

    use HasFactory;

    protected $table = "account_level_money";
    protected $fillable = [
        'sdt',
        'type',
        'min',
        'max',
    ];

    public function getGameAttribute()
    {
        $games = Config::get('constant.list_game');
        return $games[$this->type] ?? '';
    }

}
