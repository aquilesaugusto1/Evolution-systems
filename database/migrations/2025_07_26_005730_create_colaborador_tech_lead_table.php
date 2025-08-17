<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('colaborador_tech_lead', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultor_id')->constrained('usuarios')->onDelete('cascade');
            $table->foreignId('tech_lead_id')->constrained('usuarios')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('colaborador_tech_lead');
    }
};
