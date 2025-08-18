<div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Volume de Agendas Mensais por Status</h2>
    <canvas id="agendasChart"></canvas>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ctx = document.getElementById('agendasChart');
            if (ctx) {
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: @json($widgetData['chartLabels'] ?? []),
                        datasets: [
                            {
                                label: 'Realizadas',
                                data: @json($widgetData['chartRealizadas'] ?? []),
                                backgroundColor: 'rgba(34, 197, 94, 0.8)',
                            },
                            {
                                label: 'Agendadas',
                                data: @json($widgetData['chartAgendadas'] ?? []),
                                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                            },
                            {
                                label: 'Canceladas',
                                data: @json($widgetData['chartCanceladas'] ?? []),
                                backgroundColor: 'rgba(239, 68, 68, 0.8)',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            x: { stacked: true },
                            y: { stacked: true, beginAtZero: true }
                        },
                        plugins: {
                            legend: { position: 'top' },
                            tooltip: { mode: 'index', intersect: false },
                        },
                        interaction: { mode: 'index', intersect: false }
                    }
                });
            }
        });
    </script>
@endpush
