<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('M_TOOL_TYPE2', function (Blueprint $table) {
            $table->smallInteger('TOOL_TYPE1')->change();
            $table->smallInteger('TOOL_TYPE2')->change();
        });
    }

    public function down(): void
    {
        Schema::table('M_TOOL_TYPE2', function (Blueprint $table) {
            $table->tinyInteger('TOOL_TYPE1')->change();
            $table->tinyInteger('TOOL_TYPE2')->change();
        });
    }
};

