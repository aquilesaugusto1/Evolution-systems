<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatório: Visão Geral de Contratos
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Filtros do Relatório</h3>
                <form action="{{ route('relatorios.gerar') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="tipo_relatorio" value="visao-geral-contratos">
                    
                    <div>
                        <x-input-label value="Selecione os Contratos Ativos" />
                        <div class="mt-2 border rounded-md p-4 max-h-72 overflow-y-auto space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="selecionar-todos-contratos" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="selecionar-todos-contratos" class="ml-2 text-sm font-medium text-gray-900">Selecionar Todos</label>
                            </div>
                            <hr>
                            @foreach($contratos as $contrato)
                                <div class="flex items-center">
                                    <input type="checkbox" name="contratos_id[]" value="{{ $contrato->id }}" id="contrato_{{ $contrato->id }}" 
                                           class="contrato-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ (isset($filtros['contratos_id']) && in_array($contrato->id, $filtros['contratos_id'])) ? 'checked' : '' }}>
                                    <label for="contrato_{{ $contrato->id }}" class="ml-2 text-sm text-gray-700">
                                        {{ $contrato->numero_contrato }} - <span class="font-semibold">{{ $contrato->empresaParceira->nome_empresa }}</span>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button type="submit" name="formato" value="html">{{ __('Gerar Relatório') }}</x-primary-button>
                        <x-primary-button type="submit" name="formato" value="pdf" class="bg-red-600 hover:bg-red-700 focus:bg-red-700 active:bg-red-800 focus:ring-red-500">{{ __('Gerar PDF') }}</x-primary-button>
                    </div>
                </form>
            </div>

            @if(isset($resultados))
                <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Resultados da Análise de Contratos</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contrato</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Originais</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo de Horas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consumo</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($resultados as $resultado)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $resultado['contrato']->numero_contrato }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $resultado['contrato']->empresaParceira->nome_empresa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($resultado['contrato']->baseline_horas_original, 2, ',', '.') }}h</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right @if($resultado['saldo_horas'] < ($resultado['contrato']->baseline_horas_original * 0.1)) text-red-600 @else text-green-600 @endif">
                                        {{ number_format($resultado['saldo_horas'], 2, ',', '.') }}h
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                                            <div class="bg-indigo-600 h-2.5 rounded-full" style="width: {{ $resultado['percentual_gasto'] }}%"></div>
                                        </div>
                                        <span class="text-xs">{{ $resultado['percentual_gasto'] }}%</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum contrato selecionado ou dados encontrados.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selecionarTodos = document.getElementById('selecionar-todos-contratos');
            const checkboxes = document.querySelectorAll('.contrato-checkbox');
            selecionarTodos.addEventListener('change', function (e) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
