<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLichSuAttendanceDate extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lich_su_attendance_date', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('phone')->nullable();
            $table->integer('mocchoi')->nullable();
            $table->integer('tiennhan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lich_su_attendance_date');
    }
}
