<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Traits\ConvertsTime;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    use ConvertsTime;

    public function up(): void
    {
        $apontamentosToUpdate = [];
        $apontamentos = DB::table('apontamentos')->get();
        foreach ($apontamentos as $apontamento) {
            $apontamentosToUpdate[$apontamento->id] = self::decimalToTime((float) $apontamento->horas_gastas);
        }

        $contratosToUpdate = [];
        $contratos = DB::table('contratos')->get();
        foreach ($contratos as $contrato) {
            $contratosToUpdate[$contrato->id] = [
                'baseline_horas_mes' => $contrato->baseline_horas_mes ? self::decimalToTime((float) $contrato->baseline_horas_mes) : null,
                'baseline_horas_original' => $contrato->baseline_horas_original ? self::decimalToTime((float) $contrato->baseline_horas_original) : null,
            ];
        }

        Schema::table('apontamentos', function (Blueprint $table) {
            $table->string('horas_gastas')->change();
        });

        Schema::table('contratos', function (Blueprint $table) {
            $table->string('baseline_horas_mes')->nullable()->change();
            $table->string('baseline_horas_original')->nullable()->change();
        });

        foreach ($apontamentosToUpdate as $id => $horas_gastas) {
            DB::table('apontamentos')->where('id', $id)->update(['horas_gastas' => $horas_gastas]);
        }

        foreach ($contratosToUpdate as $id => $horas) {
            DB::table('contratos')->where('id', $id)->update([
                'baseline_horas_mes' => $horas['baseline_horas_mes'],
                'baseline_horas_original' => $horas['baseline_horas_original'],
            ]);
        }
    }

    public function down(): void
    {
        $apontamentosToUpdate = [];
        $apontamentos = DB::table('apontamentos')->get();
        foreach ($apontamentos as $apontamento) {
            $apontamentosToUpdate[$apontamento->id] = self::timeToDecimal($apontamento->horas_gastas);
        }

        $contratosToUpdate = [];
        $contratos = DB::table('contratos')->get();
        foreach ($contratos as $contrato) {
             $contratosToUpdate[$contrato->id] = [
                'baseline_horas_mes' => $contrato->baseline_horas_mes ? self::timeToDecimal($contrato->baseline_horas_mes) : null,
                'baseline_horas_original' => $contrato->baseline_horas_original ? self::timeToDecimal($contrato->baseline_horas_original) : null,
            ];
        }

        Schema::table('apontamentos', function (Blueprint $table) {
            $table->decimal('horas_gastas', 8, 2)->change();
        });

        Schema::table('contratos', function (Blueprint $table) {
            $table->decimal('baseline_horas_mes', 8, 2)->nullable()->change();
            $table->decimal('baseline_horas_original', 8, 2)->nullable()->change();
        });
        
        foreach ($apontamentosToUpdate as $id => $horas_gastas) {
            DB::table('apontamentos')->where('id', $id)->update(['horas_gastas' => $horas_gastas]);
        }
        
        foreach ($contratosToUpdate as $id => $horas) {
            DB::table('contratos')->where('id', $id)->update([
                'baseline_horas_mes' => $horas['baseline_horas_mes'],
                'baseline_horas_original' => $horas['baseline_horas_original'],
            ]);
        }
    }
};