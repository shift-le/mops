<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('M_THUZAIIN', function (Blueprint $table) {
            if (Schema::hasColumn('M_THUZAIIN', 'POST_CODE1')) {
                $table->dropColumn('POST_CODE1');
            }
            if (Schema::hasColumn('M_THUZAIIN', 'POST_CODE2')) {
                $table->dropColumn('POST_CODE2');
            }
        });
    }

    public function down(): void
    {
        Schema::table('M_THUZAIIN', function (Blueprint $table) {
            $table->string('POST_CODE1', 10)->nullable()->after('USER_ID');
            $table->string('POST_CODE2', 10)->nullable()->after('POST_CODE1');
        });
    }
};
