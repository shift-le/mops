<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('ORDER', function (Blueprint $table) {
            $table->string('ORDER_CODE', 20)->primary();
            $table->string('ORDER_STATUS', 1);
            $table->string('HASSOUSAKI_CODE', 20);
            $table->string('USER_ID', 32)->index(); // ← ここでインデックス追加してUSER_ID検索しやすくする
            $table->string('IRAI_NAME', 32);
            $table->string('ORDER_NAME', 32);
            $table->string('ORDER_ADDRESS', 128)->nullable();
            $table->string('ORDER_PHONE', 32);
            $table->string('ORDER_STATUS2', 1);
            $table->string('ORDER_TOOLID', 32);
            $table->integer('AMOUNT');
            $table->integer('SUBTOTAL');
            $table->tinyInteger('DEL_FLG')->default(0);
            $table->dateTime('CREATE_DT');
            $table->string('CREATE_APP', 50);
            $table->string('CREATE_USER', 32);
            $table->dateTime('UPDATE_DT');
            $table->string('UPDATE_APP', 50);
            $table->string('UPDATE_USER', 32);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ORDER');
    }
};
