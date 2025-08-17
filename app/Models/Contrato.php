<?php

namespace App\Models;

use App\Traits\ConvertsTime;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Contrato extends Model
{
    use HasFactory, Userstamps, ConvertsTime;

    protected $fillable = [
        'cliente_id',
        'numero_contrato',
        'tipo_contrato',
        'produtos',
        'especifique_outro',
        'status',
        'data_inicio',
        'data_termino',
        'contato_principal',
        'baseline_horas_mes',
        'valor_hora',
        'baseline_horas_original',
        'permite_antecipar_baseline',
        'documento_baseline_path',
        'possui_engenharia_valores',
    ];

    protected $casts = [
        'produtos' => 'array',
        'data_inicio' => 'date',
        'data_termino' => 'date',
        'permite_antecipar_baseline' => 'boolean',
        'valor_hora' => 'float',
        'possui_engenharia_valores' => 'boolean',
    ];

    public function getBaselineHorasMesDecimalAttribute(): float
    {
        return self::timeToDecimal((string) $this->attributes['baseline_horas_mes']);
    }

    public function setBaselineHorasMesAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['baseline_horas_mes'] = self::decimalToTime((float) $value);
        } else {
            $this->attributes['baseline_horas_mes'] = $value;
        }
    }

    public function getBaselineHorasOriginalDecimalAttribute(): float
    {
        return self::timeToDecimal((string) $this->attributes['baseline_horas_original']);
    }

    public function setBaselineHorasOriginalAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['baseline_horas_original'] = self::decimalToTime((float) $value);
        } else {
            $this->attributes['baseline_horas_original'] = $value;
        }
    }

    public function empresaParceira(): BelongsTo
    {
        return $this->belongsTo(EmpresaParceira::class, 'cliente_id');
    }

    public function usuarios(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'contrato_usuario', 'contrato_id', 'usuario_id')
            ->withPivot('funcao_contrato')
            ->withTimestamps();
    }

    public function coordenadores(): BelongsToMany
    {
        return $this->usuarios()->wherePivot('funcao_contrato', 'coordenador');
    }

    public function techLeads(): BelongsToMany
    {
        return $this->usuarios()->wherePivot('funcao_contrato', 'tech_lead');
    }

    public function consultores(): BelongsToMany
    {
        return $this->usuarios()->wherePivot('funcao_contrato', 'consultor');
    }

    public function faturas(): HasMany
    {
        return $this->hasMany(Fatura::class);
    }
}
