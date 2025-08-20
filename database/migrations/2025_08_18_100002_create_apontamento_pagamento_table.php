<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('apontamento_pagamento', function (Blueprint $table) {
            $table->id();
            $table->foreignId('apontamento_id')->constrained('apontamentos')->onDelete('cascade');
            $table->foreignId('pagamento_id')->constrained('pagamentos')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('apontamento_pagamento');
    }
};
