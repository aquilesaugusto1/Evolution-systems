<?php

namespace App\Http\Controllers;

use App\Models\EmpresaParceira;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EmpresaParceiraController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', EmpresaParceira::class);

        $query = EmpresaParceira::query();

        if ($request->filled('nome_empresa')) {
            $query->where('nome_empresa', 'like', '%'.$request->string('nome_empresa')->toString().'%');
        }

        if ($request->filled('cnpj')) {
            $query->where('cnpj', 'like', '%'.$request->string('cnpj')->toString().'%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }

        $empresas = $query->latest()->paginate(10)->withQueryString();

        return view('empresas.index', compact('empresas'));
    }

    public function create(): View
    {
        $this->authorize('create', EmpresaParceira::class);

        return view('empresas.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', EmpresaParceira::class);
        $validated = $request->validate($this->validationRules());
        EmpresaParceira::create($validated);

        return redirect()->route('empresas.index')->with('success', 'Cliente cadastrado com sucesso.');
    }

    public function show(EmpresaParceira $empresa): View
    {
        $this->authorize('view', $empresa);

        return view('empresas.show', compact('empresa'));
    }

    public function edit(EmpresaParceira $empresa): View
    {
        $this->authorize('update', $empresa);

        return view('empresas.edit', compact('empresa'));
    }

    public function update(Request $request, EmpresaParceira $empresa): RedirectResponse
    {
        $this->authorize('update', $empresa);
        $validated = $request->validate($this->validationRules($empresa->id));
        $empresa->update($validated);

        return redirect()->route('empresas.index')->with('success', 'Cliente atualizado com sucesso.');
    }

    public function toggleStatus(EmpresaParceira $empresa): RedirectResponse
    {
        $this->authorize('toggleStatus', $empresa);
        $novoStatus = $empresa->status === 'Ativo' ? 'Inativo' : 'Ativo';
        $empresa->update(['status' => $novoStatus]);
        $mensagem = $novoStatus === 'Ativo' ? 'Cliente habilitado com sucesso.' : 'Cliente desabilitado com sucesso.';

        return redirect()->route('empresas.index')->with('success', $mensagem);
    }

    /**
     * @return array<string, mixed>
     */
    private function validationRules(?int $id = null): array
    {
        return [
            'nome_empresa' => 'required|string|max:255',
            'cnpj' => 'required|string|max:18|unique:empresas_parceiras,cnpj,'.$id,
            'saldo_horas' => 'nullable|numeric|min:0',
            'status' => 'required|string|in:Ativo,Inativo',
            'endereco_completo.logradouro' => 'nullable|string|max:255',
            'endereco_completo.numero' => 'nullable|string|max:20',
            'endereco_completo.complemento' => 'nullable|string|max:255',
            'endereco_completo.bairro' => 'nullable|string|max:255',
            'endereco_completo.cidade' => 'nullable|string|max:255',
            'endereco_completo.uf' => 'nullable|string|max:2',
            'endereco_completo.cep' => 'nullable|string|max:9',
            'contato_principal.nome' => 'nullable|string|max:255',
            'contato_principal.email' => 'nullable|email|max:255',
            'contato_principal.telefone' => 'nullable|string|max:20',
            'contato_comercial.nome' => 'nullable|string|max:255',
            'contato_comercial.email' => 'nullable|email|max:255',
            'contato_comercial.telefone' => 'nullable|string|max:20',
            'contato_financeiro.nome' => 'nullable|string|max:255',
            'contato_financeiro.email' => 'nullable|email|max:255',
            'contato_financeiro.telefone' => 'nullable|string|max:20',
            'contato_tecnico.nome' => 'nullable|string|max:255',
            'contato_tecnico.email' => 'nullable|email|max:255',
            'contato_tecnico.telefone' => 'nullable|string|max:20',
        ];
    }
}
