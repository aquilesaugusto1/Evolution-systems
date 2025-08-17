<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            if (! Schema::hasColumn('sugestoes', 'usuario_id')) {
                $table->foreignId('usuario_id')->after('id')->constrained('usuarios')->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('sugestoes', function (Blueprint $table) {
            if (Schema::hasColumn('sugestoes', 'usuario_id')) {
                $table->dropForeign(['usuario_id']);
                $table->dropColumn('usuario_id');
            }
        });
    }
};
