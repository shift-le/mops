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
        Schema::table('THUZAIIN', function (Blueprint $table) {
            $table->tinyInteger('DEL_FLG')->default(0)->comment('削除フラグが設定される。0：未削除 1：削除済');
            $table->dateTime('CREATE_DT')->nullable()->comment('このデータを新規に登録した日時が設定される。');
            $table->string('CREATE_APP', 50)->nullable()->comment('このデータを新規に登録したアプリケーションＩＤが設定される。');
            $table->string('CREATE_USER', 32)->nullable()->comment('このデータを新規に登録したユーザＩＤが設定される。');
            $table->dateTime('UPDATE_DT')->nullable()->comment('このデータの最新更新日付が設定される。');
            $table->string('UPDATE_APP', 50)->nullable()->comment('このデータの最新更新アプリケーションＩＤが設定される。');
            $table->string('UPDATE_USER', 32)->nullable()->comment('このデータの最新更新ユーザＩＤが設定される。');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('THUZAIIN', function (Blueprint $table) {
            $table->dropColumn([
                'DEL_FLG',
                'CREATE_DT',
                'CREATE_APP',
                'CREATE_USER',
                'UPDATE_DT',
                'UPDATE_APP',
                'UPDATE_USER'
            ]);
        });
    }
};
