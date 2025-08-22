<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Tax extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'tipo',
        'aliquota',
        'tipo_aliquota',
        'regra',
        'ativo',
    ];

    public function faturas(): BelongsToMany
    {
        return $this->belongsToMany(Fatura::class, 'fatura_tax')->withPivot('valor_imposto')->withTimestamps();
    }
}