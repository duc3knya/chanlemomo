<?php

namespace App\Http\Controllers;

use App\Http\Repositories\AttendanceDateRepository;
use App\Http\Repositories\AttendanceSessionRepository;
use App\Models\AccountLevelMoney;
use App\Models\AttendanceDateSetting;
use App\Models\AttendanceSetting;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Http\Requests\LoginAdminRequest;
use App\Models\AccountMomo;
use Illuminate\Support\Facades\Auth;
use App\Models\LichSuChoiMomo;
use App\Models\LichSuChoiNoHu;
use App\Models\NoHuu;
use Illuminate\Support\Carbon;
use App\Http\Requests\AdminDeteleSdtRequest;
use App\Models\ChanLe;
use App\Models\ChanLe2;
use App\Models\Gap3;
use App\Models\TaiXiu;
use App\Http\Requests\AdminSettingGameRequest;
use App\Http\Requests\AdminSettingGameRequest2;
use App\Models\Tong3So;
use App\Models\X1Phan3;
use App\Http\Requests\AdminSettingGameRequest3;
use App\Models\SettingPhanThuongTop;
use App\Http\Requests\ChangePasswordAdminRequest;
use App\Models\ConfigMessageMomo;
use App\Models\User;
use App\Models\WEB2M;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use ZipArchive;
use Illuminate\Support\Facades\DB;
use App\Models\LichSuBank;
use Illuminate\Support\Facades\Log;


class AdminController extends Controller
{

    public function __construct()
    {
       // $this->server = 'http://serverupdate.shopfb.net';
    }

