<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class ColaboradorController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', User::class);

        $query = User::query();
        if ($request->filled('nome')) {
            $query->where('nome', 'like', '%'.$request->string('nome')->toString().'%');
        }
        if ($request->filled('funcao')) {
            $query->where('funcao', $request->string('funcao'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->string('status'));
        }
        $colaboradores = $query->latest()->paginate(10)->withQueryString();

        return view('colaboradores.index', compact('colaboradores'));
    }

    public function create(): View
    {
        $this->authorize('create', User::class);
        $colaborador = new User();
        $techLeads = User::where('funcao', 'techlead')->where('status', 'Ativo')->orderBy('nome')->get();

        return view('colaboradores.create', compact('colaborador', 'techLeads'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', User::class);
        $validated = $request->validate($this->getValidationRules($request));

        DB::transaction(function () use ($request, $validated) {
            $colaborador = User::create($this->getData($request, true));
            $techLeads = $validated['tech_leads'] ?? [];
            $colaborador->techLeads()->sync($techLeads);
        });

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador criado com sucesso.');
    }

    public function show(User $colaborador): View
    {
        $this->authorize('view', $colaborador);

        return view('colaboradores.show', compact('colaborador'));
    }

    public function edit(User $colaborador): View
    {
        $this->authorize('update', $colaborador);
        $techLeads = User::where('funcao', 'techlead')->where('status', 'Ativo')->orderBy('nome')->get();

        return view('colaboradores.edit', compact('colaborador', 'techLeads'));
    }

    public function update(Request $request, User $colaborador): RedirectResponse
    {
        $this->authorize('update', $colaborador);
        $validated = $request->validate($this->getValidationRules($request, $colaborador->id));

        DB::transaction(function () use ($request, $colaborador, $validated) {
            $colaborador->update($this->getData($request, false));
            $techLeads = $validated['tech_leads'] ?? [];
            $colaborador->techLeads()->sync($techLeads);
        });

        return redirect()->route('colaboradores.index')->with('success', 'Colaborador atualizado com sucesso.');
    }

    public function toggleStatus(User $colaborador): RedirectResponse
    {
        $this->authorize('toggleStatus', $colaborador);
        $novoStatus = $colaborador->status === 'Ativo' ? 'Inativo' : 'Ativo';
        $colaborador->update(['status' => $novoStatus]);
        $mensagem = $novoStatus === 'Ativo' ? 'Colaborador habilitado.' : 'Colaborador desabilitado.';

        return redirect()->route('colaboradores.index')->with('success', $mensagem);
    }

    /**
     * @return array<string, mixed>
     */
    private function getValidationRules(Request $request, ?int $id = null): array
    {
        $rules = [
            'nome' => ['required', 'string', 'max:255'],
            'sobrenome' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:usuarios,email,'.$id],
            'funcao' => ['required', 'string', 'in:consultor,techlead,administrativo,coordenador_operacoes,coordenador_tecnico,comercial'],
            'tipo_contrato' => ['nullable', 'string'],
            'data_nascimento' => ['nullable', 'date'],
            'nacionalidade' => ['nullable', 'string', 'max:255'],
            'naturalidade' => ['nullable', 'string', 'max:255'],
            'endereco.rua' => ['nullable', 'string', 'max:255'],
            'endereco.bairro' => ['nullable', 'string', 'max:255'],
            'endereco.cidade' => ['nullable', 'string', 'max:255'],
            'endereco.estado' => ['nullable', 'string', 'max:255'],
            'endereco.pais' => ['nullable', 'string', 'max:255'],
            'email_totvs_partner' => ['nullable', 'string', 'lowercase', 'email', 'max:255'],
            'status' => ['required', 'string'],
            'cargo' => ['nullable', 'string', 'max:255'],
            'nivel' => ['nullable', 'string'],
            'tech_leads' => ['nullable', 'array'],
            'tech_leads.*' => ['exists:usuarios,id'],
            'dados_empresa_prestador.razao_social' => ['nullable', 'string', 'max:255'],
            'dados_empresa_prestador.cnpj' => ['nullable', 'string', 'max:255'],
            'dados_bancarios.banco' => ['nullable', 'string', 'max:255'],
            'dados_bancarios.agencia' => ['nullable', 'string', 'max:255'],
            'dados_bancarios.conta' => ['nullable', 'string', 'max:255'],
        ];
        if (! $id || $request->filled('password')) {
            $rules['password'] = ['required', 'confirmed', Rules\Password::defaults()];
        }

        return $rules;
    }

    /**
     * @return array<string, mixed>
     */
    private function getData(Request $request, bool $isCreate = true): array
    {
        $data = $request->except(['_token', '_method', 'password_confirmation', 'tech_leads']);

        if (isset($data['funcao']) && $data['funcao'] === 'administrativo') {
            $data['funcao'] = 'admin';
        }

        if ($isCreate) {
            $data['password'] = Hash::make($request->string('password')->toString());
        } elseif ($request->filled('password')) {
            $data['password'] = Hash::make($request->string('password')->toString());
        } else {
            unset($data['password']);
        }

        $data['dados_empresa_prestador'] = in_array($request->input('tipo_contrato'), ['PJ Mensal', 'PJ Horista']) ? $request->input('dados_empresa_prestador') : null;

        return $data;
    }
}
