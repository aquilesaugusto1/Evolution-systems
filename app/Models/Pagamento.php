<?php

namespace App\Models;

use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Pagamento extends Model
{
    use HasFactory, Userstamps;

    protected $fillable = [
        'user_id',
        'periodo_ref',
        'valor_total',
        'status',
        'asaas_transfer_id',
        'observacoes',
        'processado_por',
    ];

    protected function casts(): array
    {
        return [
            'periodo_ref' => 'date',
            'valor_total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processado_por');
    }

    public function apontamentos(): BelongsToMany
    {
        return $this->belongsToMany(Apontamento::class, 'apontamento_pagamento');
    }
}
