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
    Schema::create('USERS', function (Blueprint $table) {
        $table->string('USER_ID', 32)->primary();
        $table->string('SHAIN_ID', 32)->nullable();
        $table->string('NAME', 40);
        $table->string('NAME_KANA', 40);
        $table->string('PASSWORD', 128);
        $table->string('EMAIL', 64)->nullable();
        $table->string('MOBILE_TEL', 20)->nullable();
        $table->string('MOBILE_EMAIL', 64)->nullable();
        $table->string('SHITEN_BU_CODE', 30)->nullable();
        $table->string('EIGYOSHO_GROUP_CODE', 30)->nullable();
        $table->string('ROLE_ID', 4)->nullable();
        $table->tinyInteger('DEL_FLG')->default(0);
        $table->string('UPDATE_FLG', 1)->default('1');
        $table->dateTime('CREATE_DT')->nullable();
        $table->string('CREATE_APP', 50)->nullable();
        $table->string('CREATE_USER', 32)->nullable();
        $table->dateTime('UPDATE_DT')->nullable();
        $table->string('UPDATE_APP', 50)->nullable();
        $table->string('UPDATE_USER', 32)->nullable();
    });

        Schema::create('PASSWORD_RESET_TOKEN', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('USERS');
        Schema::dropIfExists('PASSWORD_RESET_TOKEN');
        Schema::dropIfExists('sessions');
    }
};
