<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up()
{
    Schema::create('ORDER_MEISAI', function (Blueprint $table) {
        $table->string('ORDER_CODE', 20);
        $table->string('TOOL_CODE', 20);
        $table->string('USER_ID', 32);
        $table->string('IRAI_NAME', 32);
        $table->string('ORDER_NAME', 32);
        $table->string('ORDER_ADDRESS', 128)->nullable();
        $table->string('ORDER_STATUS', 1)->default('1');
        $table->string('TOOLID', 32);
        $table->string('AMOUNT', 32);
        $table->string('TOOL_QUANTITY', 32);
        $table->string('QUANTITY', 32);
        $table->string('SUBTOTAL', 32);
        $table->tinyInteger('DEL_FLG')->default(0);
        $table->dateTime('CREATE_DT');
        $table->string('CREATE_APP', 50);
        $table->string('CREATE_USER', 32);
        $table->dateTime('UPDATE_DT');
        $table->string('UPDATE_APP', 50);
        $table->string('UPDATE_USER', 32);

        $table->primary(['TOOL_CODE', 'TOOLID']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ORDER_MEISAI');
    }
};
