<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('ORDER_MEISAI', function (Blueprint $table) {
        $table->string('TOOL_NAME', 128)->nullable()->after('TOOLID');
        $table->integer('TANKA')->nullable()->after('TOOL_NAME');
        $table->string('ORDER_PHONE', 32)->nullable()->after('ORDER_ADDRESS');
        $table->string('NOTE', 200)->nullable()->after('ORDER_PHONE');
    });
}

public function down(): void
{
    Schema::table('ORDER_MEISAI', function (Blueprint $table) {
        $table->dropColumn(['TOOL_NAME', 'TANKA', 'ORDER_PHONE', 'NOTE']);
    });
}

};
