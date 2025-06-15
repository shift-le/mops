<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeliveryColumnsToOrderTable extends Migration
{
    public function up()
    {
        Schema::table('ORDER', function (Blueprint $table) {
            $table->string('DELI_NAME', 100)->nullable()->after('ORDER_PHONE');
            $table->string('DELI_ADDRESS', 255)->nullable()->after('DELI_NAME');
            $table->string('DELI_PHONE', 20)->nullable()->after('DELI_ADDRESS');
            $table->string('NOTE', 255)->nullable()->after('DELI_PHONE');
        });
    }

    public function down()
    {
        Schema::table('ORDER', function (Blueprint $table) {
            $table->dropColumn(['DELI_NAME', 'DELI_ADDRESS', 'DELI_PHONE', 'NOTE']);
        });
    }
}