    //
    public function index(request $request)
    {
        //Setting
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Dashboard';
        
        $LichSuChoiMomo    = new LichSuChoiMomo;
        
         $GetLichSuChoiMomo = $LichSuChoiMomo->where(
             'status', '!=', 5
         )->get();
        
        $tongluotchoi = [
            'chanle'  => 0,
            'taixiu'  => 0,
            'chanle2' => 0,
            'gap3'    => 0,
            'tong3so' => 0,
            '1phan3'  => 0,
            'nohuu'   => 0,
        ];

        $doanhthu = [
            'nohu'             => 0,
            'tongdoanhthu'     => 0,
            'doanhthuhomnay'   => 0,
            'doanhthuthangnay' => 0,
            'doanhthunamnay'   => 0,
        ];

        $thongtin = [
            'tientronghu' => 0,
        ];
        
        //Tính tổng số lượt chơi từng game
        foreach ($GetLichSuChoiMomo as $row) {
            if ($row->trochoi == 'Chẵn lẻ') {
                $tongluotchoi['chanle']++;
            }

            if ($row->trochoi == 'Tài xỉu') {
                $tongluotchoi['taixiu']++;
            }

            if ($row->trochoi == 'Chẵn lẻ Tài Xỉu 2') {
                $tongluotchoi['chanle2']++;
            }

            if ($row->trochoi == 'Gấp 3') {
                $tongluotchoi['gap3']++;
            }

            if ($row->trochoi == 'Tổng 3 số') {
                $tongluotchoi['tong3so']++;
            }

            if ($row->trochoi == '1 phần 3') {
                $tongluotchoi['1phan3']++;
            }
        }

        //Tính lượt chơi nổ hũ + doanh thu
        $LichSuChoiNoHu       = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu    = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->get();
        Log::info("index ad4");
        $tongluotchoi['nohu'] = $GetLichSuChoiNoHu->count();

        $loinhuanx = 0;
        foreach ($GetLichSuChoiNoHu as $row) {
            $loinhuanx = $loinhuanx + ($row->tiencuoc - $row->tienvaohu);
        }

        $NoHuu        = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $CountNoHu = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->where('ketqua', 1)->count();

        $loinhuany = $Setting_NoHu->tienmacdinh * $CountNoHu;

        $doanhthu['nohu'] = $loinhuanx - $loinhuany;

        $NoHuu        = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $LichSuChoiNoHu    = new LichSuChoiNoHu;
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where([
            'status' => 3,
        ])->get();

        $tongtien = $Setting_NoHu->tienmacdinh;

        foreach ($GetLichSuChoiNoHu as $row) {
            $tongtien = $tongtien + $row->tienvaohu;
            $tongtien = $tongtien - $row->tiennhan;
        }

        $thongtin['tientronghu'] = $tongtien;

        //Tính donah thu tổng - ngày - tháng - năm
        $TongDoanhThuGame      = 0;
        $TongDoanhThuGameNgay  = 0;
        $TongDoanhThuGameThang = 0;
        $TongDoanhThuGameNam   = 0;

        $TongDoanhThuNoHu      = $doanhthu['nohu'];
        $TongDoanhThuNoHuNgay  = 0;
        $TongDoanhThuNoHuThang = 0;
        $TongDoanhThuNoHuNam   = 0;

        //Tổng ALL
        foreach ($GetLichSuChoiMomo as $row) {
            $TongDoanhThuGame = $TongDoanhThuGame + $row->tiencuoc;
            $TongDoanhThuGame = $TongDoanhThuGame - $row->tiennhan;
        }

        $doanhthu['tongdoanhthu'] = $TongDoanhThuGame + $TongDoanhThuNoHu;

        //Tổng ngày
        $GetLichSuChoiMomo = $LichSuChoiMomo->where(
            'status', '!=', 5
        )->whereDate('created_at', Carbon::today())->get();

        foreach ($GetLichSuChoiMomo as $row) {
            $TongDoanhThuGameNgay = $TongDoanhThuGameNgay + $row->tiencuoc;
            $TongDoanhThuGameNgay = $TongDoanhThuGameNgay - $row->tiennhan;
        }

        //Tính lượt chơi nổ hũ + doanh thu
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->whereDate('created_at', Carbon::today())->get();

        $loinhuanx = 0;
        foreach ($GetLichSuChoiNoHu as $row) {
            $loinhuanx = $loinhuanx + ($row->tiencuoc - $row->tienvaohu);
        }

        $Setting_NoHu = $NoHuu->first();
        $CountNoHu    = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->where('ketqua', 1)->whereDate('created_at', Carbon::today())->count();

        $loinhuany = $Setting_NoHu->tienmacdinh * $CountNoHu;

        $TongDoanhThuNoHuThang      = $loinhuanx - $loinhuany;
        $doanhthu['doanhthuhomnay'] = $TongDoanhThuGameNgay + $TongDoanhThuNoHuThang;

        //Tổng tháng
        $month             = now()->month;
        $GetLichSuChoiMomo = $LichSuChoiMomo->where(
            'status', '!=', 5
        )->whereMonth('created_at', '=', $month)->get();

        foreach ($GetLichSuChoiMomo as $row) {
            $TongDoanhThuGameThang = $TongDoanhThuGameThang + $row->tiencuoc;
            $TongDoanhThuGameThang = $TongDoanhThuGameThang - $row->tiennhan;
        }

        //Tính lượt chơi nổ hũ + doanh thu
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->whereMonth('created_at', '=', $month)->get();

        $loinhuanx = 0;
        foreach ($GetLichSuChoiNoHu as $row) {
            $loinhuanx = $loinhuanx + ($row->tiencuoc - $row->tienvaohu);
        }

        $Setting_NoHu = $NoHuu->first();
        $CountNoHu    = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->where('ketqua', 1)->whereMonth('created_at', '=', $month)->count();

        $loinhuany = $Setting_NoHu->tienmacdinh * $CountNoHu;

        $TongDoanhThuNoHuThang        = $loinhuanx - $loinhuany;
        $doanhthu['doanhthuthangnay'] = $TongDoanhThuGameThang + $TongDoanhThuNoHuThang;

        //Tổng năm
        $yeah              = now()->year;
        $GetLichSuChoiMomo = $LichSuChoiMomo->where(
            'status', '!=', 5
        )->whereYear('created_at', '=', $yeah)->get();

        foreach ($GetLichSuChoiMomo as $row) {
            $TongDoanhThuGameNam = $TongDoanhThuGameNam + $row->tiencuoc;
            $TongDoanhThuGameNam = $TongDoanhThuGameNam - $row->tiennhan;
        }

        //Tính lượt chơi nổ hũ + doanh thu
        $GetLichSuChoiNoHu = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->whereYear('created_at', '=', $yeah)->get();

        $loinhuanx = 0;
        foreach ($GetLichSuChoiNoHu as $row) {
            $loinhuanx = $loinhuanx + ($row->tiencuoc - $row->tienvaohu);
        }

        $Setting_NoHu = $NoHuu->first();
        $CountNoHu    = $LichSuChoiNoHu->where(
            'status', '!=', 5
        )->where('ketqua', 1)->whereYear('created_at', '=', $yeah)->count();

        $loinhuany = $Setting_NoHu->tienmacdinh * $CountNoHu;

        $TongDoanhThuNoHuNam        = $loinhuanx - $loinhuany;
        $doanhthu['doanhthunamnay'] = $TongDoanhThuGameNam + $TongDoanhThuNoHuNam;

        //View
        return view('AdminPage.home', compact('GetSetting', 'tongluotchoi', 'doanhthu', 'thongtin'));
    }

