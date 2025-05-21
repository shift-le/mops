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
        Schema::create('FAQ', function (Blueprint $table) {
            $table->string('FAQ_CODE', 20)->primary();
            $table->string('FAQ_TITLE', 100);
            $table->string('FAQ_QUESTION', 4000);
            $table->string('FAQ_ANSWER', 4000);
            $table->smallInteger('DISP_ORDER');
            $table->tinyInteger('HYOJI_FLG')->default(0);
            $table->tinyInteger('DEL_FLG')->default(0);
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
        Schema::dropIfExists('FAQ');
    }
};
