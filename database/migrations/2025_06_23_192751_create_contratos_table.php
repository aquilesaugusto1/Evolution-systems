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
        Schema::create('contratos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('empresas_parceiras')->onDelete('cascade');
            $table->string('numero_contrato')->nullable()->unique();
            $table->string('tipo_contrato');
            $table->json('produtos');
            $table->string('especifique_outro')->nullable();
            $table->foreignId('coordenador_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('tech_lead_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('status')->default('Ativo');
            $table->date('data_inicio');
            $table->date('data_termino')->nullable();
            $table->string('contato_principal')->nullable();
            $table->decimal('baseline_horas_mes', 8, 2)->nullable();
            $table->boolean('permite_antecipar_baseline')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contratos');
    }
};
