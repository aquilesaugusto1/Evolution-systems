<?php

namespace App\Policies;

use App\Models\Contrato;
use App\Models\User;

class ContratoPolicy
{
    public function before(User $user, string $ability): ?bool
    {
        if ($user->funcao === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Contrato $contrato): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico']);
    }

    public function update(User $user, Contrato $contrato): bool
    {
        return in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico']);
    }

    public function delete(User $user, Contrato $contrato): bool
    {
        return in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico']);
    }

    public function toggleStatus(User $user, Contrato $contrato): bool
    {
        return in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico']);
    }
}
