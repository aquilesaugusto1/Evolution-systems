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
        Schema::table('dashboard_preferences', function (Blueprint $table) {
            // 1. Apaga a chave estrangeira antiga que apontava para a tabela 'users'
            $table->dropForeign('dashboard_preferences_user_id_foreign');

            // 2. Cria a nova chave estrangeira correta, apontando para a tabela 'usuarios'
            $table->foreign('user_id')
                  ->references('id')
                  ->on('usuarios')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('dashboard_preferences', function (Blueprint $table) {
            // Desfaz as alterações: apaga a chave nova e recria a antiga
            $table->dropForeign(['user_id']);

            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }
};
