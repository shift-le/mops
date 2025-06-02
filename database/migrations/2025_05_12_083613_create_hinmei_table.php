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
        Schema::create('HINMEI', function (Blueprint $table) {
            $table->string('HINMEI_CODE', 20)->primary();
            $table->string('HINMEI_NAME', 100);
            $table->smallInteger('DISP_ORDER')->nullable();
            $table->smallInteger('QUANTITY')->nullable();

            $table->tinyInteger('ACTION_FLG')->nullable();
            $table->tinyInteger('DEL_FLG')->nullable()->default(0);

            $table->dateTime('CREATE_DT')->nullable();
            $table->string('CREATE_APP', 50)->nullable();
            $table->string('CREATE_USER', 32)->nullable();

            $table->dateTime('UPDATE_DT')->nullable();
            $table->string('UPDATE_APP', 50)->nullable();
            $table->string('UPDATE_USER', 32)->nullable();

            $table->string('RYOIKI_CODE', 20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('HINMEI');
    }
};
