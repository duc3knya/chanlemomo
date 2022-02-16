<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLichSuChoiMomosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lich_su_choi_momos', function (Blueprint $table) {
            $table->id();
            $table->string('sdt');
            $table->string('sdt_get');
            $table->string('magiaodich');
            $table->integer('tiencuoc');
            $table->integer('tiennhan');
            $table->string('trochoi');
            $table->string('noidung');
            $table->integer('ketqua');
            $table->integer('status');
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
        Schema::dropIfExists('lich_su_choi_momos');
    }
}
