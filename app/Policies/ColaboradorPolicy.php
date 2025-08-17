<?php

namespace App\Policies;

use App\Models\User;

class ColaboradorPolicy
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

    public function view(User $user, User $model): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, User $model): bool
    {
        return false;
    }

    public function delete(User $user, User $model): bool
    {
        return false;
    }

    public function toggleStatus(User $user, User $model): bool
    {
        return false;
    }
}
