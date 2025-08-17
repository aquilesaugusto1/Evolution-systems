<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('apontamentos', function (Blueprint $table) {
            $table->foreignId('fatura_id')->nullable()->after('status')->constrained('faturas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('apontamentos', function (Blueprint $table) {
            $table->dropForeign(['fatura_id']);
            $table->dropColumn('fatura_id');
        });
    }
};