<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('usuarios')->onDelete('cascade');
            $table->date('periodo_ref');
            $table->decimal('valor_total', 10, 2);
            $table->string('status')->default('pendente'); // ex: pendente, processando, pago, erro
            $table->string('asaas_transfer_id')->nullable();
            $table->text('observacoes')->nullable();
            $table->foreignId('processado_por')->nullable()->constrained('usuarios');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagamentos');
    }
};
