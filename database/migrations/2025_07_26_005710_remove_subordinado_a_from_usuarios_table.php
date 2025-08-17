<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            // Remove a chave estrangeira antes de apagar a coluna
            $table->dropForeign(['subordinado_a']);
            $table->dropColumn('subordinado_a');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->foreignId('subordinado_a')->nullable()->constrained('usuarios')->onDelete('set null');
        });
    }
};
