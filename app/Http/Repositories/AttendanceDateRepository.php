<?php
/**
 *File name : AttendanceSessionRepository.php / Date: 10/26/2021 - 9:39 PM

 */

namespace App\Http\Repositories;

use App\Models\AccountMomo;
use App\Models\AttendanceDateSetting;
use App\Models\AttendanceSession;
use App\Models\AttendanceSetting;
use App\Models\LichSuChoiAttendanceDate;
use App\Models\LichSuChoiMomo;
use App\Models\Setting;
use App\Models\UserAttendanceSession;
use App\Traits\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AttendanceDateRepository extends Repository
{

    public function __construct()
    {
    }


    public function getMocchoi()
    {
        return AttendanceDateSetting::orderBy('mocchoi')->get()->toArray();
    }

    public function checkTurOnAttendanceDate()
    {
        $attendanceRepo = new AttendanceSessionRepository();
        $setting        = $attendanceRepo->getSettingWebsite();
        if (isset($setting['baotri']) && $setting['baotri'] == 1) {
            return false;
        }
        if (!isset($setting['on_diemdanh_ngay'])) {
            return true;
        }
        return $setting['on_diemdanh_ngay'] == TURN_ON_SETTING;
    }


    public function handleAttendanceDate($data)
    {
        $attendanceDateRepo = new AttendanceDateRepository();
        $phone              = (new PhoneNumber)->convert($data['phone']);
        $phoneOld           = (new PhoneNumber)->convert($data['phone'], true);
        $phonesAccount      = AccountMomo::where('sdt', $phone)->orWhere('sdt', $phoneOld)->get();
        $date               = Carbon::today()->toDateString();
        $lichSuMomosOfPhone = LichSuChoiMomo::where('sdt', $phone)
            ->where('created_at', '>=', $date)
            ->orWhere(function($query) use ($phoneOld, $date) {
                $query->where('sdt', $phoneOld)
                    ->where('created_at', '>=', $date);
            })
            ->get();
        if (count($lichSuMomosOfPhone) == 0 && count($phonesAccount) == 0) {
            return $this->responseResult("Oh !! Số điện thoại này chưa chơi game nào, hãy kiểm tra lại");
        }
        $mocchois     = $attendanceDateRepo->getMocchoi();
        $mocchoiFirst = collect($mocchois)->first();
        if (count($mocchois) == 0) {
            return $this->responseResult("Hệ thống đang bảo trì vui lòng thử lại sau!");
        }
        $sumTien = $lichSuMomosOfPhone->sum('tiencuoc');
        $lichsuChoi = $this->getLichSuChoiDiemDanhNgay($date, $phone, $phoneOld);
        if (count($lichsuChoi) == 0) {
            if ($sumTien < $mocchoiFirst['mocchoi']) {
                return $this->responseResult("Oh !! . Nay bạn đã chơi hết: ".number_format($sumTien)." VNĐ. Bạn chưa đủ mốc tiền để nhận thưởng trong ngày hôm nay. Cố gắng chơi thêm nhé!!!");
            }
            $mocSumTien = collect($mocchois)->where('mocchoi', "<=", $sumTien)->last();

            if (is_null($mocSumTien)) {
                return $this->responseResult("Hệ thống đang bảo trì vui lòng thử lại sau!");
            }
            $tiennhan = $mocSumTien['tiennhan'];
            $this->insertPhoneToTableLichSu($phoneOld, $mocSumTien['mocchoi'], $tiennhan);
        } else {
            $mocDaChoiMax = array_key_last($lichsuChoi);
            $mocSumTien = collect($mocchois)->where('mocchoi', "<=", $sumTien)->last();
//            $mocTiepTheo  = collect($mocchois)->where('mocchoi', ">", $mocDaChoiMax)->first();
            if (is_null($mocSumTien) || $this->mocchoiIsMax(collect($mocchois)->last(), $mocDaChoiMax)) {
                return $this->responseResult("Bạn đã nhận thưởng hết trong ngày hôm nay. Vui lòng quay lại trò chơi vào ngày mai!!!");
            }
            if ($mocDaChoiMax)
//            $mocSumTien['mocchoi'] == $mocDaChoiMax
                $mocDatTiepTheo = $mocSumTien['mocchoi'];
            if ($mocDaChoiMax == $mocDatTiepTheo){
                return $this->responseResult("Oh !! . Nay bạn đã chơi hết: ".number_format($sumTien)." VNĐ. Bạn chưa đủ mốc tiền tiếp theo để nhận thưởng thêm trong hôm nay. Cố gắng Pang thêm nhé!!!");
            }
            if ($sumTien >= $mocDatTiepTheo) {
                $tiennhan = $mocSumTien['tiennhan'];
                $this->insertPhoneToTableLichSu($phoneOld, $mocDatTiepTheo, $tiennhan);
            } else {
                return $this->responseResult("Oh !! . Nay bạn đã chơi hết: ".number_format($sumTien)." VNĐ. Bạn chưa đủ mốc tiền tiếp theo để nhận thưởng thêm trong hôm nay. Cố gắng Pang thêm nhé!!!");
            }
        }
        return $this->responseResult("Oh!! Chúc mừng bạn đã nhận được ".number_format($tiennhan)." VNĐ ĐỚP ÍT THÔI!!");
    }

    private function getPhoneAccountMomo()
    {
        $cache = Cache::get('cache_get_sdt_account_momo');
        if (!is_null($cache)) {
            return $cache;
        }
        $account = AccountMomo::orderBy('status')->first();
        $phone   = $account->sdt;
        Cache::put('cache_get_sdt_account_momo', $phone, Carbon::now()->addMinutes(10));
        return $phone;
    }


    private function insertPhoneToTableLichSu($phone, $mocchoi, $tienNhan)
    {
        $phoneGet = $this->getPhoneAccountMomo();
        $billCode = 'Nghiệm vụ ngày '.bin2hex(random_bytes(3)).time();
        $this->insertToLichSuMoMo($phone, $tienNhan, $phoneGet, $billCode);
        $this->insertToLichSuDiemDanhNgay($phone, $mocchoi, $tienNhan, $phoneGet, $billCode);
    }

    /**
     * @param $phone
     * @param $tienNhan
     *
     * @throws \Exception
     */
    private function insertToLichSuMoMo($phone, $tienNhan, $phoneGet, $billCode)
    {
        return DB::table('lich_su_choi_momos')->insert([
            'sdt'        => $phone,
            'sdt_get'    => $phoneGet,
            'magiaodich' => $billCode,
            'tiencuoc'   => 0,
            'tiennhan'   => $tienNhan,
            'trochoi'    => "Nghiệm vụ ngày",
            'noidung'    => "NVN",
            'ketqua'     => 1,
            'status'     => STATUS_LSMOMO_TAM_THOI,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    private function insertToLichSuDiemDanhNgay($phone, $mocchoi, $tienNhan, $phoneGet, $billCode)
    {
        return DB::table('lich_su_attendance_date')->insert([
            'date'       => Carbon::today()->toDateString(),
            'phone'      => $phone,
            'mocchoi'    => $mocchoi,
            'tiennhan'   => $tienNhan,
            'sdt_get'    => $phoneGet,
            'magiaodich' => $billCode,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

    /**
     * @return array
     */
    private function responseResult($message): array
    {
        return [
            'status'  => 2,
            'message' => $message,
        ];
    }

    /**
     * @param  string  $date
     * @param  array  $phone
     *
     * @return mixed
     */
    private function getLichSuChoiDiemDanhNgay($date, $phone, $phoneOld)
    {
        $lichsuChoi = LichSuChoiAttendanceDate::whereDate('date', $date)
            ->where('phone', $phone)
            ->orWhere(function($query) use ($phoneOld, $date) {
                $query->where('phone', $phoneOld)
                    ->whereDate('date', $date);
            })
            ->orderBy("mocchoi")
            ->get()
            ->pluck('tiennhan', 'mocchoi')
            ->toArray();
        return $lichsuChoi;
    }

    public function mocchoiIsMax($mocchoiMax, $mocchoiCheck)
    {
        return $mocchoiMax['mocchoi'] == $mocchoiCheck;
    }

}