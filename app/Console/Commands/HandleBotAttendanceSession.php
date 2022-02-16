<?php

namespace App\Console\Commands;

use App\Http\Repositories\AttendanceSessionRepository;
use App\Models\AttendanceSession;
use App\Models\AttendanceSetting;
use App\Models\LichSuChoiMomo;
use App\Models\Setting;
use App\Models\UserAttendanceSession;
use App\Traits\PhoneNumber;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HandleBotAttendanceSession extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:handle-bot-attendance-session';

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
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->attendanceSessionRepository = new AttendanceSessionRepository();
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
        $isTurnOn = $this->attendanceSessionRepository->checkTurOnAttendance();
        if ($isTurnOn) {
            $config    = $this->attendanceSessionRepository->getAttendanceSetting();
            $startTime = isset($config['start_time']) ? Carbon::parse($config['start_time']) : Carbon::parse(TIME_START_ATTENDANCE);
            $endTime   = isset($config['end_time']) ? Carbon::parse($config['end_time']) : Carbon::parse(TIME_END_ATTENDANCE);
            $timeEach  = $config['time_each'];
            $now       = Carbon::now();
            if ($now->between($startTime, $endTime)) {
                try {
                    $attendanceSetting        = $this->attendanceSessionRepository->getAttendanceSetting();
                    $attendanceSessionCurrent = AttendanceSession::where('date', Carbon::today()->toDateString())
                        ->orderBy('created_at', "DESC")
                        //                    ->where('status', STATUS_ACTIVE)
                        ->first();
                    $usersAttendance          = $this->attendanceSessionRepository->getUsersAttendanceSession($attendanceSessionCurrent);
                    $phoneUserAttendance      = $usersAttendance->pluck('phone')->toArray();
                    $botRate                  = $attendanceSetting['bot_rate'] ?? 10;
                    $bots                     = $this->attendanceSessionRepository->getRandomBotsAttendance($botRate,
                        $phoneUserAttendance);
                    $randomNumberTakeBot      = random_int(10, 40);
                    $phoneBots                = collect($bots)
                        ->take(round(($randomNumberTakeBot / 100) * count($bots)))
                        ->pluck("phone")
                        ->toArray();
                    $countBot                 = count(collect($bots));
                    $botHandled               = [];
                    sleep(3);
                    $realtimeSecond = $this->attendanceSessionRepository->getSecondsRealtime();
                    $timeRun        = $realtimeSecond;
                    for ($i = 0; $i <= $timeRun; $i++) {
                        $realtimeSecond = $this->attendanceSessionRepository->getSecondsRealtime();
                        if (count($botHandled) == count($bots) || $realtimeSecond < 1) {
                            return Command::SUCCESS;
                        }
                        if ($countBot < 50) {
                            $numberBotInsert = random_int(0, 3);
                        } else {
                            $numberBotInsert = random_int(0, 5);
                        }
                        Log::warning("BOT INSERT: ".$numberBotInsert);
                        $botsHandling = collect($phoneBots)->take($numberBotInsert)->toArray();
                        foreach ($botsHandling as $index => $phoneBot) {
                            DB::table('users_attendance_session')->insert([
                                'phone'      => $phoneBot,
                                'session_id' => $attendanceSessionCurrent->id,
                                'created_at' => Carbon::now(),
                                'updated_at' => Carbon::now(),
                            ]);
                            unset($phoneBots[array_search($phoneBot, $phoneBots)]);
                        }
                        $botHandled        = array_merge($botHandled, $botsHandling);
                        $sleepWithTimeEach = $this->getSleepSecondByTimeEach($timeEach);
                        $maxSleep          = $realtimeSecond < $sleepWithTimeEach[1] ? $realtimeSecond - 1 : $sleepWithTimeEach[1];
                        $minSleep          = $maxSleep < $sleepWithTimeEach[0] ? 1 : $sleepWithTimeEach[0];
                        var_dump($minSleep, $maxSleep, $realtimeSecond);
                        $sleepInt          = random_int($minSleep, $maxSleep);
                        var_dump("sleep:".$sleepInt);
                        sleep($sleepInt);
                        //                        } else {
                        //                            sleep(random_int(1, 3));
                        //                        }
                    }
                    var_dump("Xu ly xong luc: ".Carbon::now()->toTimeString());
                    return Command::SUCCESS;
                } catch (\Throwable $throwable) {
                    Log::info($throwable);
                }
            }
        }
    }

    private function getSleepSecondByTimeEach($timeEach)
    {
        switch ($timeEach) {
            case 60:
            default;
                return [2, 5];
            case 180:
                return [5, 10];
            case 300:
                return [10, 30];
            case 600:
                return [15, 75];
            case 900:
                return [15, 90];
            case 1200:
                return [15, 120];
            case 1800:
                return [60, 180];
            case 3600:
                return [60, 360];
            case 21600:
                return [120, 600];
            case 86400:
                return [600, 3600];
        }
    }

}
