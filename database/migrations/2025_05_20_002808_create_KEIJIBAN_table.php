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
        Schema::create('KEIJIBAN', function (Blueprint $table) {
            $table->string('KEIJIBAN_CODE', 20)->primary();
            $table->string('JUYOUDO_STATUS', 1)->default('1');
            $table->dateTime('KEISAI_START_DATE');
            $table->dateTime('KEISAI_END_DATE');
            $table->string('KEIJIBAN_TITLE', 100);
            $table->string('KEIJIBAN_CATEGORY', 1)->default('1');
            $table->string('KEIJIBAN_TEXT', 4000);
            $table->boolean('HYOJI_FLG')->default(0);
            $table->boolean('DEL_FLG')->default(0);
            $table->dateTime('DEL_DT')->nullable();
            $table->dateTime('CREATE_DT');
            $table->string('CREATE_APP', 50);
            $table->string('CREATE_USER', 32);
            $table->dateTime('UPDATE_DT');
            $table->string('UPDATE_APP', 50);
            $table->string('UPDATE_USER', 32);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('KEIJIBAN');
    }
};
