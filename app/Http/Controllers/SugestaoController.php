<?php

namespace App\Http\Controllers;

use App\Models\Sugestao;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SugestaoController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Sugestao::class);

        $query = Sugestao::with('usuario')->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $sugestoes = $query->paginate(9)->withQueryString();

        return view('sugestoes.index', compact('sugestoes'));
    }

    public function create(): View
    {
        $this->authorize('create', Sugestao::class);

        return view('sugestoes.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Sugestao::class);
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
        ]);

        Sugestao::create([
            'titulo' => $request->string('titulo'),
            'descricao' => $request->string('descricao'),
            'status' => 'Pendente',
            'usuario_id' => Auth::id(),
        ]);

        return redirect()->route('sugestoes.index')->with('success', 'Sugestão enviada com sucesso!');
    }

    public function update(Request $request, Sugestao $sugestao): RedirectResponse
    {
        $this->authorize('update', $sugestao);
        $request->validate([
            'status' => 'required|string|in:Pendente,Em Análise,Concluída,Rejeitada',
        ]);

        $sugestao->update(['status' => $request->string('status')]);

        return back()->with('success', 'Status da sugestão atualizado.');
    }
}
