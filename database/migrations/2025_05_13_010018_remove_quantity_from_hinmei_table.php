<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
        {
            Schema::table('hinmei', function (Blueprint $table) {
                $table->dropColumn('QUANTITY');
            });
        }

        public function down()
        {
            Schema::table('hinmei', function (Blueprint $table) {
                $table->integer('QUANTITY')->default(0); // 元に戻す用
            });
        }

};
