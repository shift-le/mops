<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('M_COM_TOOL_TYPE', function (Blueprint $table) {
            $table->tinyInteger('COM_TOOL_TYPE')->primary()->comment('共通ツール区分');
            $table->string('COM_TOOL_TYPE_NAME', 200)->nullable()->comment('共通ツール区分名称');
            $table->smallInteger('DISPLAY_TURN')->nullable()->comment('表示順');

            $table->dateTime('CREATE_DT')->nullable()->comment('登録日時');
            $table->string('CREATE_APP', 50)->nullable()->comment('登録APP');
            $table->string('CREATE_USER', 32)->nullable()->comment('登録者ID');

            $table->dateTime('UPDATE_DT')->nullable()->comment('更新日時');
            $table->string('UPDATE_APP', 50)->nullable()->comment('更新APP');
            $table->string('UPDATE_USER', 32)->nullable()->comment('更新者ID');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('M_COM_TOOL_TYPE');
    }
};
