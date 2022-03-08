<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AccountMomoRepository;
use App\Http\Repositories\AttendanceDateRepository;
use App\Http\Repositories\AttendanceSessionRepository;
use App\Traits\PhoneNumber;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\ChanLe;
use App\Models\TaiXiu;
use App\Models\ChanLe2;
use App\Models\Gap3;
use App\Models\Tong3So;
use App\Models\X1Phan3;
use App\Models\AccountMomo;
use App\Models\LichSuChoiMomo;
use App\Models\SettingPhanThuongTop;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;
use App\Models\NoHuu;
use App\Models\LichSuChoiNoHu;
use App\Models\LichSuBank;
use App\Models\TopTuan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Cache;

class HomeController extends Controller
{

    //index
    protected $attendanceSessionRepository;
    protected $attendanceDateRepository;
    protected $accountMomoRepo;

    public function __construct()
    {
        $this->attendanceSessionRepository = new AttendanceSessionRepository();
        $this->attendanceDateRepository    = new AttendanceDateRepository();
        $this->accountMomoRepo             = new AccountMomoRepository();
    }

    public function index()
    {
        //Lịch sử chơi Momo
//        if (Cache::has('indexData')) {
//             return view(
//            'HomePage.home',
//            Cache::get('indexData')
//            );
//        }
//        
        //Setting
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Trang chủ';
        //        $accountMomosGroupTypes = $this->accountMomoRepo->getListAccountMomosGroupType();
        //Bảo trì
        //Chẵn lẻ
        [
            $Setting_ChanLe,
            $Setting_TaiXiu,
            $Setting_ChanLe2,
            $Setting_Gap3,
            $Setting_Tong3So,
            $Setting_1Phan3,
        ] = $this->getSetingGame();

        $UserTopTuan = [];
        //Phần thưởng tuần
        $SettingPhanThuongTop    = new SettingPhanThuongTop;
        $GetSettingPhanThuongTop = $SettingPhanThuongTop->get();

        //Setting nổ hũ
        $NoHuu        = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        //Thông báo nổ hũ
        $LichSuChoiNoHu    = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 3,
            'ketqua' => 1,
        ])->get();

        $GetLichSuChoiNoHus = [];
        $dem                = 0;

        foreach ($GetLichSuChoiNoHu as $row) {
            $GetLichSuChoiNoHus[$dem]              = $row;
            $GetLichSuChoiNoHus[$dem]['sdt2']      = substr($row['sdt'], 0, 6).'******';
            $GetLichSuChoiNoHus[$dem]['tiennhan2'] = $row['tiennhan'] + $Setting_NoHu->tienmacdinh;
            $dem++;
        }
        $secondRealTime           = $this->attendanceSessionRepository->getSecondsRealtime();
        $dataAttendanceSession    = $this->attendanceSessionRepository->getDataAttendanceSession();
        $attendanceSessionCurrent = $dataAttendanceSession['current'];
        $listSessionsPast         = $dataAttendanceSession['sessions_past'];
        $phoneWinLatest           = $dataAttendanceSession['phone_win_latest'];
        $usersAttendance          = $this->attendanceSessionRepository->getUsersAttendanceSession($attendanceSessionCurrent);
