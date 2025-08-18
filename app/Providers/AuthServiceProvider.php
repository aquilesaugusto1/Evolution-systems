<?php

namespace App\Providers;

use App\Models\Agenda;
use App\Models\Apontamento;
use App\Models\Contrato;
use App\Models\EmpresaParceira;
use App\Models\Fatura;
use App\Models\User;
use App\Policies\AgendaPolicy;
use App\Policies\ApontamentoPolicy;
use App\Policies\ColaboradorPolicy;
use App\Policies\ContratoPolicy;
use App\Policies\EmpresaParceiraPolicy;
use App\Policies\FaturaPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        EmpresaParceira::class => EmpresaParceiraPolicy::class,
        Contrato::class => ContratoPolicy::class,
        User::class => ColaboradorPolicy::class,
        Agenda::class => AgendaPolicy::class,
        Apontamento::class => ApontamentoPolicy::class,
        Fatura::class => FaturaPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();
    }
}
