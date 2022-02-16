<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLichSuChoiNoHusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lich_su_choi_no_hus', function (Blueprint $table) {
            $table->id();
            $table->string('sdt');
            $table->string('magiaodich');
            $table->integer('tiencuoc');
            $table->integer('tienvaohu');
            $table->integer('tiennhan');
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
        Schema::dropIfExists('lich_su_choi_no_hus');
    }
}
