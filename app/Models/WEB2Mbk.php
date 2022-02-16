<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use App\Models\AccountMomo;
use App\Models\LichSuBank;

class WEB2M extends AccountMomo
{
    //Lấy lịch sử giao dịch
    public function GetGiaoDich($token){
        $url = "https://api.web2m.com/historyapimomo1h/$token";
        $response = Http::get($url);

        return $response->json();
    }

    //Chuyển tiền MOMO
    public function Bank($token, $sdtnguoinhan, $password, $money, $noidung){
        $url = "https://api.web2m.com/TRANSFERAPIMOMO/$token/$sdtnguoinhan/$password/$money/$noidung";
        $response = Http::get($url);

        $AccountMomo = new AccountMomo;
        $getInfoPhone = $AccountMomo->where([
            'token' => $token
        ])->first();

        $LichSuBank = new LichSuBank;
        $LichSuBank->sdtbank = $getInfoPhone->sdt;
        $LichSuBank->nguoinhan = $sdtnguoinhan;
        $LichSuBank->sotien = $money;
        $LichSuBank->noidung = $noidung;
        $LichSuBank->response = json_encode($response->json() ?? []);
        $LichSuBank->save();

        return $response->json();
    }

    public function getMoney_momo($token)
    {
        try {
            $result = Http::get("https://api.web2m.com/apigetsodu/$token")->json();

            if(isset($result['status']) && $result['status'] == 200)
            {
                return $result['SoDu'];
            }
            else
            {
                return 0;
            }
        }

        catch(Exception $e) {
            return 0;
        }
    }
    
    public function getName_momo($sdt, $token)
    {
        try {
            $result = Http::get("https://api.web2m.com/apigetten/".$sdt."/".$token)->json();

            if(isset($result['status']) && $result['status'] == 200)
            {
                return $result['name'];
            }
            else
            {
                if( !empty($result['msg']) ){
                    return $result['msg'];
                } else {
                    return 'Có lỗi xãy ra vui lòng F5';
                }
            }
        }

        catch(Exception $e) {
            return 'Có lỗi xãy ra vui lòng thử lại';
        }
    }
}
