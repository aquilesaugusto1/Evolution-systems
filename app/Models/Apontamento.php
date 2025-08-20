<?php

namespace App\Models;

use App\Traits\ConvertsTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Apontamento extends Model
{
    use HasFactory, ConvertsTime;

    protected $table = 'apontamentos';

    protected $fillable = [
        'consultor_id',
        'agenda_id',
        'contrato_id',
        'data_apontamento',
        'hora_inicio',
        'hora_fim',
        'horas_gastas',
        'descricao',
        'caminho_anexo',
        'status',
        'faturavel',
        'aprovado_por_id',
        'data_aprovacao',
        'motivo_rejeicao',
    ];

    protected $casts = [
        'data_apontamento' => 'date',
        'faturavel' => 'boolean',
        'data_aprovacao' => 'datetime',
    ];

    public function getHorasGastasDecimalAttribute(): float
    {
        return $this->timeToDecimal($this->attributes['horas_gastas']);
    }

    public function setHorasGastasAttribute($value)
    {
        if (is_numeric($value)) {
            $this->attributes['horas_gastas'] = $this->decimalToTime((float) $value);
        } else {
            $this->attributes['horas_gastas'] = $value;
        }
    }

    public function agenda(): BelongsTo
    {
        return $this->belongsTo(Agenda::class);
    }

    public function consultor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultor_id');
    }

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function aprovador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'aprovado_por_id');
    }

    public function fatura(): BelongsTo
    {
        return $this->belongsTo(Fatura::class);
    }

    public function pagamentos(): BelongsToMany
    {
        return $this->belongsToMany(Pagamento::class, 'apontamento_pagamento');
    }
}
