<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            Relatório: Histórico de Tech Leads
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
            {{-- Seção de Filtros --}}
            <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                <h3 class="text-lg font-medium text-gray-900">Filtros do Relatório</h3>
                <form action="{{ route('relatorios.gerar') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="tipo_relatorio" value="historico-techleads">
                    
                    {{-- Campo de seleção de Contrato --}}
                    <div>
                        <x-input-label for="contrato_id" value="Selecione o Contrato" />
                        <select name="contrato_id" id="contrato_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" required>
                            <option value="">-- Selecione um contrato --</option>
                            @foreach($contratos as $contratoItem)
                                <option value="{{ $contratoItem->id }}" {{ (isset($filtros['contrato_id']) && $filtros['contrato_id'] == $contratoItem->id) ? 'selected' : '' }}>
                                    {{-- CORREÇÃO APLICADA AQUI --}}
                                    {{ $contratoItem->numero_contrato }} - {{ $contratoItem->empresaParceira->nome_fantasia ?? $contratoItem->empresaParceira->nome_empresa }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Botões de Geração --}}
                    <div class="flex items-center gap-4">
                        <x-primary-button type="submit" name="formato" value="html">{{ __('Gerar Relatório') }}</x-primary-button>
                        <x-primary-button type="submit" name="formato" value="pdf" class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:ring-red-500">{{ __('Gerar PDF') }}</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- Seção de Resultados (só aparece se o relatório for gerado) --}}
            @if(isset($historico))
                <div class="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                    <h3 class="mb-4 text-lg font-medium text-gray-900">Resultado do Relatório</h3>
                    <div class="mb-4">
                        <p><strong>Contrato:</strong> {{ $contrato->numero_contrato }}</p>
                        <p><strong>Cliente:</strong> {{ $contrato->empresaParceira->nome_empresa }}</p>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nome do Tech Lead</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Data de Início</th>
                                    <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Data de Fim</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($historico as $entrada)
                                    <tr>
                                        <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">{{ $entrada->tech_lead_nome }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">{{ \Carbon\Carbon::parse($entrada->data_inicio)->format('d/m/Y') }}</td>
                                        <td class="whitespace-nowrap px-6 py-4 text-center text-sm text-gray-500">
                                            {{ $entrada->data_fim ? \Carbon\Carbon::parse($entrada->data_fim)->format('d/m/Y') : 'Atual' }}
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500">
                                            Nenhum histórico de Tech Leads encontrado para este contrato.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
