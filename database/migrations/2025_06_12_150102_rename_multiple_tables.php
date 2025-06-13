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
        Schema::rename('HINMEI', 'M_HINMEI');
        Schema::rename('RYOIKI', 'M_RYOIKI');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('M_RYOIKI', 'RYOIKI');
    }
};
