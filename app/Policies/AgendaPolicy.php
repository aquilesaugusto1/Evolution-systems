<?php

namespace App\Policies;

use App\Models\Agenda;
use App\Models\User;

class AgendaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->isAdmin() || str_contains($user->funcao, 'coordenador') || $user->isTechLead() || $user->isConsultor();
    }

    public function view(User $user, Agenda $agenda): bool
    {
        if ($user->isAdmin() || str_contains($user->funcao, 'coordenador')) {
            return true;
        }

        if ($user->isTechLead()) {
            // A CORREÇÃO ESTÁ AQUI. Adicionamos 'usuarios.id'
            return $user->consultoresLiderados()->where('usuarios.id', $agenda->consultor_id)->exists();
        }

        if ($user->isConsultor()) {
            return $user->id === $agenda->consultor_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->isAdmin() || str_contains($user->funcao, 'coordenador') || $user->isTechLead();
    }

    public function update(User $user, Agenda $agenda): bool
    {
        // Esta função já reutiliza a lógica corrigida de 'view'
        return $this->view($user, $agenda);
    }

    public function delete(User $user, Agenda $agenda): bool
    {
        // Esta função já reutiliza a lógica corrigida de 'view'
        return $this->view($user, $agenda);
    }
}
