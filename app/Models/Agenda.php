<?php

namespace App\Models;

use Database\Factories\AgendaFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Agenda extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultor_id',
        'contrato_id',
        'assunto',
        'descricao',
        'data_hora',
        'status',
        'faturavel',
        'tipo_periodo',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'faturavel' => 'boolean',
    ];

    public function consultor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'consultor_id');
    }

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function apontamento(): HasOne
    {
        return $this->hasOne(Apontamento::class);
    }
}