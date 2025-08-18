<?php

namespace App\Policies;

use App\Models\Fatura;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FaturaPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Fatura $fatura): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Fatura $fatura): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Fatura $fatura): bool
    {
         return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }
}
