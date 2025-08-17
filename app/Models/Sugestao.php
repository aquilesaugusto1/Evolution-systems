<?php

namespace App\Models;

use Database\Factories\SugestaoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @method static SugestaoFactory factory(...$parameters)
 */
class Sugestao extends Model
{
    use HasFactory;

    /**
     * Informa ao Laravel o nome correto da tabela.
     */
    protected $table = 'sugestoes';

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'titulo',
        'descricao',
        'status',
        'usuario_id',
    ];

    /**
     * Get the user that owns the suggestion.
     * @return BelongsTo<User, Sugestao>
     */
    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}