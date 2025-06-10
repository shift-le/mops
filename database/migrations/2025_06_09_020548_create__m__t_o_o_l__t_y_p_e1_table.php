<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('M_TOOL_TYPE1', function (Blueprint $table) {
            $table->tinyInteger('TOOL_TYPE1')->primary()->comment('ツール区分1');
            $table->string('TOOL_TYPE1_NAME', 200)->nullable()->comment('ツール区分1名称');
            $table->smallInteger('DISPLAY_TURN')->nullable()->comment('表示順');

            $table->dateTime('CREATE_DT')->nullable()->comment('登録日時');
            $table->string('CREATE_APP', 50)->nullable()->comment('登録APP');
            $table->string('CREATE_USER', 32)->nullable()->comment('登録者ID');

            $table->dateTime('UPDATE_DT')->nullable()->comment('更新日時');
            $table->string('UPDATE_APP', 50)->nullable()->comment('更新APP');
            $table->string('UPDATE_USER', 32)->nullable()->comment('更新者ID');

            $table->index('DISPLAY_TURN');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('M_TOOL_TYPE1');
    }
};
