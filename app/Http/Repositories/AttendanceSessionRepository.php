<?php
/**
 *File name : AttendanceSessionRepository.php / Date: 10/26/2021 - 9:39 PM

 */

namespace App\Http\Repositories;

use App\Models\AttendanceSession;
use App\Models\AttendanceSetting;
use App\Models\Setting;
use App\Models\UserAttendanceSession;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class AttendanceSessionRepository extends Repository
{

    public function __construct()
    {
    }

    public function getSecondsRealtime($updateCache = false)
    {
        $now       = Carbon::now();
        $hour      = $now->hour;
        $setting   = $this->getAttendanceSetting();
        $timeEach  = (int)$setting['time_each'];
        $timeStart = $this->getTimeStartByConfigTimeEach($timeEach, $hour, $now);
        //        $minute    = (int)floor($now->minute / 10) * 3;
        //        $timeStart = Carbon::parse($hour.":".$minute);
        //        $abc = $now->minute - $now->minute%3;
        //        dd($hour.":".$abc, $hour.":".(int)floor($now->minute / 10) * 10);

        $realTimeSeconds = $timeEach - (int)($now->timestamp - $timeStart->timestamp);
        if ($realTimeSeconds <= 1 || $updateCache) {
            $this->forgetCacheDatAttendanceSession();
        }
        return $realTimeSeconds;
    }

    private function getTimeStartByConfigTimeEach($timeEach, $hour, Carbon $now)
    {
        switch ($timeEach) {
            case 60:
            default;
                return Carbon::parse($hour.":".$now->minute);
            case 180:
                $minute = $now->minute - $now->minute % 3;
                return Carbon::parse($hour.":".$minute);
            case 300:
                $minute = $now->minute - $now->minute % 5;
                return Carbon::parse($hour.":".$minute);
            case 600:
                $minute = (int)floor($now->minute / 10) * 10;
                return Carbon::parse($hour.":".$minute);
            case 900:
                $minute = $now->minute - $now->minute % 15;
                return Carbon::parse($hour.":".$minute);
            case 1200:
                $minute = $now->minute - $now->minute % 20;
                return Carbon::parse($hour.":".$minute);
            case 1800:
                $minute = $now->minute - $now->minute % 30;
                return Carbon::parse($hour.":".$minute);
            case 3600:
                return $now->startOfHour();
            case 21600:
                $hour = $hour - $hour % 6;
                return Carbon::parse($hour.":00");
            case 86400:
                return Carbon::today();
        }
    }

    public function getDataAttendanceSession()
    {
        $cache = Cache::get('cache_data_attendance_session');
        $cache = null;
        if (!is_null($cache)) {
            return $cache;
        }
        return $this->updateCacheDataAttendanceSession();
    }

    public function getCurrentAttendanceSession()
    {
        return $this->getDataAttendanceSession()['current'];
    }

    public function getTotalAmountAttendanceSession()
    {
        $cache = Cache::get('cache_total_amount_attendance_session');
        if (!is_null($cache)) {
            return $cache;
        }
        $totalAmount = DB::table('attendance_session')->sum('amount');
        Cache::put('cache_total_amount_attendance_session', $totalAmount,
            Carbon::now()->addSeconds($this->getSecondsRealtime()));
        return $totalAmount;
    }

    public function getUsersAttendanceSession($attendanceSessionCurrent = null)
    {
        $attendanceSessionCurrent = !is_null($attendanceSessionCurrent) ? $attendanceSessionCurrent : $this->getDataAttendanceSession()['current'];
        return $attendanceSessionCurrent->usersAttendanceSession()->get();
    }


    public function insertUsersAttendanceSession($data)
    {
        $attendanceSessionCurrent = $this->getDataAttendanceSession()['current'];
        return UserAttendanceSession::create([
            'session_id' => $attendanceSessionCurrent->id,
            'phone'      => $data['phone'],
        ]);
    }

    public function queryUsersAttendanceByPhone($phone)
    {
        $attendanceSessionCurrent = $this->getDataAttendanceSession()['current'];
        return UserAttendanceSession::where('phone', $phone)->where('session_id', $attendanceSessionCurrent->id)->get();
    }

    public function createNewAttendanceSession($currentAttendanceSession)
    {
        $currentAttendanceSession->update(['status' => STATUS_DE_ACTIVE]);
        $attendanceSession = AttendanceSession::create([
            'date'   => Carbon::today()->toDateString(),
            'status' => STATUS_ACTIVE,
        ]);
        $this->forgetCacheDatAttendanceSession();
        return $attendanceSession;
    }

    public function getPhoneAttendanceSessionBots()
    {
        $cache = Cache::get('cache_phone_attendance_session_bots');
        if (!is_null($cache)) {
            return $cache;
        }
        $phones = collect(DB::table('attendance_session_bots')->select('phone')->get());
        $phones = $phones->pluck('phone')->toArray();
        Cache::put('cache_phone_attendance_session_bots', $phones, Carbon::now()->addDay());
        return $phones;
    }

    public function getRandomBotsAttendance($botRate = 10, $phoneUserAttendance = [])
    {
        $totalBot = count(DB::table('attendance_session_bots')->get());
        return DB::table('attendance_session_bots')
            ->whereNotIn('phone', $phoneUserAttendance)
            ->orderBy(DB::raw('RAND()'))
            ->take(round(($botRate / 100) * $totalBot))
            ->get();
    }

    public function checkTurOnAttendance()
    {
        $setting = $this->getSettingWebsite();
        if (isset($setting['baotri']) && $setting['baotri'] == 1) {
            return false;
        }
        if (!isset($setting['on_diemdanh'])) {
            return true;
        }
        return $setting['on_diemdanh'] == TURN_ON_SETTING;
    }

    public function getSettingWebsite()
    {
        $cache = Cache::get('cache_website_setting');
        $cache = null;
        if (!is_null($cache)) {
            return $cache;
        }
        $setting = Setting::first()->toArray();
        Cache::put('cache_website_setting', $setting, Carbon::now()->addDay());
        return $setting;
    }

    /**
     * @return array
     */
    public function updateCacheDataAttendanceSession()
    {
        $records = AttendanceSession::where('date', Carbon::today()->toDateString())
            ->orderBy('created_at', 'DESC')
            ->with(['usersAttendanceSession'])
            ->get();
        if (count($records) == 0) {
            return [
                'current'          => AttendanceSession::create(['date' => Carbon::today()->toDateString()]),
                'phone_win_latest' => "*",
                'sessions_past'    => collect(),
            ];
        }
        $current      = $records->where('status', STATUS_ACTIVE)->last();
        $current      = is_null($current) ? $records->last() : $current;
        $sessionsPast = $records->except($current->id)->take(5);
        $result       = [
            'current'          => $current,
            'phone_win_latest' => count($sessionsPast) > 0 ? $sessionsPast->first()->getPhone() : "*",
            'sessions_past'    => count($sessionsPast) > 0 ? $sessionsPast : collect(),
        ];
        Cache::put('cache_data_attendance_session', $result, Carbon::now()->addSeconds($this->getSecondsRealtime()));
        return $result;
    }

    public function forgetCacheDatAttendanceSession()
    {
        Cache::forget('cache_data_attendance_session');
        Cache::forget('cache_total_amount_attendance_session');
    }

    public function getAttendanceSetting()
    {
        $cache = Cache::get('cache_attendance_setting');
        if (!is_null($cache)) {
            return $cache;
        }
        $attendance = AttendanceSetting::first();
        $config     = !is_null($attendance) ? AttendanceSetting::first()->toArray() : AttendanceSetting::create([
            'win_rate'   => 10,
            'start_time' => TIME_START_ATTENDANCE,
            'end_time'   => TIME_END_ATTENDANCE,
            'money_min'  => MONEY_MIN_WIN_ATTENDANCE,
            'money_max'  => MONEY_MAX_WIN_ATTENDANCE,
            'time_each'  => TIME_EACH_ATTENDANCE_SESSION,
        ])->toArray();
        Cache::put('cache_attendance_setting', $config, Carbon::now()->addDay());
        return $config;
    }


}