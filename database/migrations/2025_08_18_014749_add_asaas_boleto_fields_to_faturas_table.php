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
            $table->string('billing_type')->default('PIX')->after('status');
            $table->text('asaas_boleto_url')->nullable()->after('asaas_pix_payload');
            $table->text('asaas_boleto_barcode')->nullable()->after('asaas_boleto_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('faturas', function (Blueprint $table) {
            $table->dropColumn('billing_type');
            $table->dropColumn('asaas_boleto_url');
            $table->dropColumn('asaas_boleto_barcode');
        });
    }
};
