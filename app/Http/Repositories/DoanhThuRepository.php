<?php


namespace App\Http\Repositories;

use App\Models\AccountLevelMoney;
use App\Models\AccountMomo;
use App\Models\LichSuBank;
use App\Models\LichSuChoiMomo;
use App\Models\DoanhThu;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class DoanhThuRepository
{

    public function xuLyDoandthuNgay($tiencuoc,$tiennhan)
    {
        
        // update doanh thu ngày 
		$doanhThu = new DoanhThu;
		$getDoanhThu = $doanhThu->whereDate('created_at', Carbon::today())->limit(1);
		if ($getDoanhThu->count() > 0){
          $GetLimitCron = $getDoanhThu->first();
          $GetLimitCron->doanhthungay = $GetLimitCron->doanhthungay + $tiencuoc  - $tienNhan;
          $GetLimitCron->save();
                                        
         }else{
             
            $doanhThu= new DoanhThu;
            $doanhThu->doanhthungay =  $tiencuoc  - $tienNhan;;
            $doanhThu->save();
                                  
         }
        
        return $doanhThu->doanhthungay;
    }

    

}