<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMToolTypeJoinTable extends Migration
{
    public function up(): void
    {
        Schema::create('M_TOOL_TYPE_JOIN', function (Blueprint $table) {
            $table->tinyInteger('TOOL_TYPE1')->comment('ツール区分1');
            $table->tinyInteger('TOOL_TYPE2')->comment('ツール区分2');
            $table->tinyInteger('COMMON_TYPE')->nullable()->comment('共通区分コード');

            $table->dateTime('CREATE_DT')->nullable()->comment('登録日時');
            $table->string('CREATE_APP', 50)->nullable()->comment('登録APP');
            $table->string('CREATE_USER', 32)->nullable()->comment('登録者ID');

            $table->dateTime('UPDATE_DT')->nullable()->comment('更新日時');
            $table->string('UPDATE_APP', 50)->nullable()->comment('更新APP');
            $table->string('UPDATE_USER', 32)->nullable()->comment('更新者ID');

            $table->primary(['TOOL_TYPE1', 'TOOL_TYPE2']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('M_TOOL_TYPE_JOIN');
    }
}
