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
        Schema::create('RYOIKI', function (Blueprint $table) {
            $table->string('RYOIKI_CODE', 20)->primary(); // 領域コード（主キー）
            $table->string('HINMEI_CODE', 20)->nullable(); // 品名コード
            $table->string('RYOIKI_NAME', 100); // 領域名称

            $table->smallInteger('DISP_ORDER')->nullable(); // 表示順
            $table->smallInteger('QUANTITY')->nullable();   // 数量

            $table->tinyInteger('ACTION_FLG')->nullable(); // 有効フラグ（0:無効, 1:有効）
            $table->tinyInteger('DEL_FLG')->nullable()->default(0); // 削除フラグ（0:未削除, 1:削除済）

            $table->dateTime('CREATE_DT')->nullable(); // 登録日時
            $table->string('CREATE_APP', 50)->nullable(); // 登録APP
            $table->string('CREATE_USER', 32)->nullable(); // 登録者ID

            $table->dateTime('UPDATE_DT')->nullable(); // 更新日時
            $table->string('UPDATE_APP', 50)->nullable(); // 更新APP
            $table->string('UPDATE_USER', 32)->nullable(); // 更新者ID
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('RYOIKI');
    }
};
