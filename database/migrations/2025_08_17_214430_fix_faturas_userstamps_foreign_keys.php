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
        Schema::table('faturas', function (Blueprint $table) {
            // Remove as chaves estrangeiras incorretas que apontam para a tabela 'users'
            $table->dropForeign('faturas_created_by_foreign');
            $table->dropForeign('faturas_updated_by_foreign');

            // Adiciona as chaves estrangeiras corretas, apontando para a tabela 'usuarios'
            $table->foreign('created_by')->references('id')->on('usuarios')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('usuarios')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            // Método para reverter, caso seja necessário (desfaz as correções)
            $table->dropForeign(['created_by']);
            $table->dropForeign(['updated_by']);

            $table->foreign('created_by', 'faturas_created_by_foreign')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by', 'faturas_updated_by_foreign')->references('id')->on('users')->onDelete('set null');
        });
    }
};
