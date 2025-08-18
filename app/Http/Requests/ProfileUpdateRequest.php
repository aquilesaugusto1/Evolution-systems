<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use LogicException;

class ProfileUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        $user = $this->user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        return [
            'nome' => ['required', 'string', 'max:255'],
            'sobrenome' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', Rule::unique(User::class)->ignore($user->id)],
            'foto_url' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'redes_sociais' => ['nullable', 'array'],
            'redes_sociais.linkedin' => ['nullable', 'url', 'max:255'],
            'redes_sociais.github' => ['nullable', 'url', 'max:255'],
        ];
    }
}
