<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('tipo_contrato')->nullable()->after('funcao');
            $table->json('dados_empresa_prestador')->nullable()->after('tipo_contrato');
            $table->json('dados_bancarios')->nullable()->after('dados_empresa_prestador');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn('tipo_contrato');
            $table->dropColumn('dados_empresa_prestador');
            $table->dropColumn('dados_bancarios');
        });
    }
};
