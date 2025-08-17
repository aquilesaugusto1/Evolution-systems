<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empresas_parceiras', function (Blueprint $table) {
            $table->id();
            $table->string('nome_empresa');
            $table->string('cnpj')->unique();
            $table->decimal('saldo_horas', 8, 2)->default(0);
            $table->string('status')->default('Ativo');
            $table->json('endereco_completo')->nullable();
            $table->json('contato_principal')->nullable();
            $table->json('contato_comercial')->nullable();
            $table->json('contato_financeiro')->nullable();
            $table->json('contato_tecnico')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empresas_parceiras');
    }
};
