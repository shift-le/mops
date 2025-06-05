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
        Schema::create('TOOL', function (Blueprint $table) {
            $table->string('TOOL_CODE', 30)->primary();

            // ファイル系
            $table->string('TOOL_THUM_FILE', 255)->nullable();
            $table->string('TOOL_PDF_FILE', 255)->nullable();

            // 日付・ステータス
            $table->dateTime('MOPS_ADD_DATE')->nullable();
            $table->string('TOOL_STATUS', 1)->nullable();
            $table->tinyInteger('MST_FLG')->nullable()->default(0);

            $table->dateTime('DISPLAY_START_DATE')->nullable();
            $table->dateTime('DISPLAY_END_DATE')->nullable();
            $table->dateTime('KANRI_LIMIT_DATE')->nullable();

            // テキスト情報
            $table->string('SOSHIKI1', 30)->nullable();
            $table->string('SOSHIKI2', 30)->nullable();
            $table->string('TOOL_NAME_KANA', 60)->nullable();
            $table->string('TOOL_NAME', 255)->nullable();
            $table->string('TOOL_SHORT_NAME', 20)->nullable();

            // 数値系
            $table->integer('IRISU')->nullable()->default(0);
            $table->integer('SHUKKA_TANISU')->nullable()->default(0);
            $table->integer('MAX_ORDER')->nullable();

            // マスタ関連
            $table->string('RYOIKI', 20)->nullable(); // 領域（外部キー的）
            $table->string('HINMEI', 20)->nullable(); // 品名（外部キー的）

            // 区分等
            $table->string('CATEGORY3', 20)->nullable();
            $table->string('TOOL_TYPE1', 20)->nullable();
            $table->string('TOOL_TYPE2', 20)->nullable();
            $table->string('KOTEI_KEYWORD3', 20)->nullable();
            $table->string('UNIT_TYPE', 2)->nullable();
            $table->string('TOOL_TYPE', 5)->nullable();
            $table->string('ZAIKO_TYPE', 5)->nullable();
            $table->string('SET_TYPE', 5)->nullable();
            $table->string('YOSAN_KANRI_FLG', 5)->nullable();
            $table->string('HATTHUTEN_KANRI_TYPE', 5)->nullable();

            // フラグ系
            $table->tinyInteger('SHOUNIN_KAKUNIN_FLG')->nullable();
            $table->decimal('HATTHU_KIJYUNCHI', 9)->nullable();
            $table->decimal('HATTHU_TANI', 9)->nullable();
            $table->decimal('JAN_CODE', 20)->nullable();
            $table->string('KATABAN', 20)->nullable();
            $table->decimal('SHIIRESAKI_CODE', 20)->nullable();
            $table->decimal('TANKA', 9)->nullable();

            // 管理フラグ
            $table->tinyInteger('TOOL_KANRI_FLG')->nullable()->default(1);
            $table->string('TOOL_SETSUMEI', 1000)->nullable();
            $table->string('REMARKS', 1000)->nullable();
            $table->tinyInteger('YOSAN_KANRIKANOU_USER_DOUITSU_FLG')->nullable();
            $table->tinyInteger('NEW_FLG')->nullable();
            $table->dateTime('NEW_DISPLAY_START_DATE')->nullable();
            $table->dateTime('NEW_DISPLAY_END_DATE')->nullable();

            $table->tinyInteger('SERIAL_NUM_KANRI_FLG')->nullable();
            $table->tinyInteger('LOTNO_KANRI_FLG')->nullable();
            $table->tinyInteger('YUKOUKIGEN_KANRI_FLG')->nullable();
            $table->tinyInteger('JYOTAI_KANRI_FLG')->nullable();
            $table->tinyInteger('FUKUROZUME_KONPOUZAI_FLG')->nullable();
            $table->tinyInteger('TOOL_ORDER_KANRI_FLG')->nullable();

            // 表示関連日付
            $table->dateTime('DISPLAY_START_DATE1')->nullable();
            $table->dateTime('DISPLAY_END_DATE2')->nullable();

            // オーダー関連項目
            $table->string('TOOL_NAME3', 255)->nullable();
            $table->string('TOOL_SETSUMEI4', 1000)->nullable();
            $table->decimal('ORDER_KANOUSU_FROM', 9)->nullable();
            $table->decimal('ORDER_MAX', 9)->nullable();
            $table->decimal('DISPLAY_MAX', 9)->nullable();

            // 領域カテゴリ関連
            for ($i = 2; $i <= 5; $i++) {
                $table->string("RYOIKI_CD$i", 20)->nullable();
                $table->string("HINMEI_CATEGORY_CD$i", 20)->nullable();
            }

            // 管理者（最大10人）
            for ($i = 1; $i <= 10; $i++) {
                $table->string("TOOL_MANAGER{$i}_ID", 32)->nullable();
                $table->string("TOOL_MANAGER{$i}_NAME", 40)->nullable();
            }

            // 登録・更新情報
            $table->tinyInteger('DEL_FLG')->nullable()->default(0);
            $table->dateTime('CREATE_DT')->nullable();
            $table->string('CREATE_APP', 50)->nullable();
            $table->string('CREATE_USER', 32)->nullable();
            $table->dateTime('UPDATE_DT')->nullable();
            $table->string('UPDATE_APP', 50)->nullable();
            $table->string('UPDATE_USER', 32)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('TOOL');
    }
};
