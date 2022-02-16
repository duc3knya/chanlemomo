<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGap3sTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gap3s', function (Blueprint $table) {
            $table->id();
            $table->integer('min');
            $table->integer('max');
            $table->string('sdt');
            $table->double('tile1');
            $table->double('tile2');
            $table->double('tile3');
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
        Schema::dropIfExists('gap3s');
    }
}
