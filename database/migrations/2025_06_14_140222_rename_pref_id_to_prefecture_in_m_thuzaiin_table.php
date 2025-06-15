<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePrefIdToPrefectureInMThuzaiinTable extends Migration
{
    public function up()
    {
        Schema::table('M_THUZAIIN', function (Blueprint $table) {
            $table->renameColumn('PREF_ID', 'PREFECTURE');
        });
    }

    public function down()
    {
        Schema::table('M_THUZAIIN', function (Blueprint $table) {
            $table->renameColumn('PREFECTURE', 'PREF_ID');
        });
    }
}