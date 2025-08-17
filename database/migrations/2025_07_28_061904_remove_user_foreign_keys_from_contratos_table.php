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
        Schema::table('contratos', function (Blueprint $table) {
            // Drop foreign keys first to avoid errors
            $table->dropForeign(['coordenador_id']);
            $table->dropForeign(['tech_lead_id']);

            // Then drop the columns
            $table->dropColumn(['coordenador_id', 'tech_lead_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('contratos', function (Blueprint $table) {
            // Re-add the columns if we need to rollback
            $table->foreignId('coordenador_id')->nullable()->constrained('usuarios')->onDelete('set null');
            $table->foreignId('tech_lead_id')->nullable()->constrained('usuarios')->onDelete('set null');
        });
    }
};
