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
        Schema::rename('GENERAL_CLASS', 'M_GENERAL_TYPE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('M_GENERAL_TYPE', 'GENERAL_CLASS');
    }
};
