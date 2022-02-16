<?php

namespace Database\Seeders;

use Buihuycuong\Vnfaker\VNFaker;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AttendanceSessionBotSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $dataInsert = [];
        for ($i = 0; $i <= 1000; $i++) {
            $dataInsert[] = [
                'phone'      => \vnfaker()->mobilePhone(10),
                'status'     => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ];
        }
        DB::table('attendance_session_bots')->insert($dataInsert);
    }

}