    public function Login(request $request)
    {
        //Setting
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Trang chủ';

        return view('AdminPage.login', compact('GetSetting'));
    }

    public function LoginAction(LoginAdminRequest $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return redirect()->back()->with('status', 'success')->with('message', 'Đăng nhập thành công');
        } else {
            return redirect()->back()->with('status', 'error')->with('message', 'Sai tài khoản hoặc mật khẩu');
        }
    }

    public function Logout(request $request)
    {
        Auth::logout();
        return redirect()->route('login');
    }

    public function LichSuChoi($slug)
    {
        //Setting
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Lịch sử chơi';

        $trochoi = 'More';

        if ($slug == 'no-hu') {
            $trochoi            = 'Nổ hũ';
            $LichSuChoiNoHu     = new LichSuChoiNoHu;
            $GetLichSuChoiMomos = $LichSuChoiNoHu->where('status', '!=', 5)->orderBy('id', 'DESC')->get();
        } else {
            if ($slug == 'chan-le') {
                $trochoi = 'Chẵn lẻ';
            }

            if ($slug == 'tai-xiu') {
                $trochoi = 'Tài xỉu';
            }

            if ($slug == 'chan-le-2') {
                $trochoi = 'Chẵn lẻ Tài Xỉu 2';
            }

            if ($slug == 'gap-3') {
                $trochoi = 'Gấp 3';
            }

            if ($slug == 'tong-3-so') {
                $trochoi = 'Tổng 3 số';
            }

            if ($slug == '1-phan-3') {
                $trochoi = '1 phần 3';
            }

            $GetSetting->game = $trochoi;

            $LichSuChoiMomo     = new LichSuChoiMomo;
            $GetLichSuChoiMomos = $LichSuChoiMomo->where([
                'trochoi' => $trochoi,
            ])->where('status', '!=', 5)->orderBy('id', 'DESC')->limit(300)->get();
        }

        $GetLichSuChoiMomo = [];
        $dem               = 0;

        foreach ($GetLichSuChoiMomos as $row) {
            $GetLichSuChoiMomo[$dem] = $row;

            if ($row->ketqua == 99) {
                $GetLichSuChoiMomo[$dem]['kq'] = [
                    'class' => 'danger',
                    'text'  => 'Thua',
                ];
            } else {
                $GetLichSuChoiMomo[$dem]['kq'] = [
                    'class' => 'success',
                    'text'  => 'Thắng',
                ];
            }

            if ($row->status == 1) {
                $GetLichSuChoiMomo[$dem]['tt'] = [
                    'class' => 'warning',
                    'text'  => 'Chờ xử lý',
                ];
            } elseif ($row->status == 2) {
                $GetLichSuChoiMomo[$dem]['tt'] = [
                    'class' => 'warning',
                    'text'  => 'Đang xử lý',
                ];
            } elseif ($row->status == 3) {
                $GetLichSuChoiMomo[$dem]['tt'] = [
                    'class' => 'success',
                    'text'  => 'Hoàn tất',
                ];
            } elseif ($row->status == 4) {
                $GetLichSuChoiMomo[$dem]['tt'] = [
                    'class' => 'danger',
                    'text'  => 'Lỗi',
                ];
            }

            $dem++;
        }

        //View
        return view('AdminPage.lichsuchoi', compact('GetSetting', 'GetLichSuChoiMomo'));
    }

    public function EditSetting(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cài đặt';

        //View
        return view('AdminPage.setting', compact('GetSetting'));
    }

    public function EditSettingAction(request $request)
    {
        $Setting    = new Setting;
        $GetSetting = $Setting->first();

        $GetSetting->update($request->all());
        Cache::forget('cache_website_setting');
        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function QuanlySDT(request $request)
    {
        $WEB2M = new WEB2M;

        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Quản lý số điện thoại';
        $GetSetting->desc     = 'Quản lý số điện thoại';

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['amount'] = $WEB2M->getMoney_momo($row->token,$row->webapi);
            $GetAccountMomo[$dem]['name']   = $WEB2M->getName_momo($row->sdt, $row->token,$row->webapi);

            //Lấy số lần bank
            $LichSuBank    = new LichSuBank;
            $countbank     = 0;
            $getLichSuBank = $LichSuBank->whereDate('created_at', Carbon::today())->where([
                'sdtbank' => $row->sdt,
            ])->get();

            foreach ($getLichSuBank as $r) {
                $j = json_decode($r->response, true);

                if (isset($j['status']) && $j['status'] == 200) {
                    $countbank++;
                }
            }

            //echo $countbank; exit;
            $GetAccountMomo[$dem]['countbank'] = $countbank;

            if ($row->status == 1) {
                $GetAccountMomo[$dem]['status_text']  = 'Đang hoạt động';
                $GetAccountMomo[$dem]['status_class'] = 'success';
            } else {
                $GetAccountMomo[$dem]['status_text']  = 'Bảo trì';
                $GetAccountMomo[$dem]['status_class'] = 'warning';
            }

            $dem++;
        }

        // //View
        return view('AdminPage.quanlysdt', compact('GetSetting', 'GetAccountMomo'));
    }

    public function DeteleSDT($id)
    {
        $AccountMomo = new AccountMomo;
        $account = $AccountMomo->where([
            'id' => $id,
        ])->first();
        if (is_null($account)){
            return redirect()->back()->with('status', 'success')->with('message', 'Số điện thoại không tồn tại');
        }
        $phone = $account->sdt;
        $account->delete();
        AccountLevelMoney::where('sdt',$phone)->delete();
        return redirect()->back()->with('status', 'success')->with('message', 'Xóa dữ liệu thành công');
    }

    public function EditSDT($id)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Chỉnh sửa số điện thoại';

        $AccountMomo    = new AccountMomo;
        $GetAccountMomo = $AccountMomo->where([
            'id' => $id,
        ])->first();


        return view('AdminPage.editsdt', compact('GetSetting', 'GetAccountMomo'));
    }

    public function EditSDTAction(request $request)
    {
        $AccountMomo    = new AccountMomo;
        $GetAccountMomo = $AccountMomo->where([
            'id' => $request->id,
        ]);
        $GetAccountMomo->update(request()->except(['_token']));

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function AddSDT(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Thêm số điện thoại';

        return view('AdminPage.addsdt', compact('GetSetting'));
    }

    public function AddSDTAction(request $request)
    {
        $AccountMomo = new AccountMomo;
        $AccountMomo->fill($request->all());
        $AccountMomo->save();
        AccountLevelMoney::create([
            'sdt' => $request->all()['sdt'],
            'type' => CONFIG_ALL_GAME,
            'min' => 10000,
            'max' => 500000,
        ]);
        return redirect()->back()->with('status', 'success')->with('message', 'Thêm dữ liệu thành công');
    }

    public function SettingChanLe(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình chẵn lẻ';
        $GetSetting->action   = 'admin_setting_chanle_action';

        $ChanLe               = new ChanLe;
        $Setting_ChanLe       = $ChanLe->first();
        $Setting_ChanLe->sdt2 = explode(',', $Setting_ChanLe->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_ChanLe->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_ChanLe;
        return view('AdminPage.setting_game', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingChanLeAction(AdminSettingGameRequest $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $ChanLe      = new ChanLe;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $ChanLe->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingTaiXiu(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình tài xỉu';
        $GetSetting->action   = 'admin_setting_taixiu_action';

        $TaiXiu               = new TaiXiu;
        $Setting_TaiXiu       = $TaiXiu->first();
        $Setting_TaiXiu->sdt2 = explode(',', $Setting_TaiXiu->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_TaiXiu->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_TaiXiu;
        return view('AdminPage.setting_game', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingTaiXiuAction(AdminSettingGameRequest $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $ChanLe      = new TaiXiu;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $ChanLe->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingChanLe2(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình chẵn lẻ 2';
        $GetSetting->action   = 'admin_setting_chanle_action2';

        $ChanLe2               = new ChanLe2;
        $Setting_ChanLe2       = $ChanLe2->first();
        $Setting_ChanLe2->sdt2 = explode(',', $Setting_ChanLe2->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_ChanLe2->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_ChanLe2;
        return view('AdminPage.setting_game', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingChanLeAction2(AdminSettingGameRequest $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $ChanLe2     = new ChanLe2;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $ChanLe2->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingGap3(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình gấp 3';
        $GetSetting->action   = 'admin_setting_gap3_action';

        $Gap3               = new Gap3;
        $Setting_Gap3       = $Gap3->first();
        $Setting_Gap3->sdt2 = explode(',', $Setting_Gap3->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_Gap3->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_Gap3;
        return view('AdminPage.setting_game2', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingGap3Action(AdminSettingGameRequest2 $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $Gap3        = new Gap3;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $Gap3->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingTong3So(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình tổng 3 số';
        $GetSetting->action   = 'admin_setting_tong3so_action';

        $Tong3So               = new Tong3So;
        $Setting_Tong3So       = $Tong3So->first();
        $Setting_Tong3So->sdt2 = explode(',', $Setting_Tong3So->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_Tong3So->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_Tong3So;
        return view('AdminPage.setting_game2', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingTong3SoAction(AdminSettingGameRequest2 $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $Tong3So     = new Tong3So;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $Tong3So->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function Setting1Phan3(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình 1 phần 3';
        $GetSetting->action   = 'admin_setting_1phan3_action';

        $X1Phan3              = new X1Phan3;
        $Setting_1Phan3       = $X1Phan3->first();
        $Setting_1Phan3->sdt2 = explode(',', $Setting_1Phan3->sdt);

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem]           = $row;
            $GetAccountMomo[$dem]['active'] = 0;

            foreach ($Setting_1Phan3->sdt2 as $res) {
                if ($row->id == $res) {
                    $GetAccountMomo[$dem]['active'] = 1;
                }
            }

            $dem++;
        }

        $Setting_Game = $Setting_1Phan3;
        return view('AdminPage.setting_game', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function Setting1Phan3Action(AdminSettingGameRequest $request)
    {
        $request->sdt2 = $request->sdt;
        $request->sdt  = '';
        foreach ($request->sdt2 as $row) {
            $request->sdt = $request->sdt.$row.',';
        }
        $request->sdt = substr($request->sdt, 0, strlen($request->sdt) - 1);

        $X1Phan3     = new X1Phan3;
        $data        = $request->except(['_token']);
        $data['sdt'] = $request->sdt;
        $X1Phan3->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingNoHu(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình nổ hũ';
        $GetSetting->action   = 'admin_setting_nohu_action';

        $NoHuu        = new NoHuu;
        $Setting_NoHu = $NoHuu->first();

        $AccountMomo     = new AccountMomo;
        $GetAccountMomos = $AccountMomo->where([
            'status' => 1,
        ])->get();

        $GetAccountMomo = [];
        $dem            = 0;

        foreach ($GetAccountMomos as $row) {
            $GetAccountMomo[$dem] = $row;
            $dem++;
        }

        $Setting_Game = $Setting_NoHu;
        return view('AdminPage.setting_game3', compact('GetSetting', 'Setting_Game', 'GetAccountMomo'));
    }

    public function SettingNoHuAction(AdminSettingGameRequest3 $request)
    {
        $NoHuu = new NoHuu;
        $data  = $request->except(['_token']);
        $NoHuu->where([
            'id' => $request->id,
        ])->update($data);

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingThuongTuan(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Thiết lập phần thưởng tuần';

        $SettingThuongTuan    = new SettingPhanThuongTop;
        $GetSettingThuongTuan = $SettingThuongTuan->get();

        return view('AdminPage.setting_thuongtuan', compact('GetSetting', 'GetSettingThuongTuan'));
    }

    public function SettingThuongTuanAction(request $request)
    {
        $SettingPhanThuongTop = new SettingPhanThuongTop;

        $x             = $SettingPhanThuongTop->where([
            'top' => 1,
        ])->first();
        $x->phanthuong = $request->top_1;
        $x->save();

        $x             = $SettingPhanThuongTop->where([
            'top' => 2,
        ])->first();
        $x->phanthuong = $request->top_2;
        $x->save();

        $x             = $SettingPhanThuongTop->where([
            'top' => 3,
        ])->first();
        $x->phanthuong = $request->top_3;
        $x->save();

        $x             = $SettingPhanThuongTop->where([
            'top' => 4,
        ])->first();
        $x->phanthuong = $request->top_4;
        $x->save();

        $x             = $SettingPhanThuongTop->where([
            'top' => 5,
        ])->first();
        $x->phanthuong = $request->top_5;
        $x->save();

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function SettingAttendance()
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình điểm danh nhận quà';
        $GetSetting->action   = 'admin_setting_diemdanh_action';
        $settingDiemdanh      = AttendanceSetting::first();
        $configTimeEach       = Config::get('attendance_session.time_each');
        if (is_null($settingDiemdanh)) {
            $settingDiemdanh = AttendanceSetting::create([
                'win_rate'   => 10,
                'start_time' => TIME_START_ATTENDANCE,
                'end_time'   => TIME_END_ATTENDANCE,
                'money_min'  => MONEY_MIN_WIN_ATTENDANCE,
                'money_max'  => MONEY_MAX_WIN_ATTENDANCE,
                'time_each'  => TIME_EACH_ATTENDANCE_SESSION,
            ]);
        }
        return view('AdminPage.setting_diemdanh', compact('GetSetting', 'settingDiemdanh', 'configTimeEach'));
    }

    public function SettingAttendanceAction()
    {
        $settingDiemdanh = AttendanceSetting::first();
        if (is_null($settingDiemdanh)) {
            AttendanceSetting::create(\request()->all());
        } else {
            AttendanceSetting::first()->update(\request()->all());
        }
        Cache::forget('cache_attendance_setting');
        $attendanceRepo = new AttendanceSessionRepository();
        $attendanceRepo->forgetCacheDatAttendanceSession();
        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công1');
    }

    public function SettingAttendanceDate()
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cấu hình điểm danh ngày';
        $GetSetting->action   = 'admin_setting_diemdanh_ngay_action';
        $attendanceRepo       = new AttendanceDateRepository();
        $settings             = $attendanceRepo->getMocchoi();
        return view('AdminPage.setting_diemdanh_ngay', compact('GetSetting', 'settings'));
    }

    public function SettingAttendanceDateAdd()
    {
        $data = \request()->all();
        if (!isset($data['mocchoi']) || !isset($data['mocchoi'])) {
            return ['status' => 2, 'message' => 'Thiếu dữ liệu gửi lên'];
        }
        AttendanceDateSetting::create($data);
        if (isset($data['finish']) && $data['finish'] = 1) {
            return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công2');
        }
        return 1;
    }

    public function SettingAttendanceDateDelete()
    {
        $settingDiemdanh = AttendanceDateSetting::find(request()->setting_id);
        if (!is_null($settingDiemdanh)) {
            $settingDiemdanh->delete();
        }
        return request()->setting_id;
    }
    public function SettingAttendanceDateUpdate()
    {
        $settingDiemdanh = AttendanceDateSetting::find(request()->setting_id);
        if (!is_null($settingDiemdanh)) {
            $settingDiemdanh->update(\request()->all());
        }
        return request()->setting_id;
    }

    public function DoiMatKhau(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Đổi mật khẩu';

        return view('AdminPage.doimatkhau', compact('GetSetting'));
    }

    public function DoiMatKhauAction(ChangePasswordAdminRequest $request)
    {
        //return redirect()->back()->with('status', 'error')->with('message', 'Mật khẩu hiện tại không khớp');
        if (Hash::check($request->old_password, Auth::user()->password)) {
            $user            = new User;
            $userx           = $user->find(Auth::user()->id);
            $userx->password = Hash::make($request->password);
            $userx->save();

            Auth::logout();
            return redirect()->route('login');
        } else {
            return redirect()->back()->with('status', 'error')->with('message', 'Mật khẩu hiện tại không khớp');
        }
    }

    public function Update(request $request)
    {
        /**
         * Thông số
         */
         return;
        $server = $this->server; //server
        $file   = 'source.zip'; //file source
        $sql    = 'sql.txt'; //file sql update
        $update = 'update_'.Str::random(10).'.zip'; //file zip update

        /**
         * Thông tin database
         */
        $databases = [
            'host'      => env('DB_HOST'),
            'port'      => env('DB_PORT'),
            'username'  => env('DB_USERNAME'),
            'password'  => env('DB_PASSWORD'),
            'databases' => env('DB_DATABASE'),
        ];

        /**
         * Download update
         */
        file_put_contents($update, file_get_contents($server.'/'.$file));

        /**
         * Unzip
         */
        $path = pathinfo(realpath($update), PATHINFO_DIRNAME);
        $path = str_replace('public', '', $path);

        $zip = new ZipArchive;
        $res = $zip->open($update);

        if ($res == true) {
            $zip->extractTo($path);
            $zip->close();

            /**
             * Xóa file update
             */
            unlink($update);

            /**
             * Cập nhật sql
             */
            $sqls = file_get_contents($server.'/sql.txt');
            $x    = explode("------------------------------------------------------", $sqls);

            foreach ($x as $row) {
                if (!empty($row)) {
                    DB::statement($row);
                }
            }
        }

        return redirect('/')->with('status', 'success')->with('message', 'Cập nhật thành công');
    }

    public function UpdateView()
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cập nhật';

        $server = $this->server;

        $myinfo     = file_get_contents('../info.txt');
        $serverinfo = file_get_contents($server.'/info.txt');

        $emyinfo     = explode("\n", $myinfo);
        $eserverinfo = explode("\n", $serverinfo);

        return view('AdminPage.update', compact('GetSetting', 'emyinfo', 'eserverinfo'));
    }

    public function ConfigMessage(request $request)
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Lời nhắn trả thưởng';

        $ConfigMessageMomo    = new ConfigMessageMomo;
        $GetConfigMessageMomo = (array)$ConfigMessageMomo->get()->toArray();

        return view('AdminPage.configmessage', compact('GetSetting', 'GetConfigMessageMomo'));
    }

    public function ConfigMessageAction(request $request)
    {
        $ConfigMessageMomo = new ConfigMessageMomo;

        $x          = $ConfigMessageMomo->where([
            'type' => 'tra-thuong',
        ])->first();
        $x->message = $request->trathuong;
        $x->save();

        $x          = $ConfigMessageMomo->where([
            'type' => 'thuong-top-tuan',
        ])->first();
        $x->message = $request->thuongtoptuan;
        $x->save();

        $x          = $ConfigMessageMomo->where([
            'type' => 'no-huu',
        ])->first();
        $x->message = $request->nohuu;
        $x->save();

        return redirect()->back()->with('status', 'success')->with('message', 'Lưu dữ liệu thành công');
    }

    public function LichSuBankView()
    {
        $Setting              = new Setting;
        $GetSetting           = $Setting->first();
        $GetSetting->namepage = 'Cập nhật';

        $LichSuBank    = new LichSuBank;
        $getLichSuBank = $LichSuBank->orderBy('id', 'desc')->limit(300)->get();

        return view('AdminPage.lichsubank', compact('GetSetting', 'getLichSuBank'));
    }

    public function SetStatusSDT(request $request)
    {
        $LichSuChoiMomo            = new LichSuChoiMomo;
        $getLichSuChoiMomo         = $LichSuChoiMomo->find($request->id);
        $getLichSuChoiMomo->status = $request->status;
        $getLichSuChoiMomo->save();

        return redirect()->back()->with('status', 'success')->with('message', 'Thay đổi trạng thái thành công');
    }

}
