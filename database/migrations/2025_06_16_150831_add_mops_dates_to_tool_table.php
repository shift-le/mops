<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('TOOL', function (Blueprint $table) {
            $table->date('MOPS_START_DATE')->default('2000-01-01')->after('MOPS_ADD_DATE');
            $table->date('MOPS_END_DATE')->default('2099-12-31')->after('MOPS_START_DATE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('TOOL', function (Blueprint $table) {
            $table->dropColumn('MOPS_START_DATE');
            $table->dropColumn('MOPS_END_DATE');
        });
    }
};
