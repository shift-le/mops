<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('M_TOOL_TYPE1', function (Blueprint $table) {
            if (!Schema::hasColumn('M_TOOL_TYPE1', 'DISP_ORDER')) {
                $table->integer('DISP_ORDER')->nullable()->after('TOOL_TYPE1_NAME');
            }
            if (Schema::hasColumn('M_TOOL_TYPE1', 'DISPLAY_TURN')) {
                $table->dropColumn('DISPLAY_TURN');
            }
        });

        Schema::table('M_TOOL_TYPE2', function (Blueprint $table) {
            if (!Schema::hasColumn('M_TOOL_TYPE2', 'DISP_ORDER')) {
                $table->integer('DISP_ORDER')->nullable()->after('TOOL_TYPE2_NAME');
            }
            if (Schema::hasColumn('M_TOOL_TYPE2', 'DISPLAY_TURN')) {
                $table->dropColumn('DISPLAY_TURN');
            }
        });
    }

    public function down(): void
    {
        Schema::table('M_TOOL_TYPE1', function (Blueprint $table) {
            if (Schema::hasColumn('M_TOOL_TYPE1', 'DISP_ORDER')) {
                $table->dropColumn('DISP_ORDER');
            }
            $table->integer('DISPLAY_TURN')->nullable()->after('TOOL_TYPE1_NAME');
        });

        Schema::table('M_TOOL_TYPE2', function (Blueprint $table) {
            if (Schema::hasColumn('M_TOOL_TYPE2', 'DISP_ORDER')) {
                $table->dropColumn('DISP_ORDER');
            }
            $table->integer('DISPLAY_TURN')->nullable()->after('TOOL_TYPE2_NAME');
        });
    }
};
