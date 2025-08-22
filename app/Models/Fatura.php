<?php

namespace App\Models;

use App\Enums\FaturaStatusEnum;
use App\Traits\Userstamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $contrato_id
 * @property string $numero_fatura
 * @property \Illuminate\Support\Carbon $data_emissao
 * @property \Illuminate\Support\Carbon $data_vencimento
 * @property float $valor_total
 * @property FaturaStatusEnum $status
 * @property string|null $observacoes
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read Contrato $contrato
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Apontamento> $apontamentos
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Tax> $impostos
 * @property-read User|null $creator
 * @property-read User|null $updater
 */
class Fatura extends Model
{
    use HasFactory, SoftDeletes, Userstamps;

    protected $table = 'faturas';

    protected $fillable = [
        'contrato_id',
        'numero_fatura',
        'data_emissao',
        'data_vencimento',
        'valor_total',
        'status',
        'observacoes',
    ];

    protected function casts(): array
    {
        return [
            'data_emissao' => 'date',
            'data_vencimento' => 'date',
            'valor_total' => 'decimal:2',
            'status' => FaturaStatusEnum::class,
        ];
    }

    public function contrato(): BelongsTo
    {
        return $this->belongsTo(Contrato::class);
    }

    public function apontamentos(): HasMany
    {
        return $this->hasMany(Apontamento::class);
    }

    public function impostos(): BelongsToMany
    {
        return $this->belongsToMany(Tax::class, 'fatura_tax')->withPivot('valor_imposto')->withTimestamps();
    }
}