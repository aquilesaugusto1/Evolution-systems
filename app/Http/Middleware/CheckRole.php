<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! auth()->check()) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $user = auth()->user();
        if (! $user) {
            abort(403, 'Acesso Não Autorizado.');
        }

        $userFuncao = $user->funcao;

        $techLeadLevelRoles = [
            'administrativo',
            'coordenador_operacoes',
            'coordenador_tecnico',
            'comercial',
        ];

        if (in_array($userFuncao, $techLeadLevelRoles)) {
            $userFuncao = 'techlead';
        }

        if (! in_array($userFuncao, $roles)) {
            abort(403, 'Acesso Não Autorizado.');
        }

        return $next($request);
    }
}
