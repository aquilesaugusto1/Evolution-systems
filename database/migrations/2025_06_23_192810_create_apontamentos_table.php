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
        Schema::create('apontamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('agenda_id')->constrained('agendas')->onDelete('cascade')->unique();
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');

            $table->date('data_apontamento');
            $table->time('hora_inicio');
            $table->time('hora_fim');
            $table->decimal('horas_gastas', 8, 2);
            $table->text('descricao');
            $table->string('caminho_anexo')->nullable();

            $table->string('status')->default('Pendente'); // Padronizado para 'status'
            $table->boolean('faturavel')->default(true);

            $table->foreignId('aprovado_por_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->timestamp('data_aprovacao')->nullable();
            $table->text('motivo_rejeicao')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apontamentos');
    }
};
