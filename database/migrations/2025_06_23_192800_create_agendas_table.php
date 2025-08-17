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
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('contrato_id')->constrained('contratos')->onDelete('cascade');
            $table->string('assunto');
            $table->text('descricao')->nullable();
            $table->dateTime('data_hora'); // Alterado para corresponder ao formulário antigo
            $table->string('status')->default('Agendada');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('agendas');
    }
};
