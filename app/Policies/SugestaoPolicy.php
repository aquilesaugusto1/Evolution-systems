<?php

namespace App\Policies;

use App\Models\Sugestao;
use App\Models\User;

class SugestaoPolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Sugestao $sugestao): bool
    {
        // Garante que apenas utilizadores com a função 'admin' podem atualizar.
        return $user->isAdmin();
    }
}