//        dd($usersAttendance->toArray());
        $totalAmount              = $this->attendanceSessionRepository->getTotalAmountAttendanceSession();
        $countUsersAttendance     = count($usersAttendance);
        $listUserAttendance       = $usersAttendance->take(10);
        $checkCanAttendance       = $this->attendanceSessionRepository->checkTurOnAttendance();
        $checkCanAttendanceDate   = $this->attendanceDateRepository->checkTurOnAttendanceDate();
        $setting                  = $this->attendanceSessionRepository->getAttendanceSetting();
        $timeEach                 = $setting['time_each'] ?? TIME_EACH_ATTENDANCE_SESSION;
        $startTime                = isset($setting['start_time']) ? Carbon::parse($setting['start_time']) : Carbon::parse(TIME_START_ATTENDANCE);
        $endTime                  = isset($setting['end_time']) ? Carbon::parse($setting['end_time']) : Carbon::parse(TIME_END_ATTENDANCE);
        $now                      = Carbon::now();
        $canAttendance            = $now->between($startTime, $endTime) && $checkCanAttendance;
        $configAttendanceDate     = $this->attendanceDateRepository->getMocchoi();
        //View
        $data = view(
            'HomePage.home',
            compact(
                'GetSetting',
                //                'accountMomosGroupTypes',
                'Setting_ChanLe',
                'Setting_TaiXiu',
                'Setting_ChanLe2',
                'Setting_Gap3',
                'Setting_Tong3So',
                'Setting_1Phan3',
                'UserTopTuan',
                'GetSettingPhanThuongTop',
                'GetLichSuChoiNoHus',
                'attendanceSessionCurrent',
                'secondRealTime',
                'listSessionsPast',
                'countUsersAttendance',
                'phoneWinLatest',
                'usersAttendance',
                'listUserAttendance',
                'canAttendance',
                'totalAmount',
                'checkCanAttendance',
                'setting',
                'timeEach',
                'checkCanAttendanceDate',
                'configAttendanceDate',
            )
        );
        Cache::put('indexData', compact(
                'GetSetting',
                //                'accountMomosGroupTypes',
                'Setting_ChanLe',
                'Setting_TaiXiu',
                'Setting_ChanLe2',
                'Setting_Gap3',
                'Setting_Tong3So',
                'Setting_1Phan3',
                'UserTopTuan',
                'GetSettingPhanThuongTop',
                'GetLichSuChoiNoHus',
                'attendanceSessionCurrent',
                'secondRealTime',
                'listSessionsPast',
                'countUsersAttendance',
                'phoneWinLatest',
                'usersAttendance',
                'listUserAttendance',
                'canAttendance',
                'totalAmount',
                'checkCanAttendance',
                'setting',
                'timeEach',
                'checkCanAttendanceDate',
                'configAttendanceDate',
            ), TIME_CACHE_LOAD_DATA + 30);
        return $data;
    }

    public function realTimeAttendance(Request $request)
    {
        $timeLast                 = $request->all();
        $updateCache              = $timeLast % 20 == 0;
        $secondsRealtime          = $this->attendanceSessionRepository->getSecondsRealtime($updateCache);
        $dataAttendanceSession    = $this->attendanceSessionRepository->getDataAttendanceSession();
        $attendanceSessionCurrent = $dataAttendanceSession['current'];
        $phoneWinLatest           = $dataAttendanceSession['phone_win_latest'];
        $listSessionsPast         = $dataAttendanceSession['sessions_past'];

        $usersAttendance      = $this->attendanceSessionRepository->getUsersAttendanceSession($attendanceSessionCurrent);
        $countUsersAttendance = count($usersAttendance);
        $usersAttendance      = $usersAttendance->transform(function($user) {
            $user->phone = $user->getPhone();
            return $user;
        });
        $phoneUsersAttendance = $usersAttendance->pluck('phone')->toArray();
        $totalAmount          = $this->attendanceSessionRepository->getTotalAmountAttendanceSession();

        $phonesAttendance    = view('HomePage.phone_user_attendance', compact('phoneUsersAttendance'))->render();
        $viewListSessionPast = view('HomePage.table_sessions_attendance', compact('listSessionsPast'))->render();
        return json_encode([
            'session_current_code'   => $attendanceSessionCurrent->id,
            'phone_win_latest'       => $phoneWinLatest,
            'count_users_attendance' => $countUsersAttendance,
            'phones_attendance'      => $phonesAttendance,
            'total_amount'           => number_format($totalAmount),
            'view_list_session_past' => $viewListSessionPast,
            'second_realtime'        => $secondsRealtime,
        ], true);
    }

    public function attendanceSession(Request $request)
    {
        $data = $request->all();
        if (!isset($data['phone'])) {
            return response(['status' => 2, 'message' => "Có lỗi xảy ra vui lòng thử lại"]);
        }
        if (!is_numeric($data['phone']) || !$this->isDigits($data['phone'])) {
            return response(['status' => 2, 'message' => "Số điện thoại sai định dạng. Vui lòng kiểm tra lại"]);
        }
        $startTime = Carbon::parse(TIME_START_ATTENDANCE);
        $endTime   = Carbon::parse(TIME_END_ATTENDANCE);
        $now       = Carbon::now();
        if (!$now->between($startTime, $endTime)) {
            return response(['status' => 2, 'message' => "Thời gian bắt đầu điểm danh từ 7h sáng đến 11h hằng ngày!"]);
        }
        if ($this->checkPhoneHasAttendanceSessionCurrent($data['phone'])) {
            return response(['status' => 2, 'message' => "Số điện thoại của bạn đã điểm danh trong phiên này!"]);
        }
        $this->attendanceSessionRepository->insertUsersAttendanceSession($data);
        return "SUCCESS";
    }

    public function attendanceDate(Request $request)
    {
        $data = $request->all();
        if (!isset($data['phone'])) {
            return response(['status' => 2, 'message' => "Có lỗi xảy ra vui lòng thử lại"]);
        }
        if (!$this->attendanceDateRepository->checkTurOnAttendanceDate()) {
            return response(['status' => 2, 'message' => "Hệ thống đang bảo trì"]);
        }
        $data = $this->attendanceDateRepository->handleAttendanceDate($data);
        return $data;
    }

    private function checkPhoneHasAttendanceSessionCurrent($phone)
    {
        $recordsOfPhone = $this->attendanceSessionRepository->queryUsersAttendanceByPhone($phone);
        return count($recordsOfPhone) > 0;
    }

    public function isDigits(string $s, int $minDigits = 9, int $maxDigits = 14): bool
    {
        return preg_match('/^[0-9]{'.$minDigits.','.$maxDigits.'}\z/', $s);
    }

    public function getDataAfterLoad()
    {
        //Lịch sử chơi Momo
        if (Cache::has('AllData')) {
             return Cache::get('AllData');
        }
        //Lịch sử chơi Momo
        $LichSuChoiMomo                 = new LichSuChoiMomo;
        $LichSuChoiMomoToDay            = $LichSuChoiMomo->whereDate('created_at', Carbon::today())->where([
            'ketqua' => 1,
            'status' => 3,
        ])->orderBy('id', 'desc')->get();
        $accountMomosGroupTypes         = $this->accountMomoRepo->getListAccountMomosWithAccountLevel();
        $accountMomosGroupTypesAllGames = collect();
        if (!is_null($accountMomosGroupTypes->get(CONFIG_ALL_GAME)) && count($accountMomosGroupTypes->get(CONFIG_ALL_GAME)) > 0) {
            $accountMomosGroupTypesAllGames = $accountMomosGroupTypes->get(CONFIG_ALL_GAME);
        }
        $ListLichSuChoiMomo = $LichSuChoiMomoToDay->take(5);
        $ListAccounts       = $this->getTrangthaiMomo();

        $UserTopTuan = $this->getTopTuan($LichSuChoiMomo);
        //$UserTopTuan=[];
        [
            $Setting_ChanLe,
            $Setting_TaiXiu,
            $Setting_ChanLe2,
            $Setting_Gap3,
            $Setting_Tong3So,
            $Setting_1Phan3,
        ] = $this->getSetingGame();
        $viewLichSuThang   = view('HomePage.table_lich_su_thang', compact('ListLichSuChoiMomo'))->render();
        $viewUserTopTuan   = view('HomePage.top_tuan', compact('UserTopTuan'))->render();
        $viewTrangthaiMomo = view('HomePage.table_trang_thai_momo', compact('ListAccounts'))->render();
        $viewTaleAccount   = [];
        $types             = Config::get('constant.list_game');
        foreach ($types as $type => $label) {
            if (!view()->exists('HomePage.table_account_'.$type)) {
                continue;
            }
            $viewTaleAccount[$type] = view('HomePage.table_account_'.$type,
                compact('accountMomosGroupTypes', 'accountMomosGroupTypesAllGames'))->render();
        }
        $data=[
            'lich_su_thang'                      => $viewLichSuThang,
            'view_table_account'                 => $viewTaleAccount,
            'view_table_trang_thai_momo'         => $viewTrangthaiMomo,
            'view_top_tuan'                      => $viewUserTopTuan,
            'tiencuoc_'.CONFIG_CHAN_LE           => $Setting_ChanLe['tile'],
            'tiencuoc_'.CONFIG_TAI_XIU           => $Setting_TaiXiu['tile'],
            'tiencuoc_'.CONFIG_CHAN_LE_TAI_XIU_2 => $Setting_ChanLe2['tile'],
            'tiencuoc_'.CONFIG_1_PHAN_3          => $Setting_1Phan3['tile'],
            'tiencuoc_'.CONFIG_GAP_3.'_1'        => $Setting_Gap3['tile1'],
            'tiencuoc_'.CONFIG_GAP_3.'_2'        => $Setting_Gap3['tile2'],
            'tiencuoc_'.CONFIG_GAP_3.'_3'        => $Setting_Gap3['tile3'],
            'tiencuoc_'.CONFIG_TONG_3_SO.'_1'    => $Setting_Tong3So['tile1'],
            'tiencuoc_'.CONFIG_TONG_3_SO.'_2'    => $Setting_Tong3So['tile2'],
            'tiencuoc_'.CONFIG_TONG_3_SO.'_3'    => $Setting_Tong3So['tile3'],
        ];
        // set cache tồn tại trong 30s
        Cache::put('AllData', $data, TIME_CACHE_LOAD_DATA);
        return $data;
    
    }

    public function getPhone($phone)
    {
        $middle_string = "";
        $length        = strlen($phone);
        if ($length < 3) {
            return $length == 1 ? "*" : "*".substr($phone, -1);
        } else {
            $part_size        = floor($length / 3);
            $middle_part_size = $length - ($part_size * 2);
            for ($i = 0; $i < $middle_part_size; $i++) {
                $middle_string .= "*";
            }
            return substr($phone, 0, $part_size).$middle_string.substr($phone, -$part_size);
        }
    }

    /**
     * @param  \App\Models\AccountMomo  $AccountMomo
     *
     * @return array
     */
    private function getSetingGame(): array
    {
        $AccountMomo = new AccountMomo;

        $ChanLe               = new ChanLe;
        $Setting_ChanLe       = $ChanLe->first();
        $Setting_ChanLe->sdt2 = $AccountMomo->GetListAccountID($Setting_ChanLe->sdt);
        $Setting_ChanLe       = $Setting_ChanLe->toArray();

        //Tài xỉu
        $TaiXiu               = new TaiXiu;
        $Setting_TaiXiu       = $TaiXiu->first();
        $Setting_TaiXiu->sdt2 = $AccountMomo->GetListAccountID($Setting_TaiXiu->sdt);
        $Setting_TaiXiu       = $Setting_TaiXiu->toArray();

        //Chẵn lẻ 2
        $ChanLe2               = new ChanLe2;
        $Setting_ChanLe2       = $ChanLe2->first();
        $Setting_ChanLe2->sdt2 = $AccountMomo->GetListAccountID($Setting_ChanLe2->sdt);

        $Setting_ChanLe2 = $Setting_ChanLe2->toArray();

        //Gấp 3
        $Gap3               = new Gap3;
        $Setting_Gap3       = $Gap3->first();
        $Setting_Gap3->sdt2 = $AccountMomo->GetListAccountID($Setting_Gap3->sdt);

        $Setting_Gap3 = $Setting_Gap3->toArray();

        //Tổng 3 Số
        $Tong3So               = new Tong3So;
        $Setting_Tong3So       = $Tong3So->first();
        $Setting_Tong3So->sdt2 = $AccountMomo->GetListAccountID($Setting_Tong3So->sdt);

        $Setting_Tong3So = $Setting_Tong3So->toArray();

        //1 Phần 3
        $X1Phan3              = new X1Phan3;
        $Setting_1Phan3       = $X1Phan3->first();
        $Setting_1Phan3->sdt2 = $AccountMomo->GetListAccountID($Setting_1Phan3->sdt);

        $Setting_1Phan3 = $Setting_1Phan3->toArray();
        return [
            $Setting_ChanLe,
            $Setting_TaiXiu,
            $Setting_ChanLe2,
            $Setting_Gap3,
            $Setting_Tong3So,
            $Setting_1Phan3,
        ];
    }

    /**
     * @param  \App\Models\LichSuChoiMomo  $LichSuChoiMomo
     *
     * @return mixed
     */
    private function getTopTuan(LichSuChoiMomo $LichSuChoiMomo)
    {
        $topTuan= new  TopTuan;
        $UserTopTuan=[];
         $getTopTuan = $topTuan->whereBetween('created_at',
             [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])
             ->orderBy('tongtientuan', 'desc')->limit(5)->get();
         foreach($getTopTuan as $row){
             $UserTopTuan[$this->getPhone($row->sdt)] = $row->tongtientuan;
         }
         return $UserTopTuan;
        // $lichSuChoiMomoTuan = $LichSuChoiMomo->whereBetween('created_at',
        //     [Carbon::today()->startOfWeek(), Carbon::today()->endOfWeek()])
        //     //            ->where('ketqua', 1)
        //     ->where('status', 3)
        //     ->get();

        // $UserTopTuan = $lichSuChoiMomoTuan->map(function($lichSu) {
        //     $phoneConvert = new PhoneNumber();
        //     $lichSu->sdt  = $phoneConvert->convert($lichSu->sdt, true);
        //     $lichSu->sdt  = $this->getPhone($lichSu->sdt);
        //     return $lichSu;
        // })->groupBy('sdt')->map(function($lichSuPhone) {
        //     return $lichSuPhone->sum('tiencuoc');
        // })->sortByDesc(function($tiencuoc) {
        //     return $tiencuoc;
        // })->take(5)->toArray();
        // return $UserTopTuan;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    private function getTrangthaiMomo(): \Illuminate\Support\Collection
    {
        //Trạng thái MOMO

        $ListAccounts = $this->accountMomoRepo->getListAccountMomosWithAccountLevel(false);
        return $ListAccounts->map(function($account) {
            $account['status_class'] = "success";
            $account['status_text']  = "hoạt động";
            return $account;
        });
//        $LichSuBank   = new LichSuBank;
//        $accounts     = collect($this->accountMomoRepo->getListAccountMomos());
//        $LichSuBanks  = $LichSuBank->whereDate('created_at', Carbon::today())->get();
//        $ListAccounts = collect($accounts)->map(function($account) use (
//            $LichSuChoiMomoToDay,
//            $LichSuBanks
//        ) {
//            $GetLichSuChoiMomo  = $LichSuChoiMomoToDay->where('sdt_get', $account['sdt']);
//            $getLichSuBank      = $LichSuBanks->where('sdtbank', $account['sdt']);
//            $responseLichSuBank = $getLichSuBank->pluck('response')->toArray();
//            $countbank          = 0;
//            foreach ($responseLichSuBank as $response) {
//                $j = json_decode($response, true);
//                if (isset($j['status']) && $j['status'] == 200) {
//                    $countbank++;
//                }
//            }
//            $account['sent_money']   = $GetLichSuChoiMomo->sum('tiennhan');
//            $account['status_class'] = "success";
//            $account['status_text']  = "hoạt động";
//            $account['countbank']    = $countbank;
//
//            return $account;
//        })->take(5);
        return $ListAccounts;
    }

}
