<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->string('foto_url')->nullable()->after('nivel');
            $table->text('bio')->nullable()->after('foto_url');
            $table->json('redes_sociais')->nullable()->after('bio');
        });
    }

    public function down(): void
    {
        Schema::table('usuarios', function (Blueprint $table) {
            $table->dropColumn(['foto_url', 'bio', 'redes_sociais']);
        });
    }
};
