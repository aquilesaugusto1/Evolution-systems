<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-sm border border-slate-200 p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{ $contrato->empresaParceira->nome_empresa }}</h1>
                        <span class="text-sm text-slate-500">Contrato #{{ $contrato->numero_contrato ?? $contrato->id }}</span>
                    </div>
                    <div class="px-3 py-1 text-xs font-semibold rounded-full {{ $contrato->status === 'Ativo' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ $contrato->status }}
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Detalhes Principais</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Tipo de Contrato:</span>
                                <span class="text-slate-800">{{ $contrato->tipo_contrato }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Contato Principal:</span>
                                <span class="text-slate-800">{{ $contrato->contato_principal ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Data de Início:</span>
                                <span class="text-slate-800">{{ $contrato->data_inicio->format('d/m/Y') }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Data de Término:</span>
                                <span class="text-slate-800">{{ $contrato->data_termino ? $contrato->data_termino->format('d/m/Y') : 'Indeterminado' }}</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Escopo e Financeiro</h3>
                        
                        @if($contrato->baseline_horas_original)
                            @php
                                $horasOriginais = $contrato->baseline_horas_original;
                                $horasRestantes = $contrato->baseline_horas_mes;
                                $horasConsumidas = $horasOriginais - $horasRestantes;
                                $percentualConsumido = $horasOriginais > 0 ? ($horasConsumidas / $horasOriginais) * 100 : 0;
                            @endphp
                            <div class="mb-4">
                                <div class="flex justify-between mb-1">
                                    <span class="text-base font-medium text-blue-700">Consumo de Horas</span>
                                    <span class="text-sm font-medium text-blue-700">{{ number_format($percentualConsumido, 1) }}%</span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentualConsumido }}%"></div>
                                </div>
                            </div>
                        @endif

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Baseline Original:</span>
                                <span class="text-slate-800 font-semibold">{{ $contrato->baseline_horas_original ? number_format($contrato->baseline_horas_original, 2) . 'h' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Saldo de Horas:</span>
                                <span class="text-slate-800 font-bold text-emerald-600">{{ $contrato->baseline_horas_mes ? number_format($contrato->baseline_horas_mes, 2) . 'h' : 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Permite Antecipar Baseline:</span>
                                <span class="text-slate-800">{{ $contrato->permite_antecipar_baseline ? 'Sim' : 'Não' }}</span>
                            </div>
                            <!-- CAMPO ADICIONADO -->
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Possui Engenharia de Valores:</span>
                                <span class="text-slate-800">{{ $contrato->possui_engenharia_valores ? 'Sim' : 'Não' }}</span>
                            </div>
                            @if($contrato->documento_baseline_path)
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Documento de Comprovação:</span>
                                <a href="{{ Storage::url($contrato->documento_baseline_path) }}" target="_blank" class="text-indigo-600 hover:underline">
                                    Visualizar
                                </a>
                            </div>
                            @endif
                            <div class="md:col-span-2">
                                <span class="font-medium text-slate-600">Produtos Contratados:</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @foreach($contrato->produtos as $produto)
                                        <span class="px-2 py-1 bg-indigo-100 text-indigo-800 text-xs font-semibold rounded-full">{{ $produto }}</span>
                                    @endforeach
                                    @if($contrato->especifique_outro)
                                        <span class="px-2 py-1 bg-slate-100 text-slate-800 text-xs font-semibold rounded-full">{{ $contrato->especifique_outro }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Equipe do Contrato</h3>
                        <div class="space-y-4 text-sm">
                            <div>
                                <span class="font-medium text-slate-600">Coordenador(es):</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse($contrato->coordenadores as $coordenador)
                                        <span class="px-2 py-1 bg-sky-100 text-sky-800 text-xs font-semibold rounded-full">{{ $coordenador->nome }}</span>
                                    @empty
                                        <span class="text-slate-500">Nenhum coordenador associado.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Tech Lead(s):</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse($contrato->techLeads as $techLead)
                                        <span class="px-2 py-1 bg-purple-100 text-purple-800 text-xs font-semibold rounded-full">{{ $techLead->nome }}</span>
                                    @empty
                                        <span class="text-slate-500">Nenhum Tech Lead associado.</span>
                                    @endforelse
                                </div>
                            </div>
                            <div>
                                <span class="font-medium text-slate-600">Consultor(es):</span>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse($contrato->consultores as $consultor)
                                        <span class="px-2 py-1 bg-gray-100 text-gray-800 text-xs font-semibold rounded-full">{{ $consultor->nome }}</span>
                                    @empty
                                        <span class="text-slate-500">Nenhum consultor associado.</span>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Informações de Auditoria</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-slate-600">Criado por</dt>
                                <dd class="mt-1 text-slate-800">
                                    {{ $contrato->creator->nome ?? 'Sistema' }} em {{ $contrato->created_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="font-medium text-slate-600">Última Atualização por</dt>
                                <dd class="mt-1 text-slate-800">
                                    {{ $contrato->updater->nome ?? 'Sistema' }} em {{ $contrato->updated_at->format('d/m/Y H:i') }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>

                <div class="flex items-center justify-end mt-8 pt-5 border-t border-slate-200">
                    <a href="{{ route('contratos.index') }}" class="btn bg-white border-slate-200 hover:border-slate-300 text-slate-600">Voltar para a Lista</a>
                    @can('update', $contrato)
                        <a href="{{ route('contratos.edit', $contrato) }}" class="btn bg-indigo-500 hover:bg-indigo-600 text-white ml-3">Editar Contrato</a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
