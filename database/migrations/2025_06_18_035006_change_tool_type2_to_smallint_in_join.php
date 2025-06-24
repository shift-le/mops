<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeToolType2ToSmallintInJoin extends Migration
{
    public function up(): void
    {
        Schema::table('M_TOOL_TYPE_JOIN', function (Blueprint $table) {
            $table->smallInteger('TOOL_TYPE2')->change();
        });
    }

    public function down(): void
    {
        Schema::table('M_TOOL_TYPE_JOIN', function (Blueprint $table) {
            $table->tinyInteger('TOOL_TYPE2')->change();
        });
    }
}
