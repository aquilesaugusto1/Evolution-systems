<div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
    <h3 class="text-lg font-bold text-slate-800 mb-3">Consultores Mais Ativos (Últimos 30 dias)</h3>
    <ul class="space-y-3">
        @forelse($widgetData as $consultor)
            <li class="flex justify-between items-center text-sm">
                <span class="font-medium text-slate-700">{{ $consultor->nome }}</span>
                <span class="font-bold text-indigo-600">{{ $consultor->apontamentos_sum_horas_gastas }}h</span>
            </li>
        @empty
            <p class="text-sm text-slate-500">Sem apontamentos nos últimos 30 dias.</p>
        @endforelse
    </ul>
</div>
