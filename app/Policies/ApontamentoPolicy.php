<?php

namespace App\Policies;

use App\Models\Apontamento;
use App\Models\User;

class ApontamentoPolicy
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

    public function view(User $user, Apontamento $apontamento): bool
    {
        if (in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico'])) {
            return true;
        }

        if ($user->funcao === 'techlead') {
            return $user->consultoresLiderados()->where('usuarios.id', $apontamento->consultor_id)->exists();
        }

        return $user->id === $apontamento->consultor_id;
    }

    public function create(User $user): bool
    {
        // Apenas consultores podem criar apontamentos para si mesmos.
        // A lógica de quem pode criar para quem está no controller do calendário.
        return true;
    }

    public function update(User $user, Apontamento $apontamento): bool
    {
        // Permite a atualização se o usuário for o dono E o status for Pendente OU Rejeitado.
        return $user->id === $apontamento->consultor_id && in_array($apontamento->status, ['Pendente', 'Rejeitado']);
    }

    public function delete(User $user, Apontamento $apontamento): bool
    {
        return $user->id === $apontamento->consultor_id && in_array($apontamento->status, ['Pendente', 'Rejeitado']);
    }

    public function approve(User $user, Apontamento $apontamento): bool
    {
        if (in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico'])) {
            return true;
        }

        if ($user->funcao === 'techlead') {
            return $user->consultoresLiderados()->where('usuarios.id', $apontamento->consultor_id)->exists();
        }

        return false;
    }

    /**
     * Determine whether the user can view the approvals page link in the sidebar.
     */
    public function viewAprovacoes(User $user): bool
    {
        return in_array($user->funcao, ['coordenador_operacoes', 'coordenador_tecnico', 'techlead']);
    }
}
