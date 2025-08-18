<?php

namespace App\Models;

use App\Traits\Userstamps;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * @method static UserFactory factory(...$parameters)
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, Userstamps;

    protected $table = 'usuarios';

    protected $fillable = [
        'nome',
        'sobrenome',
        'email',
        'email_totvs_partner',
        'password',
        'status',
        'funcao',
        'tipo_contrato',
        'data_nascimento',
        'nacionalidade',
        'naturalidade',
        'endereco',
        'cargo',
        'nivel',
        'dados_empresa_prestador',
        'dados_bancarios',
        'termos_aceite_em',
        'ip_aceite',
        'created_by',
        'updated_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'endereco' => 'array',
            'dados_empresa_prestador' => 'array',
            'dados_bancarios' => 'array',
            'data_nascimento' => 'date',
            'termos_aceite_em' => 'datetime',
        ];
    }

    /**
     * @return HasOne<Consultor, User>
     */
    public function consultor(): HasOne
    {
        return $this->hasOne(Consultor::class, 'usuario_id');
    }

    /**
     * @return BelongsToMany<User, User>
     */
    public function techLeads(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'colaborador_tech_lead', 'consultor_id', 'tech_lead_id');
    }

    /**
     * @return BelongsToMany<User, User>
     */
    public function consultoresLiderados(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'colaborador_tech_lead', 'tech_lead_id', 'consultor_id');
    }

    /**
     * @return HasMany<Apontamento, User>
     */
    public function apontamentos(): HasMany
    {
        return $this->hasMany(Apontamento::class, 'consultor_id');
    }

    /**
     * @return BelongsToMany<Contrato, User>
     */
    public function contratos(): BelongsToMany
    {
        return $this->belongsToMany(Contrato::class, 'contrato_usuario', 'usuario_id', 'contrato_id')
            ->withPivot('funcao_contrato')
            ->withTimestamps();
    }

    public function isAdmin(): bool
    {
        return $this->funcao === 'admin';
    }

    public function isCoordenador(): bool
    {
        return str_contains((string) $this->funcao, 'coordenador');
    }

    public function isTechLead(): bool
    {
        return $this->funcao === 'techlead';
    }

    public function isConsultor(): bool
    {
        return $this->funcao === 'consultor';
    }

    /**
     * @return HasMany<Fatura>
     */
    public function faturasCriadas(): HasMany
    {
        return $this->hasMany(Fatura::class, 'created_by');
    }

    /**
     * @return HasOne<DashboardPreference, User>
     */
    public function dashboardPreference(): HasOne
    {
        return $this->hasOne(DashboardPreference::class);
    }
}
