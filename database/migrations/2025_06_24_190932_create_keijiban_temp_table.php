<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKeijibanTempTable extends Migration
{
    public function up()
    {
        Schema::create('KEIJIBAN_TEMP', function (Blueprint $table) {
            $table->string('KEIJIBAN_CODE'); // 掲示板コード（親）
            $table->integer('FILE_NO');      // 枝番（1〜5）
            $table->string('FILE_NAME');     // 保存ファイル名
            $table->timestamp('CREATE_DT')->nullable();
            $table->string('CREATE_APP', 50)->nullable();
            $table->string('CREATE_USER', 50)->nullable();
            $table->timestamp('UPDATE_DT')->nullable();
            $table->string('UPDATE_APP', 50)->nullable();
            $table->string('UPDATE_USER', 50)->nullable();

            $table->primary(['KEIJIBAN_CODE', 'FILE_NO']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('KEIJIBAN_TEMP');
    }
}
