<?php

namespace App\Http\Controllers;


use App\Http\Repositories\AccountMomoRepository;
use App\Models\AccountLevelMoney;
use App\Models\AccountMomo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use stdClass;

class AccountLevelMoneyController extends Controller
{

    public function __construct()
    {
    }

    //
    public function index()
    {
        $GetSetting              = new stdClass;
        $accountMomoRepo         = new AccountMomoRepository();
        $accounts                = $accountMomoRepo->getListAccountMomosLevels();
        $accountsMomo            = $accountMomoRepo->getListAccountMomos(true);
        $GetSetting->namepage    = 'Quản lý hạn mức SĐT';
        $GetSetting->title       = 'Quản lý hạn mức SĐT';
        $GetSetting->description = 'Quản lý hạn mức SĐT';
        $GetSetting->description = 'Tạo mới';
        $titleModal              = 'Tạo mới';
        $types                   = Config::get('constant.list_game');
        return view('AdminPage.AccountLevelMoney.index',
            compact('GetSetting', 'titleModal', 'accounts', 'accountsMomo', 'types'));
    }

    public function store()
    {
        $data      = request()->all();
        $paramKeys = ['sdt', 'type', 'min', 'max'];
        if (!$this->validateParameterKeys($paramKeys, $data)) {
            return $this->responseMissingParameters();
        }
        if (!is_numeric($data['min']) || !is_numeric($data['max']) || !is_numeric($data['sdt'])) {
            return $this->responseError($data, "Dữ liệu gửi lên không hợp lệ");
        }
        if ((int)($data['min']) >= (int)$data['max']) {
            return $this->responseError($data, "Giá trị min phải nhỏ hơn giá trị max");
        }
        AccountLevelMoney::create($data);
        Cache::forget('cache_list_account_momos_active');
        Session::flash('message', 'Lưu dữ liệu thành công');
        return $this->responseSuccess();
    }

    public function edit()
    {
        $data      = request()->all();
        $paramKeys = ['id'];
        if (!$this->validateParameterKeys($paramKeys, $data)) {
            return $this->responseMissingParameters();
        }
        $account = AccountLevelMoney::where('id', $data['id'])->first();
        if (is_null($account)) {
            return $this->responseMissingParameters();
        }
        $accountMomoRepo = new AccountMomoRepository();
        $accountsMomo    = $accountMomoRepo->getListAccountMomos();
        $types           = Config::get('constant.list_game');

        $titleModal = "Cập nhật";
        return view('AdminPage.AccountLevelMoney.form_template',
            compact('account', 'titleModal', 'accountsMomo', 'types'));
    }

    public function update()
    {
        $data      = request()->all();
        $paramKeys = ['id', 'sdt', 'type', 'min', 'max'];
        if (!$this->validateParameterKeys($paramKeys, $data)) {
            return $this->responseMissingParameters();
        }
        if (!is_numeric($data['min']) || !is_numeric($data['max']) || !is_numeric($data['sdt'])) {
            return $this->responseError($data, "Dữ liệu gửi lên không hợp lệ");
        }
        if ((int)($data['min']) >= (int)$data['max']) {
            return $this->responseError($data, "Giá trị min phải nhỏ hơn giá trị max");
        }
        $account = AccountLevelMoney::where('id', $data['id'])->update($data);
        if (!$account) {
            return $this->responseMissingParameters();
        }
        $account = AccountLevelMoney::where('id', $data['id'])->first();
        Cache::forget('cache_list_account_momos_active');
        return view('AdminPage.AccountLevelMoney.row', compact('account'));
    }


    public function delete()
    {
        $data      = request()->all();
        $paramKeys = ['id'];
        if (!$this->validateParameterKeys($paramKeys, $data)) {
            return $this->responseMissingParameters();
        }
        $account = AccountLevelMoney::where('id', $data['id'])->update(['status' => STATUS_DE_ACTIVE]);
        if (is_null($account)) {
            return $this->responseMissingParameters();
        }
        Cache::forget('cache_list_account_momos_active');
        return $this->responseSuccess();
    }


}
