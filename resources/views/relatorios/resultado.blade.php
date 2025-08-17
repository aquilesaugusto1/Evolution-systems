<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            <div>
                <h2 class="font-semibold text-2xl text-gray-800 leading-tight">Relatório de Apontamentos</h2>
                <p class="text-sm text-gray-500">Período de {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}</p>
            </div>

            <!-- KPIs -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-indigo-500 text-white p-6 rounded-lg shadow-lg">
                    <p class="text-sm font-medium text-indigo-100">Total de Horas Aprovadas</p>
                    <p class="mt-1 text-4xl font-bold">{{ number_format($kpis['total_horas'], 1) }}h</p>
                </div>
                <div class="bg-blue-500 text-white p-6 rounded-lg shadow-lg">
                    <p class="text-sm font-medium text-blue-100">Total de Apontamentos</p>
                    <p class="mt-1 text-4xl font-bold">{{ $kpis['total_apontamentos'] }}</p>
                </div>
                <div class="bg-purple-500 text-white p-6 rounded-lg shadow-lg">
                    <p class="text-sm font-medium text-purple-100">Média de Horas / Apontamento</p>
                    <p class="mt-1 text-4xl font-bold">{{ number_format($kpis['media_horas'], 1) }}h</p>
                </div>
            </div>

            <!-- Gráficos -->
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8">
                <div class="lg:col-span-3 bg-white p-6 rounded-xl shadow-md border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Horas Aprovadas por Cliente</h3>
                    <canvas id="clienteChart"></canvas>
                </div>
                <div class="lg:col-span-2 bg-white p-6 rounded-xl shadow-md border border-slate-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Distribuição de Horas por Consultor</h3>
                    <canvas id="consultorChart"></canvas>
                </div>
            </div>

            <!-- Tabela Detalhada -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-slate-800 mb-4">Todos os Apontamentos Aprovados</h3>
                    <table class="min-w-full divide-y divide-gray-200 text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600">Data</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600">Consultor</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600">Cliente</th>
                                <th class="px-4 py-2 text-left font-semibold text-slate-600">Assunto</th>
                                <th class="px-4 py-2 text-right font-semibold text-slate-600">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($apontamentos as $apontamento)
                                <tr>
                                    <td class="px-4 py-2">{{ $apontamento->data_apontamento->format('d/m/Y') }}</td>
                                    <td class="px-4 py-2">{{ $apontamento->consultor->nome ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $apontamento->contrato->cliente->nome_empresa ?? 'N/A' }}</td>
                                    <td class="px-4 py-2">{{ $apontamento->agenda->assunto ?? 'N/A' }}</td>
                                    <td class="px-4 py-2 text-right font-bold">{{ number_format($apontamento->horas_gastas, 1) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-slate-500">Nenhum apontamento aprovado encontrado para os filtros selecionados.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
             <div class="flex items-center justify-end mt-6">
                <a href="{{ route('relatorios.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-800">Gerar Novo Relatório</a>
            </div>
        </div>
    </div>
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            // Gráfico de Horas por Cliente
            const clienteCtx = document.getElementById('clienteChart');
            new Chart(clienteCtx, {
                type: 'bar',
                data: {
                    labels: @json($horasPorCliente->keys()),
                    datasets: [{
                        label: 'Horas Aprovadas',
                        data: @json($horasPorCliente->values()),
                        backgroundColor: 'rgba(79, 70, 229, 0.8)',
                    }]
                },
                options: { indexAxis: 'y', responsive: true, plugins: { legend: { display: false } } }
            });

            // Gráfico de Distribuição por Consultor
            const consultorCtx = document.getElementById('consultorChart');
            new Chart(consultorCtx, {
                type: 'doughnut',
                data: {
                    labels: @json($horasPorConsultor->keys()),
                    datasets: [{
                        label: 'Horas',
                        data: @json($horasPorConsultor->values()),
                        backgroundColor: ['#4f46e5', '#6366f1', '#818cf8', '#a5b4fc', '#c7d2fe'],
                    }]
                },
                options: { responsive: true, plugins: { legend: { position: 'top' } } }
            });
        </script>
    @endpush
</x-app-layout>
