<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();;
            $table->string('linkvideoyoutube')->nullable();
            $table->string('zalo')->nullable();
            $table->string('script')->nullable();
            $table->integer('baotri');
            $table->string('color_header');
            $table->string('color_footer');
            $table->string('color_table');
            $table->string('color_table2');
            $table->integer('on_chanle');
            $table->integer('on_taixiu');
            $table->integer('on_chanle2');
            $table->integer('on_gap3');
            $table->integer('on_tong3so');
            $table->integer('on_1phan3');
            $table->integer('on_nohu');
            $table->integer('on_trathuongtuan');
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
        Schema::dropIfExists('settings');
    }
}
