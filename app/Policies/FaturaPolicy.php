<?php

namespace App\Policies;

use App\Models\Fatura;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class FaturaPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    public function view(User $user, Fatura $fatura): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    public function create(User $user): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    public function update(User $user, Fatura $fatura): bool
    {
        return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }

    public function delete(User $user, Fatura $fatura): bool
    {
         return in_array($user->funcao, ['admin', 'coordenador_operacoes']);
    }
}