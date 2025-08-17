<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class VerificarTermoAceite
{
    /**
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        if ($user && ! $user->termos_aceite_em) {
            // To prevent infinite redirect loops, we must ensure we are not already on the acceptance page.
            if (! $request->routeIs(['termo.aceite', 'termo.accept', 'logout'])) {
                return redirect()->route('termo.aceite');
            }
        }

        return $next($request);
    }
}
