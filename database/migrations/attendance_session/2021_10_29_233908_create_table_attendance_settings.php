<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableAttendanceSettings extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('attendance_settings')) {
            Schema::create('attendance_settings', function(Blueprint $table) {
                $table->id();
                $table->integer('win_rate')->comment("Tỉ lệ người chơi thắng")->default(10);
                $table->time('start_time');
                $table->time('end_time');
                $table->integer('money_min');
                $table->integer('money_max');
                $table->integer('time_each')->comment("Thời gian mỗi phiên điểm danh");
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_attendance_settings');
    }

}
