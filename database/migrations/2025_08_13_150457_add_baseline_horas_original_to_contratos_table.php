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
        Schema::table('contratos', function (Blueprint $table) {
            // Adiciona a nova coluna, que pode ser nula, do tipo decimal.
            // O 'after' garante que ela será criada logo após a coluna existente.
            $table->decimal('baseline_horas_original', 8, 2)->nullable()->after('baseline_horas_mes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Lógica para remover a coluna caso seja necessário reverter a migration.
            $table->dropColumn('baseline_horas_original');
        });
    }
};
