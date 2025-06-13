<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColumnsInPasswordResetTokenTable extends Migration
{
    public function up(): void
    {
        Schema::table('PASSWORD_RESET_TOKEN', function (Blueprint $table) {
            // 重複回避のため先に削除
            if (Schema::hasColumn('PASSWORD_RESET_TOKEN', 'created_at')) {
                $table->dropColumn('created_at');
            }

            $table->renameColumn('EMAIL', 'email');
            $table->renameColumn('TOKEN', 'token');
            $table->renameColumn('CREATE_DT', 'created_at');
        });
    }


    public function down(): void
    {
        Schema::table('PASSWORD_RESET_TOKEN', function (Blueprint $table) {
            $table->renameColumn('email', 'EMAIL');
            $table->renameColumn('token', 'TOKEN');
            $table->renameColumn('created_at', 'CREATE_DT');
        });
    }
}
