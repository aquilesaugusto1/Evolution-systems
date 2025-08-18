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
        Schema::table('faturas', function (Blueprint $table) {
            $table->text('asaas_pix_qrcode')->nullable()->after('asaas_payment_url');
            $table->text('asaas_pix_payload')->nullable()->after('asaas_pix_qrcode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropColumn('asaas_pix_qrcode');
            $table->dropColumn('asaas_pix_payload');
        });
    }
};
