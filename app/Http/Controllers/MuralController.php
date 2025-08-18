<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MuralController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $podeVerSkills = $user->isAdmin() || $user->isCoordenador() || $user->isTechLead();

        $query = User::query()->where('status', 'ativo');

        if ($podeVerSkills) {
            $query->with(['skills' => function ($query) {
                $query->orderBy('categoria')->orderBy('nome');
            }]);
        }

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nome', 'like', "%{$searchTerm}%")
                  ->orWhere('sobrenome', 'like', "%{$searchTerm}%")
                  ->orWhere('cargo', 'like', "%{$searchTerm}%");
            });
        }

        if ($podeVerSkills && $request->filled('skill')) {
            $skillId = $request->input('skill');
            $nivel = $request->input('nivel');

            $query->whereHas('skills', function ($q) use ($skillId, $nivel) {
                $q->where('skills.id', $skillId);
                if ($nivel) {
                    $q->where('nivel', '>=', $nivel);
                }
            });
        }

        $usuarios = $query->orderBy('nome')->paginate(12);
        $skills = $podeVerSkills ? Skill::orderBy('categoria')->orderBy('nome')->get() : collect();

        return view('mural.index', [
            'usuarios' => $usuarios,
            'skills' => $skills,
            'podeVerSkills' => $podeVerSkills,
        ]);
    }
}
