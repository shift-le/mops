<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // テーブル名の変更
        Schema::rename('SOSHIKI1', 'M_SOSHIKI1');
        Schema::rename('SOSHIKI2', 'M_SOSHIKI2');

        // M_SOSHIKI1テーブルの変更
        Schema::table('M_SOSHIKI1', function (Blueprint $table) {
            // 既存のPOST_CODE1,2削除（存在していれば）
            if (Schema::hasColumn('M_SOSHIKI1', 'POST_CODE1')) {
                $table->dropColumn('POST_CODE1');
            }
            if (Schema::hasColumn('M_SOSHIKI1', 'POST_CODE2')) {
                $table->dropColumn('POST_CODE2');
            }

            // 新しいPOST_CODEカラム追加（USER_IDの後など、適宜調整）
            if (!Schema::hasColumn('M_SOSHIKI1', 'POST_CODE')) {
                $table->string('POST_CODE')->nullable()->after('SOSHIKI1_SHORT_NAME');
            }
        });

        // M_SOSHIKI2テーブルの変更
        Schema::table('M_SOSHIKI2', function (Blueprint $table) {
            if (Schema::hasColumn('M_SOSHIKI2', 'POST_CODE1')) {
                $table->dropColumn('POST_CODE1');
            }
            if (Schema::hasColumn('M_SOSHIKI2', 'POST_CODE2')) {
                $table->dropColumn('POST_CODE2');
            }

            if (!Schema::hasColumn('M_SOSHIKI2', 'POST_CODE')) {
                $table->string('POST_CODE')->nullable()->after('SOSHIKI2_SHORT_NAME');
            }
        });
    }

    public function down(): void
    {
        // M_SOSHIKI1,2 を元の名前に戻す
        Schema::rename('M_SOSHIKI1', 'SOSHIKI1');
        Schema::rename('M_SOSHIKI2', 'SOSHIKI2');

        // カラムの巻き戻し（必要であれば下記追加）
        Schema::table('SOSHIKI1', function (Blueprint $table) {
            $table->dropColumn('POST_CODE');
            $table->string('POST_CODE1')->nullable();
            $table->string('POST_CODE2')->nullable();
        });

        Schema::table('SOSHIKI2', function (Blueprint $table) {
            $table->dropColumn('POST_CODE');
            $table->string('POST_CODE1')->nullable();
            $table->string('POST_CODE2')->nullable();
        });
    }
};
