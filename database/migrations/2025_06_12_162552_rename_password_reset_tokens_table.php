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
    Schema::rename('password_reset_tokens', 'PASSWORD_RESET_TOKEN');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::rename('PASSWORD_RESET_TOKEN', 'password_reset_tokens');
    }
};
