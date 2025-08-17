<?php

namespace App\Http\Controllers;

use App\Models\Contrato;
use App\Models\EmpresaParceira;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ContratoController extends Controller
{
    public function index(Request $request): View
    {
        $this->authorize('viewAny', Contrato::class);
        $query = Contrato::with(['empresaParceira', 'usuarios']);
        if ($request->filled('cliente_id')) {
            $query->where('cliente_id', $request->input('cliente_id'));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }
        $contratos = $query->latest()->paginate(10)->withQueryString();
        $clientes = EmpresaParceira::where('status', 'Ativo')->get();

        return view('contratos.index', compact('contratos', 'clientes'));
    }

    public function create(): View
    {
        $this->authorize('create', Contrato::class);
        $contrato = new Contrato();
        $clientes = EmpresaParceira::where('status', 'Ativo')->orderBy('nome_empresa')->get();
        $coordenadores = User::whereIn('funcao', ['coordenador_operacoes', 'coordenador_tecnico'])->where('status', 'Ativo')->orderBy('nome')->get();
        $techLeads = User::where('funcao', 'techlead')->where('status', 'Ativo')->orderBy('nome')->get();
        $consultores = User::where('funcao', 'consultor')->where('status', 'Ativo')->orderBy('nome')->get();

        return view('contratos.create', compact('contrato', 'clientes', 'coordenadores', 'techLeads', 'consultores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Contrato::class);
        $validatedData = $request->validate($this->getValidationRules());

        DB::transaction(function () use ($request, $validatedData) {
            $preparedData = $this->prepareData($request, $validatedData);

            if (isset($preparedData['baseline_horas_mes'])) {
                $preparedData['baseline_horas_original'] = $preparedData['baseline_horas_mes'];
            }

            if ($request->hasFile('documento_baseline')) {
                $path = $request->file('documento_baseline')->store('contratos/baseline_docs', 'public');
                if (is_string($path)) {
                    $preparedData['documento_baseline_path'] = $path;
                }
            }

            $contrato = Contrato::create($preparedData);
            $this->syncUsuarios($contrato, $request);
            $techLeadsNovos = $validatedData['tech_leads'] ?? [];
            $this->atualizarHistoricoTechLeads($contrato, [], $techLeadsNovos);
        });

        return redirect()->route('contratos.index')->with('success', 'Contrato criado com sucesso.');
    }

    public function show(Contrato $contrato): View
    {
        $this->authorize('view', $contrato);
        $contrato->load(['empresaParceira', 'usuarios', 'creator', 'updater']);

        return view('contratos.show', compact('contrato'));
    }

    public function edit(Contrato $contrato): View
    {
        $this->authorize('update', $contrato);
        $contrato->load(['usuarios']);
        $clientes = EmpresaParceira::where('status', 'Ativo')->orderBy('nome_empresa')->get();
        $coordenadores = User::whereIn('funcao', ['coordenador_operacoes', 'coordenador_tecnico'])->where('status', 'Ativo')->orderBy('nome')->get();
        $techLeads = User::where('funcao', 'techlead')->where('status', 'Ativo')->orderBy('nome')->get();
        $consultores = User::where('funcao', 'consultor')->where('status', 'Ativo')->orderBy('nome')->get();

        return view('contratos.edit', compact('contrato', 'clientes', 'coordenadores', 'techLeads', 'consultores'));
    }

    public function update(Request $request, Contrato $contrato): RedirectResponse
    {
        $this->authorize('update', $contrato);
        $validatedData = $request->validate($this->getValidationRules($contrato->id));

        DB::transaction(function () use ($request, $contrato, $validatedData) {
            /** @var array<int, int|string> $techLeadsAntigos */
            $techLeadsAntigos = $contrato->techLeads()->pluck('usuarios.id')->toArray();

            $preparedData = $this->prepareData($request, $validatedData);

            if ($request->hasFile('documento_baseline')) {
                if ($contrato->documento_baseline_path) {
                    Storage::disk('public')->delete($contrato->documento_baseline_path);
                }
                $path = $request->file('documento_baseline')->store('contratos/baseline_docs', 'public');
                if (is_string($path)) {
                    $preparedData['documento_baseline_path'] = $path;
                }
            }

            $contrato->update($preparedData);
            $this->syncUsuarios($contrato, $request);
            $techLeadsNovos = $validatedData['tech_leads'] ?? [];
            $this->atualizarHistoricoTechLeads($contrato, $techLeadsAntigos, $techLeadsNovos);
        });

        return redirect()->route('contratos.index')->with('success', 'Contrato atualizado com sucesso.');
    }

    public function toggleStatus(Contrato $contrato): RedirectResponse
    {
        $this->authorize('toggleStatus', $contrato);
        $novoStatus = $contrato->status === 'Ativo' ? 'Inativo' : 'Ativo';
        $contrato->update(['status' => $novoStatus]);
        $mensagem = $novoStatus === 'Ativo' ? 'Contrato ativado com sucesso.' : 'Contrato desabilitado com sucesso.';

        return back()->with('success', $mensagem);
    }

    /**
     * @return array<string, mixed>
     */
    private function getValidationRules(?int $id = null): array
    {
        $permiteAntecipar = request()->input('permite_antecipar_baseline');

        return [
            'cliente_id' => ['required', 'exists:empresas_parceiras,id'],
            'numero_contrato' => ['nullable', 'string', 'max:255', Rule::unique('contratos')->ignore($id)],
            'tipo_contrato' => ['required', 'string'],
            'produtos' => ['required', 'array'],
            'produtos.*' => ['string'],
            'especifique_outro' => ['nullable', 'string', 'max:255', 'required_if:produtos.*,Outro'],
            'status' => ['required', 'string'],
            'data_inicio' => ['required', 'date'],
            'data_termino' => ['nullable', 'date', 'after_or_equal:data_inicio'],
            'contato_principal' => ['nullable', 'string', 'max:255'],
            'baseline_horas_mes' => ['nullable', 'numeric', 'min:0'],
            'permite_antecipar_baseline' => ['nullable', 'boolean'],
            'possui_engenharia_valores' => ['nullable', 'boolean'], // Adicionado
            'documento_baseline' => [
                'nullable',
                Rule::requiredIf($permiteAntecipar == '1' || $permiteAntecipar === true),
                'file',
                'mimes:pdf,doc,docx,jpg,jpeg,png',
                'max:2048',
            ],
            'coordenadores' => ['nullable', 'array'],
            'coordenadores.*' => ['exists:usuarios,id'],
            'tech_leads' => ['nullable', 'array'],
            'tech_leads.*' => ['exists:usuarios,id'],
            'consultores' => ['nullable', 'array'],
            'consultores.*' => ['exists:usuarios,id'],
        ];
    }

    /**
     * @param  array<string, mixed>  $validatedData
     * @return array<string, mixed>
     */
    private function prepareData(Request $request, array $validatedData): array
    {
        $validatedData['permite_antecipar_baseline'] = $request->boolean('permite_antecipar_baseline');
        $validatedData['possui_engenharia_valores'] = $request->boolean('possui_engenharia_valores'); // Adicionado

        /** @var array<int, string> $produtos */
        $produtos = $validatedData['produtos'];
        if (! in_array('Outro', $produtos)) {
            $validatedData['especifique_outro'] = null;
        }

        return $validatedData;
    }

    private function syncUsuarios(Contrato $contrato, Request $request): void
    {
        $contrato->usuarios()->detach();
        $coordenadores = (array) $request->input('coordenadores', []);
        foreach ($coordenadores as $id) {
            $contrato->usuarios()->attach($id, ['funcao_contrato' => 'coordenador']);
        }

        $techLeads = (array) $request->input('tech_leads', []);
        foreach ($techLeads as $id) {
            $contrato->usuarios()->attach($id, ['funcao_contrato' => 'tech_lead']);
        }

        $consultores = (array) $request->input('consultores', []);
        foreach ($consultores as $id) {
            $contrato->usuarios()->attach($id, ['funcao_contrato' => 'consultor']);
        }
        $contrato->touch();
    }

    /**
     * @param  array<int, int|string>  $techLeadsAntigos
     * @param  array<int, int|string>  $techLeadsNovos
     */
    private function atualizarHistoricoTechLeads(Contrato $contrato, array $techLeadsAntigos, array $techLeadsNovos): void
    {
        $hoje = now();
        $userId = Auth::id();

        $removidos = array_diff($techLeadsAntigos, $techLeadsNovos);
        $adicionados = array_diff($techLeadsNovos, $techLeadsAntigos);

        if (! empty($removidos)) {
            DB::table('contrato_historico_techleads')
                ->where('contrato_id', $contrato->id)
                ->whereIn('tech_lead_id', $removidos)
                ->whereNull('data_fim')
                ->update(['data_fim' => $hoje]);
        }

        foreach ($adicionados as $techLeadId) {
            DB::table('contrato_historico_techleads')->updateOrInsert(
                [
                    'contrato_id' => $contrato->id,
                    'tech_lead_id' => $techLeadId,
                    'data_fim' => null,
                ],
                [
                    'data_inicio' => $hoje,
                    'user_creator_id' => $userId,
                    'created_at' => $hoje,
                    'updated_at' => $hoje,
                ]
            );
        }
    }
}
