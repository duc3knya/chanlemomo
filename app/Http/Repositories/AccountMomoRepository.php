<?php
/**
 *File name : AccountMomoRepository.php / Date: 1/4/2022 - 8:55 PM
 *Code Owner: Thanhnt/ Email: Thanhnt@omt.com.vn/ Phone: 0384428234
 */

namespace App\Http\Repositories;

use App\Models\AccountLevelMoney;
use App\Models\AccountMomo;
use App\Models\LichSuBank;
use App\Models\LichSuChoiMomo;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class AccountMomoRepository
{

    public function getListAccountMomosLevels()
    {
        $cache = Cache::get('cache_list_account_momos_active');
        $cache = null;
        if (!is_null($cache)) {
            return $cache;
        }
        $listAccountMomos = $this->getListAccountMomos(true, [STATUS_ACTIVE, STATUS_MAINTENANCE]);
        $levelAccounts    = AccountLevelMoney::where('status', STATUS_ACTIVE)->get()->map(function($account) use (
            $listAccountMomos
        ) {
            $accountMomo   = collect($listAccountMomos)->where('sdt', $account->sdt)->first();
            $account->game = $account->getGameAttribute();
            if (!is_null($accountMomo)) {
                if ($accountMomo['status'] == STATUS_MAINTENANCE) {
                    $account->text_status  = "Bảo trì";
                    $account->class_status = "warning";
                } else {
                    $account->text_status  = "Hoạt động";
                    $account->class_status = "success";
                }
            } else {
                $account->notExist = "true";
            }
            return $account;
        })->filter(function($account) {
            return !isset($account->notExist);
        })->toArray();
        Cache::put('cache_list_account_momos_active', $levelAccounts, Carbon::now()->addMinutes(60));
        return $levelAccounts;
    }

    public function getListAccountMomos($forCreate = false, $status = [STATUS_ACTIVE])
    {
        $phones = [];
        if (!$forCreate) {
            $accountListMomoLevel = $this->getListAccountMomosLevels();
            $phones               = collect($accountListMomoLevel)->pluck('sdt')->toArray();
        }
        $query = AccountMomo::whereIn('status', $status);
        $query = !$forCreate ? $query->whereIn('sdt', $phones)->limit(5) : $query;
        return $query->get()->unique('sdt')->toArray();
    }

    public function getListAccountMomosWithAccountLevel($groupByType = true)
    {
        $accounts          = collect($this->getListAccountMomosLevels());
        $phones            = $accounts->pluck('sdt')->unique()->toArray();
        $LichSuBank        = new LichSuBank;
        $LichSuBanks       = $LichSuBank->whereDate('created_at', \Illuminate\Support\Carbon::today())->get();
        $sumTienCuocPhones = [];
        foreach ($phones as $index => $phone) {
            $sumTienCuocPhones[] = [
                'phone' => $phone,
                'sum'   => DB::table('lich_su_choi_momos')
                    ->whereDate('created_at', Carbon::today())
                    ->where('ketqua', 1)
                    ->where('sdt_get', $phone)
                    ->sum('tiennhan'),

            ];
        }
        $accountMomos      = AccountMomo::whereIn('sdt', $phones)
            ->where('status', STATUS_ACTIVE)
            ->get();
        $phonesAccountMomo = $accountMomos->pluck('sdt')->toArray();
        $accounts          = $accounts->map(function($account) use ($sumTienCuocPhones, $LichSuBanks, $accountMomos) {
            $sumTienCuocPhone       = collect($sumTienCuocPhones)->where('phone', $account['sdt'])->first();
            $accountMomo            = $accountMomos->where('sdt', $account['sdt'])->first();
            $account['sumTienCuoc'] = is_null($sumTienCuocPhone) ? 0 : $sumTienCuocPhone['sum'];
            $account['gioihan']     = is_null($accountMomo) ? 0 : $accountMomo['gioihan'];
            $getLichSuBank          = $LichSuBanks->where('sdtbank', $account['sdt']);
            $countbank              = 0;
            $responseLichSuBank     = $getLichSuBank->pluck('response')->toArray();
            foreach ($responseLichSuBank as $response) {
                $j = json_decode($response, true);
                if (isset($j['status']) && $j['status'] == 200) {
                    $countbank++;
                }
            }
            $account['countbank']       = $countbank;
            $account['color_min']       = $account['min'] > CONFIG_COMPARE_TIEN_CUOC_MIN ? "blue" : "";
            $account['color_max']       = $account['max'] > CONFIG_COMPARE_TIEN_CUOC_MIN ? "blue" : "";
            $account['color_tiencuoc']  = $account['sumTienCuoc'] > CONFIG_MAX_SUM_TIEN_CUOC ? "red" : "green";
            $account['color_countbank'] = $countbank > CONFIG_MAX_COUNT_BANK ? "red" : "green";
            return $account;
        })->filter(function($account) use ($phonesAccountMomo) {
            return in_array($account['sdt'], $phonesAccountMomo);
        })->take(5)->sortBy('min');
        return $groupByType ? $accounts->groupBy('type')->map(function($accountList) {
            return $accountList->unique('sdt');
        }) : $accounts;
    }

}