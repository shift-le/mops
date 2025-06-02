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
        Schema::create('CART', function (Blueprint $table) {
            $table->string('USER_ID', 32);
            $table->string('TOOL_CODE', 20);
            $table->smallInteger('QUANTITY');
            $table->timestamp('updated_at')->useCurrent()->nullable();

            $table->primary(['USER_ID', 'TOOL_CODE']);

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
        Schema::dropIfExists('CART');
    }
};
