<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Relatório de Alocação de Consultores
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <h3 class="text-lg font-medium text-gray-900">Filtros do Relatório</h3>
                <form action="{{ route('relatorios.gerar') }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    <input type="hidden" name="tipo_relatorio" value="alocacao-consultores">
                    
                    <div>
                        <x-input-label for="data_inicio" value="Período de Análise" />
                        <div class="flex items-center space-x-2 mt-1">
                            <x-text-input id="data_inicio" name="data_inicio" type="date" class="block w-full" :value="isset($filtros['data_inicio']) ? $filtros['data_inicio'] : now()->startOfMonth()->format('Y-m-d')" required />
                            <span class="text-gray-500">até</span>
                            <x-text-input id="data_fim" name="data_fim" type="date" class="block w-full" :value="isset($filtros['data_fim']) ? $filtros['data_fim'] : now()->endOfMonth()->format('Y-m-d')" required />
                        </div>
                        <div class="flex space-x-2 mt-2">
                            {{-- ATUALIZAÇÃO: Adicionado o botão "Próximo Mês" --}}
                            <x-secondary-button type="button" id="btnMesPassado">Mês Passado</x-secondary-button>
                            <x-secondary-button type="button" id="btnEsteMes">Este Mês</x-secondary-button>
                            <x-secondary-button type="button" id="btnProximoMes">Próximo Mês</x-secondary-button>
                        </div>
                    </div>

                    <div>
                        <x-input-label value="Consultores (PJ)" />
                        <div class="mt-2 border rounded-md p-4 max-h-60 overflow-y-auto space-y-2">
                            <div class="flex items-center">
                                <input type="checkbox" id="selecionar-todos" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                                <label for="selecionar-todos" class="ml-2 text-sm font-medium text-gray-900">Selecionar Todos</label>
                            </div>
                            <hr>
                            @foreach($consultores as $consultor)
                                <div class="flex items-center">
                                    <input type="checkbox" name="consultores_id[]" value="{{ $consultor->id }}" id="consultor_{{ $consultor->id }}" 
                                           class="consultor-checkbox rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
                                           {{ (isset($filtros['consultores_id']) && in_array($consultor->id, $filtros['consultores_id'])) ? 'checked' : '' }}>
                                    <label for="consultor_{{ $consultor->id }}" class="ml-2 text-sm text-gray-700">{{ $consultor->nome }}</label>
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
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Resultados da Alocação</h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Período de <strong>{{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }}</strong> a <strong>{{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}</strong> 
                        contém <strong>{{ $dias_uteis }} dias úteis</strong>.
                    </p>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Apontadas</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nº de Agendas</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Úteis no Período</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Úteis Restantes</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($resultados as $resultado)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $resultado['consultor']->nome }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($resultado['horas_apontadas'], 2, ',', '.') }}h</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $resultado['numero_agendas'] }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ number_format($resultado['horas_uteis_periodo'], 2, ',', '.') }}h</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right @if($resultado['horas_uteis_restantes'] < 0) text-red-600 @else text-green-600 @endif">
                                        {{ number_format($resultado['horas_uteis_restantes'], 2, ',', '.') }}h
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum resultado encontrado.</td>
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
            const dataInicio = document.getElementById('data_inicio');
            const dataFim = document.getElementById('data_fim');

            // Função para formatar a data para YYYY-MM-DD
            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }

            document.getElementById('btnEsteMes').addEventListener('click', function () {
                const hoje = new Date();
                dataInicio.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth(), 1));
                dataFim.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth() + 1, 0));
            });

            document.getElementById('btnMesPassado').addEventListener('click', function () {
                const hoje = new Date();
                dataInicio.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth() - 1, 1));
                dataFim.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth(), 0));
            });
            
            // ATUALIZAÇÃO: Lógica para o botão "Próximo Mês"
            document.getElementById('btnProximoMes').addEventListener('click', function () {
                const hoje = new Date();
                dataInicio.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth() + 1, 1));
                dataFim.value = formatDate(new Date(hoje.getFullYear(), hoje.getMonth() + 2, 0));
            });

            const selecionarTodos = document.getElementById('selecionar-todos');
            const checkboxes = document.querySelectorAll('.consultor-checkbox');
            selecionarTodos.addEventListener('change', function (e) {
                checkboxes.forEach(checkbox => {
                    checkbox.checked = e.target.checked;
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
