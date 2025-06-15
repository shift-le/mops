<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('M_GENERAL_TYPE', function (Blueprint $table) {
            $table->string('TYPE_CODE', 30);
            $table->string('KEY', 10);
            $table->string('VALUE', 100)->nullable();
            $table->string('VALUE_KANA', 100)->nullable();
            $table->string('VALUE_ENGLISH', 100)->nullable();
            $table->string('REMARK', 200)->nullable();
            $table->smallInteger('DISP_ORDER')->nullable();
            $table->string('PRELIMINARY_ITEM1', 200)->nullable();
            $table->string('PRELIMINARY_ITEM2', 200)->nullable();
            $table->string('PRELIMINARY_ITEM3', 200)->nullable();
            $table->dateTime('CREATE_DT')->nullable();
            $table->string('CREATE_APP', 50)->nullable();
            $table->string('CREATE_USER', 32)->nullable();
            $table->dateTime('UPDATE_DT')->nullable();
            $table->string('UPDATE_APP', 50)->nullable();
            $table->string('UPDATE_USER', 32)->nullable();

            $table->primary(['TYPE_CODE', 'KEY']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('M_GENERAL_TYPE');
    }
};
