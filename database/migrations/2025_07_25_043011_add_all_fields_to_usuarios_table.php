<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('sobrenome')->nullable()->after('nome');
            $table->date('data_nascimento')->nullable()->after('sobrenome');
            $table->string('nacionalidade')->nullable()->after('data_nascimento');
            $table->string('naturalidade')->nullable()->after('nacionalidade');
            $table->json('endereco')->nullable()->after('naturalidade');
            $table->string('email_totvs_partner')->nullable()->after('email');
            $table->string('status')->default('Ativo')->after('password');
            $table->string('cargo')->nullable()->after('tipo_contrato');
            $table->string('nivel')->nullable()->after('cargo');
            $table->foreignId('subordinado_a')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->string('visibilidade_agenda')->default('Restrito')->after('nivel');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropForeign(['subordinado_a']);
            $table->dropColumn([
                'sobrenome',
                'data_nascimento',
                'nacionalidade',
                'naturalidade',
                'endereco',
                'email_totvs_partner',
                'status',
                'cargo',
                'nivel',
                'subordinado_a',
                'visibilidade_agenda',
            ]);
        });
    }
};
