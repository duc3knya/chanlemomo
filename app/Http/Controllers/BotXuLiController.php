<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Models\Setting;
use App\Models\AccountMomo;
use App\Models\LichSuChoiMomo;
use App\Models\TaiXiu;
use App\Models\ChanLe;
use App\Models\ChanLe2;
use App\Models\Gap3;
use App\Models\Tong3So;
use App\Models\X1Phan3;
use App\Models\ConfigMessageMomo;
use App\Models\WEB2M;
use App\Models\Cache;
use App\Models\LichSuTraThuongTuan;
use App\Models\SettingPhanThuongTop;
use App\Models\NoHuu;
use App\Models\LichSuChoiNoHu;
use App\Models\LimitCron;
use App\Models\MaGiaoDich;
use App\Models\TopTuan;
use App\Models\AccountLevelMoney;



class BotXuLiController extends Controller
{
    public function __construct(){
        if(!isset($_GET['cron'])) {
            exit('Exit!');
        }
    }
    
    public function sendHetTien($method,$parameters){
		$bot_token='5190886198:AAFcHwqC1Sf5Vg4igG9fRz1pPJ_rSqHBoaA';
		$url ="https://api.telegram.org/bot$bot_token/$method";
		if(!$curl = curl_init()){
			exit();
		}
		curl_setopt($curl,CURLOPT_POST,true);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$parameters);
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$output = curl_exec($curl);
		return $output;
	}
	
    public function send($method,$parameters){
		$bot_token='1986646734:AAEOYxaY55RjB7J-Uh_BKlOLZFIH9h2QOEc';
		$url ="https://api.telegram.org/bot$bot_token/$method";
		if(!$curl = curl_init()){
			exit();
		}
		curl_setopt($curl,CURLOPT_POST,true);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$parameters);
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$output = curl_exec($curl);
		return $output;
	}
	
	public function sendSimMomo($method,$parameters){
		$bot_token='2052533102:AAEvfPzY18-cl2rsfGP1KsqHM7BTpYnE4gg';
		$url ="https://api.telegram.org/bot$bot_token/$method";
		if(!$curl = curl_init()){
			exit();
		}
		curl_setopt($curl,CURLOPT_POST,true);
		curl_setopt($curl,CURLOPT_POSTFIELDS,$parameters);
		curl_setopt($curl,CURLOPT_URL,$url);
		curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
		$output = curl_exec($curl);
		return $output;
	}
	//Xử lí lại status lỗi quá 3 phút chưa trả tiền
    public function getDoanhThuNgay(request $request){
        $TongDoanhThuGameNgay = 0;
		//Tổng ngày
		$LichSuChoiMomo = new LichSuChoiMomo;
        $GetLichSuChoiMomo = $LichSuChoiMomo->where(
            'status', '!=', 5
        )->whereDate('created_at', Carbon::today())->get();

        foreach ($GetLichSuChoiMomo as $row) {
            $TongDoanhThuGameNgay = $TongDoanhThuGameNgay + $row->tiencuoc;
            $TongDoanhThuGameNgay = $TongDoanhThuGameNgay - $row->tiennhan;
        }
		$parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                	                    
         $parameters["text"]='*AUTO CHẴN LẺ : Doanh thu ngày:* '. Carbon::now()  .' 🤡 :  '. number_format($TongDoanhThuGameNgay);
         $this->sendSimMomo("sendMessage",$parameters);
        echo 'Update doanh thu ngày thành công';
    }
	
	//Xử lí rút tiền về tài khoản
    public function getTranferMoney(request $request){
		$parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao1',
                	                        "parse_mode" => 'Markdown'
                                    	);
                                    	
         $AccountMomo = new AccountMomo;                            	
        $ListAccountMomo = $AccountMomo->get();
		$WEB2M = new WEB2M;
        foreach($ListAccountMomo as $row){
			
			$soDu = $WEB2M->getMoney_momo($row->token,$row->webapi);

			$parameters["text"] = '*AUTO CHẴN LẺ* : ' . $row->sdt . ' Số dư hiện tại =' . number_format($soDu)  ;
			if($soDu > 2000000){
			    
				$content='RÚT '. bin2hex(random_bytes(3));
				for ($i = 0; $i < 3; $i++){
					$soTienRut = $soDu - 2000000;
					if($soTienRut <= 5000000){
						$res = $WEB2M->Bank(
                            $row->token,
                            '0394343329',
                            $row->password,
                            $soTienRut,
                            $content,
							$row->webapi
                        );
					}else{
						$res = $WEB2M->Bank(
                            $row->token,
                            '0394343329',
                            $row->password,
                            4999999,
                            $content,
							$row->webapi
							);
						
					}
					if ( isset($res['status']) && $res['status'] == 200) {
					    $parameters["text"] =$parameters["text"] .' RÚT THÀNH CÔNG: số cuối =' .number_format($res['balance']); 
							if($soTienRut <= 5000000){
								break;
							}
							
					}else{
					    $parameters["text"]= $parameters["text"] . $res['msg']; 
					} 
					sleep(3);
				}         
			}else{
			    $parameters["text"] = $parameters["text"] . ' *KHÔNG RÚT*';
			}
			 $this->sendSimMomo("sendMessage",$parameters);
			 $parameters["text"]='';
		}        
        echo 'rút tiền  thành công';
    }
	
	 //Xử lí lại status lỗi quá 3 phút chưa trả tiền
    public function updateStatusError(request $request){
        $newDateTime = Carbon::now()->subSeconds(5);
        //print_r($newDateTime);
        $LichSuChoiMomo = new LichSuChoiMomo;
        $GetLichSuChoiMomo = $LichSuChoiMomo->whereDate('created_at', Carbon::today())-> where('created_at','<=',$newDateTime)->where([
                        'status' => 2,'ketqua' => 1,
                    ])->get();
        foreach($GetLichSuChoiMomo as $row){
            $newDateTime = Carbon::now()->subMinutes(5);
            // 'dem ';
			$MaGiaoDich = new MaGiaoDich;
		    $dem = $MaGiaoDich->whereDate('created_at', Carbon::today())-> where('created_at','>=',$newDateTime)->where([
            'magiaodich' => $row->magiaodich,
		    ])->count();
		   // 'dem '. $dem;
			if($dem > 0){
			  $row->status = 3;
		    }else{
		       $row->status = 4;
		    }
           
            $row->save();
        }            
        echo 'Update 2 to 4 thành công';
    }
    //Get giao dịch và lưu lại
    public function SaveGiaoDich(request $request){
        //sleep(10);
        // 'cu';
        //return;
        $type_cron = 'savegiaodich';

        // $LimitCron = new LimitCron;
        // $GetLimitCron = $LimitCron->where([
        //     'type' => $type_cron,
        // ])->orderBy('id', 'desc')->limit(1);

        // if ($GetLimitCron->count() > 0){
        //     $GetLimitCron = $GetLimitCron->first();

        //     $time = time();
        //     if ( ($time - $GetLimitCron->time) <= 10 ) {
        //         //return 'Cron không thể xử lý ngay lúc nầy';
        //     }
    
        //     $LimitCron->type = $type_cron;
        //     $LimitCron->time = time();
        //     $LimitCron->save();
        // } else {
        //     $LimitCron->type = $type_cron;
        //     $LimitCron->time = time();
        //     $LimitCron->save();
        // }

        //Setting
        $Setting = new Setting;
        $GetSetting = $Setting->first();

        //Check bảo trì
        if ($GetSetting->baotri == 1) {
            return;
        }

        //WEB2M
        $WEB2M = new WEB2M;
        //'123';
        // + Lưu lại lịch sử giao dịch
        $AccountMomo = new AccountMomo;
        //$ListAccountMomo = $AccountMomo->where([
        //   'status' => 1,
       // ])->get();
        $ListAccountMomo = $AccountMomo->get();
        // '1234';
        foreach($ListAccountMomo as $row){
            
            
            $accountLevelMoney = new AccountLevelMoney;
			$countSdtAccountLevelMoney = $accountLevelMoney->where([
			'sdt' => $row->sdt,
			'status' => 1,
			])->count();
			if($countSdtAccountLevelMoney == 0){
			    continue;
			}
            //Lấy lịch sử giao dịch
            //dump($row);
           $ListGiaoDich = $WEB2M->GetGiaoDich($row->token,$row->webapi);
          
            //if($row->webapi ==1 ){
            if(true){
                
				 echo $row->webapi.'w2'; 
				      if (!isset($ListGiaoDich['status'])) {
               // dump($ListGiaoDich['momoMsg']);
                //if(isset($ListGiaoDich['status']) && $ListGiaoDich['status'] == 200){
                if ( isset($ListGiaoDich['momoMsg']['tranList']) ) {
                    $ListGD = $ListGiaoDich['momoMsg']['tranList'];
                 //if ( isset($ListGiaoDich['thueapimomo.vn']) ) {
                 //   $ListGD = $ListGiaoDich['thueapimomo.vn'];
                   echo '<br />Lấy dữ liệu lịch sử => ' . $row->sdt;
            
                } else {
                    $ListGD = [];
                }
                //dump($ListGD);
                //Lấy từng giao dịch
                $countGetNhanTien=0;
               // dump($ListGD);
               // $ListGD = collect($ListGD)->reverse()->toArray();
                //dump($ListGD);
                $ListGD = collect($ListGD)->sortByDesc('finishTime')->toArray();
                //dump($ListGD);
                
                foreach($ListGD as $res){
                    echo ' ' .   $res['tranId'].',';
                    if($res['io'] != 1){
					   continue;
				    }
				    $countGetNhanTien ++;
				    if($countGetNhanTien >=30){
				       // break;
				    }
                    // //Check giới hạn ngày
                    // $AccountMomo = new AccountMomo;
                    // $GetAccountMomo = $AccountMomo->where([
                    //     'sdt' => $row->sdt,
                    // ])->first();

                    // $gh1 = $GetAccountMomo->gioihan;

                    // $LichSuChoiMomo = new LichSuChoiMomo;
                    // $GetLichSuChoiMomo = $LichSuChoiMomo->whereDate('created_at', Carbon::today())->where('status', '!=', 5)->where([
                    //     'sdt_get' => $row->sdt,
                    // ])->get();

                    // $listLimit = 0;
                    // foreach($GetLichSuChoiMomo as $crush){
                    //     $listLimit = $listLimit + $crush->tiennhan;
                    // }

                    // $listLimit = $listLimit + (int) $res['amount'];
                    // $gh2 = $listLimit;
                      // echo 'a';
                    //if ($gh2 < $gh1) {
                        if (true) {
                        // echo 'c';
                        if ( !isset($res['comment']) ) {
                             $res['comment'] = ' ';
                        }
                        if ( isset($res['comment']) ) {
                            
                            //Đưa nội dung về chữ thường
                            $res['comment2'] = $res['comment'];
                            $res['comment'] = strtolower($res['comment']);
                            $res['comment'] = str_replace(' ', '', $res['comment']);

                            if ( $res['comment'] == 'h1' ) {
                        
                                //Setting
                                $Setting = new Setting;
                                $GetSetting = $Setting->first();
                        
                                //Check off game
                                if ($GetSetting->on_nohu == 1) {
                                    //Setting Nổ Hũ
                                    $NoHuu = new NoHuu;
                                    $Setting_NoHu = $NoHuu->first();

                                    //Kiểm tra giao dịch tồn tại chưa
                                    $LichSuChoiNoHu = new LichSuChoiNoHu;
                                    $Check = $LichSuChoiNoHu->where([
                                        'magiaodich' => (string) $res['tranId'],
                                    ])->count();

                                    if ($Check == 0) {
                                        if( (int) $res['amount'] == $Setting_NoHu->tiencuoc ){
                                            $nhanduoc = 0;
                                            $ketqua = 99;

                                            $socuoi = substr( (string) $res['tranId'], -4 );

                                            if ($socuoi[0] == $socuoi[1] && $socuoi[1] == $socuoi[2] && $socuoi[2] == $socuoi[3]) {
                                                $ketqua = 99;
                                                $LichSuChoiNoHu = new LichSuChoiNoHu;
                                                $GetLichSuChoiNoHu = $LichSuChoiNoHu->get();
                    
                                                foreach ($GetLichSuChoiNoHu as $sm) {
                                                    $nhanduoc = $nhanduoc + $sm->tiencuoc;
                                                    $nhanduoc = $nhanduoc - $sm->tiennhan;
                                                }
                    
                                                $nhanduoc = $nhanduoc + ($res['amount'] / 100) * $Setting_NoHu->ptvaohu;    
                                            }

                                            $LichSuChoiNoHu = new LichSuChoiNoHu;
                                            $LichSuChoiNoHu->sdt = $res['partnerId']; //SĐT người chơi
                                            $LichSuChoiNoHu->magiaodich = (string) $res['tranId']; //Mã giao dịch
                                            $LichSuChoiNoHu->tiencuoc = $res['amount']; //Tiền cược
                                            $LichSuChoiNoHu->tienvaohu = ($res['amount'] / 100) * $Setting_NoHu->ptvaohu;
                                            $LichSuChoiNoHu->tiennhan = $nhanduoc; //Nhận được
                                            $LichSuChoiNoHu->noidung = $res['comment2']; //Nội dung chuyển
                                            $LichSuChoiNoHu->ketqua = $ketqua;
                                            $LichSuChoiNoHu->status = 3; //Mặc định chờ xử lí
                                            $Setting_NoHu->tienmacdinh = $Setting_NoHu->tienmacdinh + $LichSuChoiNoHu->tienvaohu;
											$Setting_NoHu->save();
                                            $LichSuChoiNoHu->save();
                                        }
                                    }
                                }

                            } else {
                            
                                //Kiểm tra giao dịch tồn tại chưa
                                $newDateTime = Carbon::now()->subHours(72);
                                $LichSuChoiMomo = new LichSuChoiMomo;
                                $Check = $LichSuChoiMomo->where('created_at','>=',$newDateTime)->where([
                                    'magiaodich' => (string) $res['tranId'],
                                ])->count();

                                if ($Check == 0) {

                                    $NameGame = '';
                                    $tiennhan = 0;
                                    $ketqua = '';
                                    $flagMinMax = false;
                                    $accountLevelMoney = new AccountLevelMoney;
									$sdtLevelMoney = $accountLevelMoney->where([
									'sdt' => $row->sdt,
									'status' => 1,
									])->first();
									$minSdt= $sdtLevelMoney->min;
									$maxSdt= $sdtLevelMoney->max;
			                        //dump($sdtLevelMoney);
			                        //echo $minSdt;
			                        //echo $maxSdt;
                                    //Logic chẵn lẻ
                                    if ( $res['comment'] == 'c' || $res['comment'] == 'l' ) {
                                        if ($GetSetting->on_chanle == 1) {
                                            $ChanLe = new ChanLe;
                                            $Setting_ChanLe = $ChanLe->first();

                                            //if ( (int) $res['amount'] >= $Setting_ChanLe->min && (int) $res['amount'] <= $Setting_ChanLe->max ) {
                                            if ( (int) $res['amount'] >=  $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = 'Chẵn lẻ';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -1);

                                                if ($x == 0 || $x == 9) {
                                                    $ra = 3;
                                                } else {
                                                    if ($x % 2 == 0) {
                                                        $ra = 1;
                                                    } else {
                                                        $ra = 2;
                                                    }
                                                }

                                                if ($res['comment'] == 'c') {
                                                    $dat = 1;
                                                } else {
                                                    $dat = 2;
                                                }

                                                if ($dat == $ra) {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_ChanLe->tile;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }

                                    //Logic tài xỉu
                                    if ( $res['comment'] == 't' || $res['comment'] == 'x' ) {
                                        if ($GetSetting->on_taixiu == 1) {
                                            $TaiXiu = new TaiXiu;
                                            $Setting_TaiXiu = $TaiXiu->first();

                                            //if ( (int) $res['amount'] >= $Setting_TaiXiu->min && (int) $res['amount'] <= $Setting_TaiXiu->max ) {
                                             if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) { 
                                                $NameGame = 'Tài xỉu';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -1);

                                                if ($x == 5 || $x == 6 || $x == 7 || $x == 8) {
                                                    $ra = 1;
                                                } else {

                                                    if ($x == 0 || $x == 9) {
                                                        $ra = 3;
                                                    } else {
                                                        $ra = 2;
                                                    }
                                                }

                                                if ($res['comment'] == 't') {
                                                    $dat = 1;
                                                } else {
                                                    $dat = 2;
                                                }

                                                if ($dat == $ra) {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_TaiXiu->tile;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }

                                    //Logic chẵn lẻ 2
                                    if ( $res['comment'] == 'c2' || $res['comment'] == 'l2' ) {
                                        if ($GetSetting->on_chanle2 == 1) {
                                            $ChanLe2 = new ChanLe2;
                                            $Setting_ChanLe2 = $ChanLe2->first();

                                            //if ( (int) $res['amount'] >= $Setting_ChanLe2->min && (int) $res['amount'] <= $Setting_ChanLe2->max ) {
                                              if ( (int) $res['amount'] >=  $minSdt && (int) $res['amount'] <=  $maxSdt ) {
                                                $NameGame = 'Chẵn lẻ Tài Xỉu 2';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -1);

                                                if ($x % 2 == 0) {
                                                    $ra = 1;
                                                } else {
                                                    $ra = 2;
                                                }

                                                if ($res['comment'] == 'c2') {
                                                    $dat = 1;
                                                } else {
                                                    $dat = 2;
                                                }

                                                if ($dat == $ra) {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_ChanLe2->tile;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }
                                    
                                    if ( $res['comment'] == 'x2' || $res['comment'] == 't2' ) {
                                        if ($GetSetting->on_chanle2 == 1) {
                                            $ChanLe2 = new ChanLe2;
                                            $Setting_ChanLe2 = $ChanLe2->first();

                                            //if ( (int) $res['amount'] >= $Setting_ChanLe2->min && (int) $res['amount'] <= $Setting_ChanLe2->max ) {
                                              if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = 'Chẵn lẻ Tài Xỉu 2';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -1);

                                                if ($x < 5) {
                                                    $ra = 1;
                                                } else {
                                                    $ra = 2;
                                                }

                                                if ($res['comment'] == 'x2') {
                                                    $dat = 1;
                                                } else {
                                                    $dat = 2;
                                                }

                                                if ($dat == $ra) {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_ChanLe2->tile;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }

                                    //Logic gấp 3
                                    if ( $res['comment'] == 'g3' ) {
                                        if ($GetSetting->on_gap3 == 1) {
                                            $Gap3 = new Gap3;
                                            $Setting_Gap3 = $Gap3->first();

                                            //if ( (int) $res['amount'] >= $Setting_Gap3->min && (int) $res['amount'] <= $Setting_Gap3->max ) {
                                              if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = 'Gấp 3';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -2);
                                                $y = substr( (string) $res['tranId'] , -3);

                                                //Loại 1
                                                if ($x == '02' || $x == '13' || $x == '17' || $x == '19' || $x == '21' || $x == '29' || $x == '35' || $x == '37' || $x == '47' || $x == '49' || $x == '51' || $x == '54' || $x == '57' || $x == '63' || $x == '64' || $x == '74' || $x == '83' || $x == '91' || $x == '95' || $x == '96') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Gap3->tile1;
                                                } elseif ($x == '69' || $x == '96' || $x == '66' || $x == '99') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Gap3->tile2;
                                                } elseif ($y == '123' || $y == '234' || $y == '456' || $y == '678' || $y == '789') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Gap3->tile3;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }
                                    // Lô
                                    if ( $res['comment'] == 'f' ) {
                                        if ($GetSetting->on_gap3 == 1) {
                                            $Gap3 = new Gap3;
                                            $Setting_Gap3 = $Gap3->first();

                                            //if ( (int) $res['amount'] >= $Setting_Gap3->min && (int) $res['amount'] <= $Setting_Gap3->max ) {
                                            if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = 'Lô';

                                                //Logic
                                                $x = substr( (string) $res['tranId'] , -2);
                                                //$y = substr( (string) $res['tranId'] , -3);

                                                //Loại 1
                                                if ($x == '00' || $x == '04' || $x == '10' || $x == '15' || $x == '18' || $x == '22' || $x == '24' || $x == '27' || $x == '33' || $x == '35' || $x == '38' || $x == '40' || $x == '42' || $x == '47' || $x == '54' || $x == '56' || $x == '61' || $x == '65' || $x == '69' || $x == '72' || $x == '77' || $x == '81' || $x == '84' || $x == '94' || $x == '99') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * 3.5;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }

                                    //Logic tổng 3 số
                                    if ( $res['comment'] == 's' ) {
                                        if ($GetSetting->on_tong3so == 1) {
                                            $Tong3So = new Tong3So;
                                            $Setting_Tong3So = $Tong3So->first();

                                           // if ( (int) $res['amount'] >= $Setting_Tong3So->min && (int) $res['amount'] <= $Setting_Tong3So->max ) {
                                             if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = 'Tổng 3 số';
                                                $x = substr( (string) $res['tranId'] , -3);
                                                $y = str_split($x);

                                                $tong = 0 ;
                                                foreach($y as $ris){
                                                    $tong = $tong + (int) $ris;
                                                }

                                                if ($tong == '7' || $tong == '17' || $tong == '27') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Tong3So->tile1;
                                                } elseif ($tong == '8' || $tong == '18') {
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Tong3So->tile2;
                                                } elseif ($tong == '9' || $tong == '19'){
                                                    $ketqua = 1;
                                                    $tiennhan = (int) $res['amount'] * $Setting_Tong3So->tile3;
                                                } else {
                                                    $ketqua = 99;
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }

                                    //Logic 1 phần 3
                                    if ( $res['comment'] == 'n1' || $res['comment'] == 'n2' || $res['comment'] == 'n3' ) {
                                        if ($GetSetting->on_1phan3 == 1) {
                                            $X1Phan3 = new X1Phan3;
                                            $Setting_1Phan3 = $X1Phan3->first();

                                           // if ( (int) $res['amount'] >= $Setting_1Phan3->min && (int) $res['amount'] <= $Setting_1Phan3->max ) {
                                             if ( (int) $res['amount'] >= $minSdt && (int) $res['amount'] <= $maxSdt ) {
                                                $NameGame = '1 phần 3';

                                                $x = substr( (string) $res['tranId'] , -1);
                                                if ($res['comment'] == 'n1') {
                                                    if ($x == '1' || $x == '2' || $x == '3') {
                                                        $ketqua = 1;
                                                        $tiennhan = (int) $res['amount'] * $Setting_1Phan3->tile;
                                                    } else {
                                                        $ketqua = 99;
                                                    }
                                                } elseif ($res['comment'] == 'n2') {
                                                    if ($x == '4' || $x == '5' || $x == '6') {
                                                        $ketqua = 1;
                                                        $tiennhan = (int) $res['amount'] * $Setting_1Phan3->tile;
                                                    } else {
                                                        $ketqua = 99;
                                                    }
                                                } elseif ($res['comment'] == 'n3') {
                                                    if ($x == '7' || $x == '8' || $x == '9') {
                                                        $ketqua = 1;
                                                        $tiennhan = (int) $res['amount'] * $Setting_1Phan3->tile;
                                                    } else {
                                                        $ketqua = 99;
                                                    }
                                                }
                                            }else{
												$flagMinMax = true;
											}
                                        }
                                    }
                                    echo'e';
                                    if ($NameGame != '' && $row->status == 1) {
                                        //Kiểm tra số điện thoại tồn tại chưa tư dong hoàn tiền max 20k
									//$LichSuChoiMomo1 = new LichSuChoiMomo;
								//	$Check = $LichSuChoiMomo1->where([
								//		'sdt' => $res['partnerId'],
								//	])->count();
                                    $ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *THẮNG* ' . $row->sdt  : ' ~~THUA~~  ' . $row->sdt;
                                    //if ($Check == -1) {
									if (false) {
										if($ketqua == 99 && (int) $res['amount'] <= 20000){
												$tiennhan = $res['amount'];
												$ketqua = 1;
												$ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *HOÀN TIỀN* ' . $row->sdt : ' ~~THUA~~ ' ;
										}
									}
									
                                        $LichSuChoiMomo = new LichSuChoiMomo;
                                        $LichSuChoiMomo->sdt = $res['partnerId']; //SĐT người chơi
                                        $LichSuChoiMomo->sdt_get = $row->sdt; //SĐT admin
                                        $LichSuChoiMomo->magiaodich = (string) $res['tranId']; //Mã giao dịch
                                        $LichSuChoiMomo->tiencuoc = $res['amount']; //Tiền cược
                                        $LichSuChoiMomo->tiennhan = $tiennhan; //Nhận được
                                        $LichSuChoiMomo->trochoi = $NameGame; //Tên trò chơi
                                        $LichSuChoiMomo->noidung = $res['comment2']; //Nội dung chuyển
                                        $LichSuChoiMomo->ketqua = $ketqua; //Kết quả Thắng hay Thua
                                        $LichSuChoiMomo->status = 4; //Mặc định chờ xử lí
                                        $LichSuChoiMomo->save();
                                        // save top tuần
                                        $topTuan= new TopTuan;
                                        $getTopTuan = $topTuan->whereBetween('created_at',
                                        [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])
                                         ->where('sdt', $res['partnerId'])
                                            ->limit(1);
                                        if ($getTopTuan->count() > 0){
                                         $GetLimitCron = $getTopTuan->first();
                                         $GetLimitCron->tongtientuan = $GetLimitCron->tongtientuan + $res['amount'];
                                         $GetLimitCron->save();
                                        
                                        }else{
                                            $topTuan= new TopTuan;
                                            $topTuan->sdt = $res['partnerId'];
                                            $topTuan->tongtientuan =$res['amount'];
                                            $topTuan->save();
                                        }
                                        // end save top tuan
                                        $parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                	                    
                        	             $parameters["text"]='*SDT* ' . $LichSuChoiMomo->sdt  .' Game '.  $LichSuChoiMomo->trochoi .'  Mã Giao Dịch : ' . $LichSuChoiMomo->magiaodich .' Nội dung: ' . $LichSuChoiMomo->noidung .' Cược: '. number_format($LichSuChoiMomo->tiencuoc)  .'  Tiền nhận ' . number_format($LichSuChoiMomo->tiennhan) . '  Kết Quả:  ' . $ketquagane;
                        	             
                        	             $this->send("sendMessage",$parameters);
                        	            $parameters["chat_id"]='1970029182';
                        	             $this->send("sendMessage",$parameters);
                        	             $parameters["chat_id"]='5004810472';
                        	             $this->send("sendMessage",$parameters);
                        	             
                                    } else {
                                        if ( $res['comment'] == 'nap' || $res['comment'] == 'Nap' ) {
                                            //return;
                                            continue;
                                        }
										$LichSuChoiMomo = new LichSuChoiMomo;
										
										// 80% tiền nhận nếu sai nội dung hoặc giới hạn min max cược tiền
										
										$tiennhan = 0;
										$ketquagane = '~~THUA~~';
										if((int) $res['amount'] > 1000){
										    if($row->status == 2){
												if($ketqua==1){
													$tiennhan= (int) $res['amount'] * 1;
													$ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *HOÀN TIỀN SỐ BẢO TRÌ* '. $row->sdt : ' ~~THUA~~ ' ;
												}else{
												$ketqua=1;    
    											$tiennhan= (int) $res['amount'] * 0.82;
    											$ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *HOÀN TIỀN SỐ BẢO TRÌ* '. $row->sdt : ' ~~THUA~~ ' ;
												}
    										}else{
    										    $ketqua=1;
    										    if($flagMinMax){
    										    	$tiennhan= (int) $res['amount'] * 0.80;
    										    	$ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *HOÀN TIỀN MAX*  '. $row->sdt : ' ~~THUA~~ ' ;
    										    }else{
    											    $tiennhan= (int) $res['amount'] * 0.81;
    											    $ketquagane= $ketqua == '1' ? '👉👉👉👉👉 *HOÀN TIỀN Nội DUNG* '. $row->sdt : ' ~~THUA~~ ' ;
    										    }
    										}
    										
    										
										}
										if($tiennhan == 0){
										    $ketqua = 99;
										}
										$LichSuChoiMomo->sdt = $res['partnerId']; //SĐT người chơi
                                        $LichSuChoiMomo->sdt_get = $row->sdt; //SĐT admin
                                        $LichSuChoiMomo->magiaodich = (string) $res['tranId']; //Mã giao dịch
                                        $LichSuChoiMomo->tiencuoc = $res['amount']; //Tiền cược
                                        $LichSuChoiMomo->tiennhan = $tiennhan; //Nhận được
                                        $LichSuChoiMomo->trochoi = 'Chẵn lẻ'; //Tên trò chơi
                                        $LichSuChoiMomo->noidung = $res['comment2']; //Nội dung chuyển
                                        $LichSuChoiMomo->ketqua = $ketqua; //Kết quả Thắng hay Thua
                                        $LichSuChoiMomo->status = 4; //Mặc định chờ xử lí
                                        $LichSuChoiMomo->save();
										$parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                        	             $parameters["text"]='*SDT* ' . $LichSuChoiMomo->sdt  .' Game '.  $LichSuChoiMomo->trochoi .'  Mã Giao Dịch : ' . $LichSuChoiMomo->magiaodich .' Nội dung: ' . $LichSuChoiMomo->noidung .' Cược: '. number_format($LichSuChoiMomo->tiencuoc)  .'  Tiền nhận ' . number_format($LichSuChoiMomo->tiennhan) . '  Kết Quả:  ' . $ketquagane;
                        	            $this->send("sendMessage",$parameters);
                        	            $parameters["chat_id"]='1970029182';
                        	             $this->send("sendMessage",$parameters);
									}
                        
                                }
                            }
                       
                        }
                        
                    }
                  echo'b';
                }
               echo'K';
            }
           
            }else
			{ 
			    
            }
            
        }
        echo "<br />".'+ Lấy giao dịch thành công';        
    }
    //test kich hoat sdt 
    public function GetKichHoatSdt(request $request){
        $type_cron = 'kichhoatsdt ';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }
	    // + Lưu lại lịch sử giao dịch
        $AccountMomo = new AccountMomo;
        $ListAccountMomo = $AccountMomo->where([
            'status' => 2,
        ])->get();

        foreach($ListAccountMomo as $row){
			$row->status = 1 ;
			$row->save();
		}
        echo "<br />".'+ test kich hoat sdt';
    }	
    //Xử lý giao dịch
    public function TraThuongGiaoDich(request $request){
        return;
        $type_cron = 'trathuonggiaodich';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }


        $WEB2M = new WEB2M;
        $ConfigMessageMomo = new ConfigMessageMomo;

        //Bảo trì
        $Setting = new Setting;
        $GetSetting = $Setting->first();

        $GetSetting->baotri;

        //Check bảo trì
        if ($GetSetting->baotri == 1) {
            echo 'Máy chủ bảo trì!';
            return;
        }

        $LichSuChoiMomo = new LichSuChoiMomo;
        $ListLichSuChoiMomo = $LichSuChoiMomo->where([
            'status' => 1,
        ])->limit(15)->get();

        foreach($ListLichSuChoiMomo as $row){
            //Kiểm tra lại
            $Check = $LichSuChoiMomo->where([
                'id' => $row->id,
            ])->first();
            $newDateTime = Carbon::now()->subMinutes(20);
            //echo 'dem ';
			$MaGiaoDich = new MaGiaoDich;
		    $dem = $MaGiaoDich->whereDate('created_at', Carbon::today())-> where('created_at','>=',$newDateTime)->where([
            'magiaodich' => $Check->magiaodich,
		    ])->count();
		   echo 'dem '. $dem;
			if($dem > 0){
			  // update về trạng thái đã thanh toán;
			  $Check->status = 3;
              $Check->save();
			 continue;
		    }
            //Nếu vẫn đang ở trạng thái chờ
            if ($Check->status == 1) {

                //Chuyển thành trạng thái đang xử lí
                $Check->status = 2;
                $Check->save();

                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'sdt' => $Check->sdt_get,
                ])->first();

                $GetMessageTraThuong = $ConfigMessageMomo->where([
                    'type' => 'tra-thuong',
                ])->first();

                if ($Check->tiennhan > 0) {
                    // begin check cảnh báo sắp bảo trì
					$LichSuChoiMomo = new LichSuChoiMomo;
                    $GetLichSuChoiMomo = $LichSuChoiMomo->whereDate('created_at', Carbon::today())->where([
                        'sdt_get' => $Check->sdt_get,'status' => 3,'ketqua' => 1,
                    ])->get();

                    $listLimit = 0;
					$countLimit = 0;
                    foreach($GetLichSuChoiMomo as $crush){
                        $listLimit = $listLimit + $crush->tiennhan;
						$countLimit = $countLimit + 1;
                    }
					
					if($countLimit >= 185 || $listLimit > 27000000){
						//$GetMessageTraThuong->message ='CẢNH BÁO:SDT SẮP BẢO TRÌ HÃY LÊN WEB LẤY SỐ MỚI' ;
						//$GetMessageTraThuong->message ='Canh Bao:SDT '. $Check->sdt_get .' Sap Bao Tri Vi Sap Dat Han Muc Hay Len WEB Lay So Moi' ;
						$GetMessageTraThuong->message ='cảnh báo:SDT ' . $Check->sdt_get .' sắp bảo trì vì đạt hạn mức!hãy lên WEB lấy số mới' ;
						if($countLimit >= 190 || $listLimit > 28000000){
							$AccountMomo = new AccountMomo;
							$GetAccountMomo = $AccountMomo->where([
							'sdt' => $Check->sdt_get,
							])->first();
							$GetAccountMomo->status = 2;
							$GetAccountMomo->save();
							$parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                            $parameters["text"]='AUTO CHẴN LẺ:  '.  $Check->sdt_get . ' đặt vào trạng thái *BẢO TRÌ* Số Giao Dịch = ' . $countLimit . ' .Số Tiền Giao Dịch = ' . number_format($listLimit) ;        	
                            $this->sendSimMomo("sendMessage",$parameters);
                            $parameters["chat_id"]='1970029182';
                        	$this->sendSimMomo("sendMessage",$parameters);
						}
					}
				    // hoàn tiền
					if($Check->tiennhan < $Check->tiencuoc){
					    $amount= (int)($Check->tiencuoc * 0.81);
					    $amountnhan =(int)$Check->tiennhan;
					    if((int)$amount == (int)$amountnhan ){
					        	$GetMessageTraThuong->message ='hoàn tiền do: ghi sai nội dung chơi hoặc nội dung thừa dấu cách';
					    } 
					    if((int)$amount < (int)$amountnhan ){
					        	//$GetMessageTraThuong->message ='hoan tiền do: sdt ' . $Check->sdt_get .' dang choi bao tri! Hay Len WEB Lay So Moi';
					        	$GetMessageTraThuong->message ='hoàn tiền do: sdt đang chơi bảo trì! hãy lên WEB lấy số mới';
					    }
					    if((int)$amount > (int)$amountnhan ){
					        	$GetMessageTraThuong->message ='hoàn tiền do: chơi quá giới hạn Min hoặc giới hạn Max';
					    }
					
					}
                    // end
                        $Check->status = 4;
                        $Check->save();
                        continue;
                        //$res = $WEB2M->Bank(
                          //  $Account->token,
                          //  $Check->sdt,
                          //  $Account->password,
                          ///  $Check->tiennhan,
                           // $GetMessageTraThuong->message.' '.$Check->magiaodich,
						   //$Account->webapi
                      // );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                            $Check->status = 3;
                            $Check->save();
                            $MaGiaoDich = new MaGiaoDich;
                            $MaGiaoDich->magiaodich = $Check->magiaodich;
                            $MaGiaoDich->save();
                        } else {
                            $parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                            if (str_contains($res['msg'], '5.000.000') || str_contains($res['msg'], '30.000.000') || str_contains($res['msg'], '100.000.000' )  || str_contains($res['msg'], 'giao') || str_contains($res['msg'], '150')) {
                                
                                if (str_contains($res['msg'], '5.000.000')){
                                  $parameters["text"]= $Check->sdt_get . '    đạt max 5M/1 ngày '. ' AUTO CHẴN LẺ ';
                                }
                                if (str_contains($res['msg'], '30.000.000')){
                                 $parameters["text"]= $Check->sdt_get . '     đạt max 30M/1 ngày'. ' AUTO CHẴN LẺ' ;
                                }
                                if (str_contains($res['msg'], '100.000.000')){
                                  $parameters["text"]= $Check->sdt_get . '    đạt max 100M/1 ngày'. ' AUTO CHẴN LẺ' ;
                                }
                                if (str_contains($res['msg'], '150')){
                                  $parameters["text"]= $Check->sdt_get . '    đạt max 150 giao dịch'. ' AUTO CHẴN LẺ' ;
                                }
                        	      $this->sendSimMomo("sendMessage",$parameters);
								  $ListAccountMomo = $AccountMomo->where([
									'status' => 1,
									])->first();
									$Check->sdt_get = $ListAccountMomo->sdt;	
									$Account->status = 2;
									$Account->save();
							}
							if(str_contains($res['msg'], 'đủ tiền')){
							 $parameters["text"]=' AUTO CHẴN LẺ: ' . $Check->sdt_get . '   *không đủ tiền!* Mã : ' . $Check->magiaodich  . ' SDT : ' . $Check->sdt . ' Nội dung: ' . $Check->noidung . ' Cược: '. number_format($Check->tiencuoc)  .' Tiền nhận ' . number_format($Check->tiennhan);
							    $this->sendSimMomo("sendMessage",$parameters);
							    $this->sendHetTien("sendMessage",$parameters);
							    $parameters["chat_id"]='1970029182';
                        	$this->sendSimMomo("sendMessage",$parameters);
                        	
							    
							}
						
                            $Check->status = 4;
                            $Check->save();
                        }
                        
                        var_dump($res);
                        
                        sleep(3);
                        
                        if($Check->status == 3){
                            $parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                	                    
                        	             $parameters["text"]='*Trả Thưởng SDT* ' .    $Check->sdt  .' Game '.  $Check->trochoi .'  Mã Giao Dịch : ' . $Check->magiaodich . ' Nội Dung : ' .  $Check->noidung  .' Cược: '. number_format($Check->tiencuoc)  .'  Tiền nhận ' . number_format($Check->tiennhan) . '  *THÀNH CÔNG* '. $Check->sdt_get ;
                        	            $this->send("sendMessage",$parameters);
                        	             $parameters["chat_id"]='1970029182';
                        	             $this->send("sendMessage",$parameters);
                        }
                    
                    
                } else {
                    $Check->status = 3;
                    $Check->save();
                }
            }

        }

        echo "<br />".'+ Xử lí giao dịch hoàn tất';
    }

    //Xử lí lại giao dịch lỗi
    public function TraThuongGiaoDichError(request $request){
        //return;
        $type_cron = 'trathuonggiaodicherror';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        $WEB2M = new WEB2M;
        $ConfigMessageMomo = new ConfigMessageMomo;

        //Bảo trì
        $Setting = new Setting;
        $GetSetting = $Setting->first();

        $GetSetting->baotri;

        //Check bảo trì
        if ($GetSetting->baotri == 1) {
            echo 'Máy chủ bảo trì!';
            return;
        }

        $LichSuChoiMomo = new LichSuChoiMomo;
        $ListLichSuChoiMomo = $LichSuChoiMomo->where([
            'status' => 4,
        ])->limit(15)->get();

        foreach($ListLichSuChoiMomo as $row){
            //Kiểm tra lại
            $Check = $LichSuChoiMomo->where([
                'id' => $row->id,
            ])->first();
            $newDateTime = Carbon::now()->subMinutes(20);
            //echo 'dem ';
			$MaGiaoDich = new MaGiaoDich;
			//$dem = $MaGiaoDich->whereDate('created_at', Carbon::today())-> where('created_at','>=',$newDateTime)->where([
		    $dem = $MaGiaoDich->where([
            'magiaodich' => $Check->magiaodich,
		    ])->count();
		   //echo 'dem 12312'. $dem;
			if($dem > 0){
			   // update về trạng thái đã thanh toán;
			  $Check->status = 3;
              $Check->save();
			  continue;
		    }
		      // 'demabc'. $dem;
            //Nếu vẫn đang ở trạng thái chờ
            if ($Check->status == 4) {

                //Chuyển thành trạng thái đang xử lí
                $Check->status = 2;
                $Check->save();

                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'sdt' => $Check->sdt_get,
                ])->first();

                $GetMessageTraThuong = $ConfigMessageMomo->where([
                    'type' => 'tra-thuong',
                ])->first();

                if ($Check->tiennhan > 0) {
                        // begin check cảnh báo sắp bảo trì
					$LichSuChoiMomo = new LichSuChoiMomo;
                    $GetLichSuChoiMomo = $LichSuChoiMomo->whereDate('created_at', Carbon::today())->where([
                        'sdt_get' => $Check->sdt_get,'status' => 3,'ketqua' => 1,
                    ])->get();

                    $listLimit = 0;
					$countLimit = 0;
                    foreach($GetLichSuChoiMomo as $crush){
                        $listLimit = $listLimit + $crush->tiennhan;
						$countLimit = $countLimit + 1;
                    }
					
					if($countLimit >= 185 || $listLimit > 27000000){
						//$GetMessageTraThuong->message ='CẢNH BÁO:SDT SẮP BẢO TRÌ HÃY LÊN WEB LẤY SỐ MỚI';
						$GetMessageTraThuong->message ='cảnh báo:SDT ' . $Check->sdt_get .' sắp bảo trì vì đạt hạn mức!hãy lên WEB lấy số mới' ;
						if($countLimit >= 190 || $listLimit > 28000000){
							$AccountMomo = new AccountMomo;
							$GetAccountMomo = $AccountMomo->where([
							'sdt' => $Check->sdt_get,
							])->first();
							$GetAccountMomo->status = 2;
							$GetAccountMomo->save();
							$parameters = array(
	                                      "chat_id" => '1090916551',
                	                        "text" => 'hello chao',
                	                        "parse_mode" => 'Markdown'
                                    	);
                            $parameters["text"]=' AUTO CHẴN LẺ '. $Check->sdt_get . ' đặt vào trạng thái *BẢO TRÌ* Số Giao Dịch = ' . $countLimit . ' .Số Tiền Giao Dịch = ' . number_format($listLimit) ;        	
                            $this->sendSimMomo("sendMessage",$parameters);
                            $parameters["chat_id"]='1970029182';
                        	$this->sendSimMomo("sendMessage",$parameters);
						}
					}
					// hoàn tiền
					if($Check->tiennhan <= $Check->tiencuoc){
					    $amount= (int)($Check->tiencuoc * 0.81);
					    $amountnhan =(int)$Check->tiennhan;
					    if((int)$amount == (int)$amountnhan ){
					        	$GetMessageTraThuong->message ='hoàn tiền do: ghi sai nội dung chơi hoặc nội dung thừa dấu cách';
					        	
					    } 
					    //if((int)$amount <= (int)$amountnhan ){
					    if((int)($Check->tiencuoc * 0.82) == (int)$amountnhan || (int)($Check->tiencuoc * 1) == (int)$amountnhan){
					        	//$GetMessageTraThuong->message ='hoan tiền do: sdt ' . $Check->sdt_get .' dang choi bao tri! Hay Len WEB Lay So Moi';
					        	$GetMessageTraThuong->message ='hoàn tiền do: sdt đang chơi bảo trì! hãy lên WEB lấy số mới';
					        
					    }
					    if((int)$amount > (int)$amountnhan ){
					        	$GetMessageTraThuong->message ='hoàn tiền do: chơi quá giới hạn Min hoặc giới hạn Max';
					        
					    }
					
					}
                    // end

                        $res = $WEB2M->Bank(
                            $Account->token,
                            $Check->sdt,
                            $Account->password,
                            $Check->tiennhan,
                            $GetMessageTraThuong->message.' '.$Check->magiaodich,
							$Account->webapi
                        );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                             $Check->status = 3;
                             $Check->save();
                             $parameters = array(
	                            "chat_id" => '1090916551',
                	            "text" => 'hello chao',
                	            "parse_mode" => 'Markdown'
                            	);
                            	//*Trả Thưởng SDT*
                            	//$parameters["text"]='*Xử lý giao dịch lỗi*: SDT ' . $Check->sdt .' Game '. $Check->trochoi .' Mã giao dịch: ' .$Check->magiaodich  . ' Nội dung: ' . $Check->noidung  .' Cược: '. number_format($Check->tiencuoc)  .' Tiền nhận ' . number_format($Check->tiennhan) .'  *SĐT=>* '. $Check->sdt_get;
                	            $parameters["text"]='*Trả Thưởng SDT*: SDT ' . $Check->sdt .' Game '. $Check->trochoi .' Mã giao dịch: ' .$Check->magiaodich  . ' Nội dung: ' . $Check->noidung  .' Cược: '. number_format($Check->tiencuoc)  .' Tiền nhận ' . number_format($Check->tiennhan) .'  *SĐT=>* '. $Check->sdt_get;
                	            $this->send("sendMessage",$parameters);
                	            $parameters["chat_id"]='1970029182';
                        	   $this->send("sendMessage",$parameters);
                        	   $parameters["chat_id"]='5004810472';
                        	   $this->send("sendMessage",$parameters);
                            $MaGiaoDich = new MaGiaoDich;
                            $MaGiaoDich->magiaodich = $Check->magiaodich;
                            $MaGiaoDich->save();
                        } else {
                            
                            
                 //              if($res['msg'] == null || $res['msg'] == 'null' || $res['msg'] == 'NULL'){
				// 					continue;
				// 			}
                            $parameters = array(
        	                                      "chat_id" => '1090916551',
                        	                        "text" => 'hello chao',
                        	                        "parse_mode" => 'Markdown'
                                            	);
                                if (str_contains($res['msg'], '5') || str_contains($res['msg'], '30') || str_contains($res['msg'], '100' )  || str_contains($res['msg'], '5') || str_contains($res['msg'], '150') ) {
                                        if (str_contains($res['msg'], 'mới')){
                                          $parameters["text"]= $Check->sdt_get . '    đạt max 5M/1 ngày AUTO CHẴN LẺ'  ;
                                        }
                                        if (str_contains($res['msg'], '30')){
                                         $parameters["text"]= $Check->sdt_get . '     đạt max 30M/1 ngày AUTO CHẴN LẺ ' ;
                                        }
                                        if (str_contains($res['msg'], '100')){
                                          $parameters["text"]= $Check->sdt_get . '    đạt max 100M/1 ngày AUTO CHẴN LẺ ' ;
                                        }
                                        if (str_contains($res['msg'], '150')){ 
                                          $parameters["text"]= $Check->sdt_get . '    đạt max 150 giao dịch AUTO CHẴN LẺ ' ;
                                        }
                                        
                                        $parameters["text"] = $parameters["text" ] .  $res['msg'];
                                	      $this->sendSimMomo("sendMessage",$parameters);
        								  $ListAccountMomo = $AccountMomo->where([
        									'status' => 1,
        									])->first();
        									$Check->sdt_get = $ListAccountMomo->sdt;	
        									$Account->status = 2;
        									$Account->save();
        						  }
							if(str_contains($res['msg'], 'đủ tiền')){
							    $parameters["text"]='AUTO CHẴN LẺ :'. $Check->sdt_get . '   *không đủ tiền!* Mã : ' . $Check->magiaodich  . ' SDT : ' . $Check->sdt . ' Nội dung: ' . $Check->noidung . ' Cược: '. number_format($Check->tiencuoc)  .' Tiền nhận ' . number_format($Check->tiennhan) . $res['msg'];;
							    $this->sendSimMomo("sendMessage",$parameters);
							    $this->sendHetTien("sendMessage",$parameters);
							    $parameters["chat_id"]='1970029182';
                        	$this->sendSimMomo("sendMessage",$parameters);
                        	$parameters["chat_id"]='-1001649646662';
                        	$parameters["text"]='AUTO CHẴN LẺ :'. $Check->sdt_get . '   *không đủ tiền!* Mã : ' . $Check->magiaodich  . ' SDT : ......' . substr( (string) $Check->sdt, -6 ) . ' Nội dung: ' . $Check->noidung . ' Cược: '. number_format($Check->tiencuoc)  .' Tiền nhận ' . number_format($Check->tiennhan) .' Lý do '. $res['msg'];
                        	$this->sendSimMomo("sendMessage",$parameters);
							}
							
						    $Check->status = 4;
                            $Check->save();
                           
                        }
                        
                        var_dump($res);
                        
                        sleep(3);
                    
                    
                } else {
                    $Check->status = 3;
                    $Check->save();
                }
            }

        }

        echo "<br />".'+ Xử lí giao dịch hoàn tất';
    }

    //Trả thường top tuần
    public function GetTraThuongTuan(request $request){
        return;
        $type_cron = 'gettrathuongtuan';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        //Setting
        $Setting = new Setting;
        $GetSetting = $Setting->first();

        //Check bảo trì
        if ($GetSetting->baotri == 1) {
            return;
        }

        //Check game có bật không
        if ($GetSetting->on_trathuongtuan != 1) {
            return;
        }

        $SettingPhanThuongTop = new SettingPhanThuongTop;
        $LichSuChoiMomo = new LichSuChoiMomo;
        $Cache = new Cache;
        $GetCache = $Cache->first();

        //
        $TimeUpdate = $GetCache->time_bank_top_tuan;
        $TimeNow = date("d/m/Y");

        //Cập nhật khi bị rổng
        if ($GetCache->time_bank_top_tuan == '') {
            $GetCache->time_bank_top_tuan = date("d/m/Y");
            $GetCache->save();
        }

        $weekday = date("l");
        $weekday = strtolower($weekday);
        
        if ($weekday == 'monday') {
            if ($TimeNow != $TimeUpdate) {

                //Cập nhật lại thời gian update
                $GetCache->time_bank_top_tuan = date("d/m/Y");
                $GetCache->save();

                //Thuật toán tìm TOP tuần
                $TopTuan = [];
                $dem = 0;

                $ListSDT = [];
                $st = 0;

                $now = Carbon::now();
                $weekStartDate = $now->startOfWeek()->format('Y-m-d H:i');
                $weekEndDate = $now->endOfWeek()->format('Y-m-d H:i');
                $date = \Carbon\Carbon::today()->subDays(7);

                $ListLichSuChoiMomo = $LichSuChoiMomo->where('created_at','>=',$date)->get();

                foreach ($ListLichSuChoiMomo as $row) {
                    $sdt = $row->sdt;

                    $check = True;
                    foreach ($ListSDT as $res) {
                        if ($res == $sdt) {
                            $check = False;
                        }
                    }

                    if ($check) {
                        $ListSDT[$st] = $sdt;
                        $st ++;
                    }
                    
                }

                $ListUser = [];
                $dem = 0;

                foreach($ListSDT as $row){
                    $Result = $LichSuChoiMomo->where([
                        'sdt' => $row,
                        'status' => 3,
                    ])->where('created_at','>=',$date)->get();

                    $ListUser[$dem]['sdt'] = $row;
                    $ListUser[$dem]['tiencuoc'] = 0;

                    foreach ($Result as $res) {
                        $ListUser[$dem]['tiencuoc'] = $ListUser[$dem]['tiencuoc'] + $res->tiencuoc;
                    }

                    $dem ++;
                }

                $UserTop = [];
                $st = 0;

                if ($dem > 1) {
                    // Đếm tổng số phần tử của mảng
                    $sophantu = count($ListUser);
                    // Lặp để sắp xếp
                    for ($i = 0; $i < $sophantu - 1; $i++)
                    {
                        // Tìm vị trí phần tử lớn nhất
                        $max = $i;
                        for ($j = $i + 1; $j < $sophantu; $j++){
                            if ($ListUser[$j]['tiencuoc'] > $ListUser[$max]['tiencuoc']){
                                $max = $j;
                            }
                        }
                        // Sau khi có vị trí lớn nhất thì hoán vị
                        // với vị trí thứ $i
                        $temp = $ListUser[$i];
                        $ListUser[$i] = $ListUser[$max];
                        $ListUser[$max] = $temp;
                    }

                    $UserTop = $ListUser;
                } else {
                    $UserTop = $ListUser;
                }

                $UserTopTuan = [];
                $dem = 0;

                foreach ($UserTop as $row) {
                    if ( $dem < 5 ) {
                        $UserTopTuan[$dem] = $row;
                        $UserTopTuan[$dem]['sdt2'] = substr($row['sdt'], 0, 6).'******';
                        $dem ++;
                    }
                }


                $dem = 1;
                foreach ($UserTopTuan as $row) {
                    $SettingPhanThuongTop = new SettingPhanThuongTop;
                    $GetSettingPhanThuongTop = $SettingPhanThuongTop->where([
                        'top' => $dem,
                    ])->first();

                    $LichSuTraThuongTuan = new LichSuTraThuongTuan;
                    $LichSuTraThuongTuan->sdt = $row['sdt'];
                    $LichSuTraThuongTuan->sotien = $GetSettingPhanThuongTop->phanthuong;
                    $LichSuTraThuongTuan->status = 1;
                    $LichSuTraThuongTuan->save();    
                    
                    $dem ++;
                }

            }
        }

        echo "<br />".'+ Lưu dữ liệu trả thưởng hoàn tất';
    }

    public function XuLiTraThuongTuan(request $request){
        return;
        $type_cron = 'xulitrathuongtuan';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        $LichSuTraThuongTuan = new LichSuTraThuongTuan;
        $GetLichSuTraThuongTuan = $LichSuTraThuongTuan->where([
            'status' => 1,
        ])->limit(15)->get();

        $WEB2M = new WEB2M;
        foreach ($GetLichSuTraThuongTuan as $row) {
            $Check = $LichSuTraThuongTuan->where([
                'id' => $row->id,
            ])->first();

            if ($Check->status == 1) {

                //Đang xử lý
                $Check->status = 2;
                $Check->save();

                //Lấy tài khoản Momo
                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'status' => 1
                ])->first();

                //Lấy message
                $ConfigMessageMomo = new ConfigMessageMomo;
                $GetMessageTraThuong = $ConfigMessageMomo->where([
                    'type' => 'thuong-top-tuan',
                ])->first();

                        $res = $WEB2M->Bank(
                            $Account->token,
                            $Check->sdt,
                            $Account->password,
                            $Check->sotien,
                            $GetMessageTraThuong->message.' '.$Check->magiaodich,
							$Account->webapi
                        );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                            $Check->status = 3;
                            $Check->save();
                        } else {
                            $Check->status = 4;
                            $Check->save();
                        }
                        
                        var_dump($res);
                        
                        sleep(3);                

            }
        }
        echo 'Xử lí giao dịch hoàn tất';
    }

    public function XuLiTraThuongTuanLoi(request $request){
        return;
        $type_cron = 'xulitrathuongtuanloi';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        $LichSuTraThuongTuan = new LichSuTraThuongTuan;
        $GetLichSuTraThuongTuan = $LichSuTraThuongTuan->where([
            'status' => 4,
        ])->limit(15)->get();

        $WEB2M = new WEB2M;
        foreach ($GetLichSuTraThuongTuan as $row) {
            $Check = $LichSuTraThuongTuan->where([
                'id' => $row->id,
            ])->first();

            if ($Check->status == 4) {

                //Đang xử lý
                $Check->status = 2;
                $Check->save();

                //Lấy tài khoản Momo
                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'status' => 1
                ])->first();

                //Lấy message
                $ConfigMessageMomo = new ConfigMessageMomo;
                $GetMessageTraThuong = $ConfigMessageMomo->where([
                    'type' => 'thuong-top-tuan',
                ])->first();
                        $res = $WEB2M->Bank(
                            $Account->token,
                            $Check->sdt,
                            $Account->password,
                            $Check->sotien,
                            $GetMessageTraThuong->message.' '.$Check->magiaodich,
							$Account->webapi
                        );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                            $Check->status = 3;
                            $Check->save();
                        } else {
                            $Check->status = 4;
                            $Check->save();
                        }
                        
                        var_dump($res);
                        
                        sleep(3);
                
            }
        }
        echo "<br />".'+ Xử lí giao dịch hoàn tất';
    }

    public function XuLiNoHuu(request $request){
        $type_cron = 'xulinohu';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        //Setting nổ hũ
        $NoHuu = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $LichSuChoiNoHu = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 1,
        ])->limit(15)->get();

        $WEB2M = new WEB2M;
        foreach ($GetLichSuChoiNoHu as $row) {
            $Check = $LichSuChoiNoHu->where([
                'id' => $row->id,
            ])->first();

            if ($Check->status == 1) {

                //Đang xử lý
                $Check->status = 2;
                $Check->save();

                //Lấy tài khoản Momo
                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'status' => 1
                ])->first();

                //Lấy message
                $ConfigMessageMomo = new ConfigMessageMomo;
                $GetConfigMessageMomo = $ConfigMessageMomo->where([
                    'type' => 'no-huu',
                ])->first();

                if ($Check->ketqua == 100){

                        $res = $WEB2M->Bank(
                            $Account->token,
                            $Check->sdt,
                            $Account->password,
                            $Check->tiennhan,
                            $GetMessageTraThuong->message.' '.$Check->magiaodich,
							$Account->webapi
                        );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                            $Check->status = 3;
                            $Check->save();
                        } else {
                            $Check->status = 4;
                            $Check->save();
                        }
                        
                        var_dump($res);
                        
                        sleep(3);
                
                    
                } else {
                    $res->status = 3;
                    $res->save();
                }              
            }
        }
        echo "<br />".'+ Xử lí giao dịch hoàn tất';
    }

    public function XuLiNoHuuLoi(request $request){
        $type_cron = 'xulinohuloi';

        $LimitCron = new LimitCron;
        $GetLimitCron = $LimitCron->where([
            'type' => $type_cron,
        ])->orderBy('id', 'desc')->limit(1);

        if ($GetLimitCron->count() > 0){
            $GetLimitCron = $GetLimitCron->first();

            $time = time();
            if ( ($time - $GetLimitCron->time) <= 10 ) {
                //return 'Cron không thể xử lý ngay lúc nầy';
            }
    
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        } else {
            $LimitCron->type = $type_cron;
            $LimitCron->time = time();
            $LimitCron->save();
        }

        //Setting nổ hũ
        $NoHuu = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $LichSuChoiNoHu = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 4,
        ])->limit(15)->get();

        $WEB2M = new WEB2M;
        foreach ($GetLichSuChoiNoHu as $row) {
            $Check = $LichSuChoiNoHu->where([
                'id' => $row->id,
            ])->first();

            if ($Check->status == 4) {
                
                //Đang xử lý
                $Check->status = 2;
                $Check->save();

                //Lấy tài khoản Momo
                $AccountMomo = new AccountMomo;
                $Account = $AccountMomo->where([
                    'status' => 1
                ])->first();

                //Lấy message
                $ConfigMessageMomo = new ConfigMessageMomo;
                $GetConfigMessageMomo = $ConfigMessageMomo->where([
                    'type' => 'no-huu',
                ])->first();

                if ($Check->ketqua == 1){

                        $res = $WEB2M->Bank(
                            $Account->token,
                            $Check->sdt,
                            $Account->password,
                            $Check->tiennhan,
                            $GetMessageTraThuong->message.' '.$Check->magiaodich,
							$Account->webapi
                        );
    
                        
                        if ( isset($res['status']) && $res['status'] == 200) {
                            $Check->status = 3;
                            $Check->save();
                        } else {
                            $Check->status = 4;
                            $Check->save();
                        }
                        
                        var_dump($res);
                        
                        sleep(3);
                
                } else {
                    $Check->status = 3;
                    $Check->save();
                }              
            }
        }
        echo "<br />".'+ Xử lí giao dịch hoàn tất';
    }

}