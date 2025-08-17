<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use LogicException;

class TermoAceiteController extends Controller
{
    public function show(): View|RedirectResponse
    {
        $user = Auth::user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        if ($user->termos_aceite_em) {
            return redirect()->route('dashboard');
        }

        return view('auth.termo-aceite');
    }

    public function accept(Request $request): RedirectResponse
    {
        $request->validate([
            'aceite' => 'required|accepted',
        ]);

        $user = Auth::user();
        if (! $user) {
            throw new LogicException('User not authenticated.');
        }

        $user->termos_aceite_em = now();
        $user->ip_aceite = $request->ip();
        $user->save();

        return redirect()->route('dashboard');
    }
}
