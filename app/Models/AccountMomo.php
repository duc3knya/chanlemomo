<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountMomo extends Model
{
    use HasFactory;

    protected $fillable = [
        'sdt',
        'password',
        'token',
        'status',
        'gioihan',
        'webapi'
    ];

    protected $hidden = [
        'password',
        'token',
    ];

    public function TextStatus($status){

        if ($status == 1) {
            return 'Hoạt động';
        }

        if ($status == 2) {
            return 'Đang bảo trì';
        }

    }

    public function ClassStatus($status){

        if ($status == 1) {
            return 'success';
        }

        if ($status == 2) {
            return 'danger';
        }

    }

    public function GetListAccountID($id){
        $id='';
		$AccountMomo = new AccountMomo;
		 $ListAccount = $AccountMomo->get();
		 foreach ($ListAccount as $row) {
			 $id=$id .$row->id.',';
		 }
        $list_id = explode(',', $id);

        $data = [];
        $dem = 0;

        foreach($list_id as $row){
            $res = $this->where([
                'id' => $row,
                'status' => 1,
            ]);

            if ($res->count() > 0) {
                $response = $res->first()->sdt;
                $data[$dem] = $response;
                $dem++;
            }
        }

        return $data;

    }

}
