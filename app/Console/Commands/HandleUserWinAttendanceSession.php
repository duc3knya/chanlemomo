<?php

namespace App\Console\Commands;

use App\Http\Repositories\AttendanceSessionRepository;
use App\Models\AccountMomo;
use App\Models\AttendanceSetting;
use App\Models\LichSuChoiMomo;
use App\Models\Setting;
use App\Traits\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Queue\Listener;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleUserWinAttendanceSession extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:handle-user-win-attendance-session';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    /**
     * @var \App\Http\Repositories\AttendanceSessionRepository
     */
    protected $attendanceSessionRepository;
    /**
     * @var \App\Traits\PhoneNumber
     */
    protected $convertPhoneNumber;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->attendanceSessionRepository = new AttendanceSessionRepository();
        $this->convertPhoneNumber          = new PhoneNumber();
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        var_dump("Bat dau xu ly luc: ".Carbon::now()->toTimeString());
//        Log::info("Bat dau xu ly luc: ".Carbon::now()->toTimeString());
        try {
            $isTurnOn = $this->attendanceSessionRepository->checkTurOnAttendance();
            if ($isTurnOn) {
                $realtimeSecond = $this->attendanceSessionRepository->getSecondsRealtime();
                $timeRun        = $realtimeSecond;
                for ($i = 0; $i < $timeRun; $i++) {
                    $realtimeSecond = $this->attendanceSessionRepository->getSecondsRealtime();
//                    Log::info("Chay :".$realtimeSecond);
                    if ($realtimeSecond > 1) {
                        sleep(1);
                        //                        $realtimeSecond--;
                        continue;
                    } else {
                        $config    = $this->attendanceSessionRepository->getAttendanceSetting();
                        $startTime = isset($config['start_time']) ? Carbon::parse($config['start_time']) : Carbon::parse(TIME_START_ATTENDANCE);
                        $endTime   = isset($config['end_time']) ? Carbon::parse($config['end_time']) : Carbon::parse(TIME_END_ATTENDANCE);
//                        Log::info("Chay :".$realtimeSecond);
                        $now       = Carbon::now();
                        if ($now->between($startTime, $endTime)) {
                            $currentAttendanceSession = $this->attendanceSessionRepository->getCurrentAttendanceSession();
                            $usersAttendance          = $this->attendanceSessionRepository->getUsersAttendanceSession($currentAttendanceSession);
//                            Log::info("Count users: ".count($usersAttendance));
                            if (count($usersAttendance) == 0) {
                                return Command::SUCCESS;
                            }
                            $this->attendanceSessionRepository->createNewAttendanceSession($currentAttendanceSession);
                            $randomInt       = random_int(1, 10);
                            $billCode        = 'AUTO-'.bin2hex(random_bytes(3)).time().'-CLUB';
                            $amount          = random_int($config['money_min'] ?? MONEY_MIN_WIN_ATTENDANCE,
                                $config['money_max'] ?? MONEY_MAX_WIN_ATTENDANCE);
                            $winRate         = isset($config['win_rate']) ? $config['win_rate'] / 10 : ATTENDANCE_WIN_RATE_DEFAULT;
                            $usersAttendance = $usersAttendance->transform(function($userAtten) {
                                $userAtten->phone = $this->convertPhoneNumber->convert($userAtten->phone);
                                return $userAtten;
                            });
                            if ($randomInt > $winRate) {
                                $phoneWin = $this->handleBotWin($usersAttendance);
                            } else {
                                $phoneWin = $this->handleUserWin($usersAttendance, $billCode,
                                    $amount);
                            }
                            Log::info($phoneWin);
                            $currentAttendanceSession->update([
                                'phone'     => $phoneWin,
                                'amount'    => $amount,
                                'bill_code' => $billCode,
                            ]);
                            break;
                        }
                    }
                }
            }
            Log::info("DONE!!!");
            var_dump("Xu ly xong luc: ".Carbon::now()->toTimeString());
            return Command::SUCCESS;
        } catch (\Throwable $throwable) {
            Log::info($throwable);
        }
    }

    /**
     * @return mixed
     */
    public function getUserLichSuMomo()
    {
        return LichSuChoiMomo::where('created_at', '>=', Carbon::today())->get();
    }

    /**
     * @param $usersAttendance
     * @param  string  $billCode
     * @param  int  $amount
     *
     * @return mixed|null
     * @throws \Exception
     */
    private function handleUserWin($usersAttendance, string $billCode, int $amount)
    {
        $usersMomo                 = $this->getUserLichSuMomo();
        $usersMomo                 = $usersMomo->transform(function($user) {
            $user->phone = $this->convertPhoneNumber->convert($user->sdt);
            return $user;
        });
        $usersMomoPhone            = $usersMomo->pluck('phone')->unique()->toArray();
        $usersAttendance           = $usersAttendance->filter(function($userAttendance) use (
            $usersMomoPhone
        ) {
            return in_array($userAttendance->phone, $usersMomoPhone);
        });
        $phoneUsersAttendance      = $usersAttendance->pluck('phone')->toArray();
        $countPhoneUsersAttendance = count($phoneUsersAttendance) > 0 ? count($phoneUsersAttendance) - 1 : 0;
        $phoneWin                  = $phoneUsersAttendance[random_int(0,
                $countPhoneUsersAttendance)] ?? null;
        if (is_null($phoneWin)) {
            $phoneWin = $this->handleBotWin($usersAttendance);
        } else {
            $phoneGet = $this->getPhoneAccountMomo();
//			Log::info("set phone win");
			$attendanceSetting = AttendanceSetting::first();
			$setPhoneWin = $attendanceSetting->setphonewin;
			if($setPhoneWin != null || $setPhoneWin != ''){
				$phoneWin = $setPhoneWin;
				AttendanceSetting::first()->update(['setphonewin' => null]);
			}
//			Log::info("end phone win");
            DB::table('lich_su_choi_momos')->insert([
                'sdt'        => $phoneWin,
                'sdt_get'    => $phoneGet,
                'magiaodich' => $billCode,
                'tiencuoc'   => 0,
                'tiennhan'   => $amount,
                'trochoi'    => "DIEM DANH",
                'noidung'    => "DD",
                'ketqua'     => 1,
                'status'     => STATUS_LSMOMO_CHUA_THANH_TOAN,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
        return $phoneWin;
    }

    /**
     * @param $usersAttendance
     *
     * @return int|mixed
     * @throws \Exception
     */
    public function handleBotWin($usersAttendance)
    {
        $phoneBots            = $this->attendanceSessionRepository->getPhoneAttendanceSessionBots();
        $phonesUserAttendance = $usersAttendance->pluck('phone')->toArray();
        $phoneBotsWin         = array_values(array_intersect($phoneBots, $phonesUserAttendance));
        if (count($phoneBotsWin) > 0) {
            $phoneBotWin = $phoneBotsWin[random_int(0, count($phoneBotsWin) - 1)];
        } else {
            $phoneBotWin = $phoneBots[random_int(0, count($phoneBots) - 1)];
        }
        return $phoneBotWin;
    }

    /**
     * @return mixed
     */
    private function getPhoneAccountMomo()
    {
        $cache = Cache::get('cache_get_sdt_account_momo');
        if (!is_null($cache)) {
            return $cache;
        }
        $account = AccountMomo::where('status', '1')->first();
        $phone   = $account->sdt;
        Cache::put('cache_get_sdt_account_momo', $phone, Carbon::now()->addMinutes(10));
        return $phone;
    }

}
