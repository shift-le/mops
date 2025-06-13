<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::dropIfExists('PASSWORD_RESET_TOKEN');

        Schema::create('PASSWORD_RESET_TOKEN', function (Blueprint $table) {
            $table->string('EMAIL')->index();             
            $table->string('TOKEN');                      
            $table->timestamp('CREATE_DT')->nullable(); 
            $table->string('CREATE_APP', 50)->nullable();
            $table->string('CREATE_USER', 32)->nullable();
            $table->timestamp('UPDATE_DT')->nullable();
            $table->string('UPDATE_APP', 50)->nullable();
            $table->string('UPDATE_USER', 32)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('PASSWORD_RESET_TOKEN');
    }
};

