<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->decimal('salario_mensal', 10, 2)->nullable()->after('dados_bancarios');
            $table->decimal('valor_hora', 10, 2)->nullable()->after('salario_mensal');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['salario_mensal', 'valor_hora']);
        });
    }
};
