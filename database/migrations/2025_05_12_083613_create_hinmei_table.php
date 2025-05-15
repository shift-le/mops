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
        Schema::create('hinmei', function (Blueprint $table) {
            $table->string('HINMEI_CODE', 20)->primary();  // 品名コード（主キー）
            $table->string('HINMEI_NAME', 100);             // 品名
            $table->smallInteger('DISP_ORDER')->nullable(); // 表示順
            $table->smallInteger('QUANTITY')->nullable();   // 数量

            $table->tinyInteger('ACTION_FLG')->nullable();  // 有効フラグ（0:OFF, 1:ON）
            $table->tinyInteger('DEL_FLG')->nullable()->default(0); // 削除フラグ（0:未削除, 1:削除済）

            $table->dateTime('CREATE_DT')->nullable();      // 登録日時
            $table->string('CREATE_APP', 50)->nullable();   // 登録アプリ
            $table->string('CREATE_USER', 32)->nullable();  // 登録者ID

            $table->dateTime('UPDATE_DT')->nullable();      // 更新日時
            $table->string('UPDATE_APP', 50)->nullable();   // 更新アプリ
            $table->string('UPDATE_USER', 32)->nullable();  // 更新者ID

            $table->string('RYOIKI_CODE', 20)->nullable();  // ← 領域マスタへの外部キー相当
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hinmei');
    }
};
