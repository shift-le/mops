<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('ORDER_MEISAI', function (Blueprint $table) {
            $table->string('UNIT_TYPE', 20)->nullable()->after('TOOL_NAME');
        });
    }

    public function down(): void
    {
        Schema::table('ORDER_MEISAI', function (Blueprint $table) {
            $table->dropColumn('UNIT_TYPE');
        });
    }
};
