<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NoHuu;
use App\Models\LichSuChoiNoHu;
use App\Models\AccountMomo;

class NoHuController extends Controller
{
    //
    public function Get_Hu(request $request){
        //Setting nổ hũ
        $NoHuu = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $LichSuChoiNoHu = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 3,
        ])->get();

        $tongtien = $Setting_NoHu->tienmacdinh;

        foreach ($GetLichSuChoiNoHu as $row) {
            $tongtien = $tongtien + $row->tienvaohu;        
            $tongtien = $tongtien - $row->tiennhan;
        }

        return response()->json([
            'tongtien' => $tongtien,
        ]);
    }

    public function Load_Hu(request $request){
        //Setting nổ hũ
        $NoHuu = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $LichSuChoiNoHu = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 3,
        ])->get();

        $tongtien = $Setting_NoHu->tienmacdinh;

        foreach ($GetLichSuChoiNoHu as $row) {
            $tongtien = $tongtien + $row->tienvaohu;        
            $tongtien = $tongtien - $row->tiennhan;
        }

        //
        $AccountMomo = new AccountMomo;
        $GetAccountMomo = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomos = [];
        $dem = 0;

        foreach ($GetAccountMomo as $row) {
            $GetAccountMomos[$dem]['sdt'] = $row->sdt;
            $dem ++;
        }

        $sotienchuyen = $Setting_NoHu->tiencuoc;

        //
        return response()->json([
            'tongtien' => $tongtien,
            'tongtien_format' => number_format($tongtien),
            'list_sdt' => $GetAccountMomos,
            'sotienchuyen' => $sotienchuyen,
        ]);
    }

}
