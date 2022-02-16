<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnOnDiemdanhNgayForSettingTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('settings', 'on_diemdanh_ngay')) {
            Schema::table('settings', function(Blueprint $table) {
                $table->tinyInteger('on_diemdanh_ngay')->after('on_diemdanh')->default(1);
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
        if (Schema::hasColumn('settings', 'on_diemdanh_ngay')) {
            Schema::table('settings', function(Blueprint $table) {
                $table->dropColumn('on_diemdanh_ngay');
            });
        }
    }

}
