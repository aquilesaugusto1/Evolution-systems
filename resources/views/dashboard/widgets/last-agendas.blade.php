<div class="bg-white p-6 rounded-xl shadow-md border border-slate-200">
    <h2 class="text-xl font-bold text-slate-800 mb-4">Suas Próximas Agendas</h2>
    <div class="space-y-4">
        @forelse($widgetData as $agenda)
            <div class="flex items-center justify-between p-4 rounded-lg bg-slate-50 border border-slate-200">
                <div>
                    <p class="font-semibold text-slate-800">{{ $agenda->assunto }}</p>
                    <p class="text-sm text-slate-500">
                        Para <strong>{{ $agenda->contrato->empresaParceira->nome_empresa ?? 'N/A' }}</strong>
                    </p>
                </div>
                <div class="text-right">
                    <p class="font-bold text-slate-800">{{ $agenda->data_hora ? $agenda->data_hora->format('d/m/Y') : 'N/A' }}</p>
                    <p class="text-sm text-slate-500">{{ $agenda->data_hora ? $agenda->data_hora->format('H:i') : '' }}</p>
                </div>
            </div>
        @empty
            <div class="text-center py-8">
                <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                <h3 class="mt-2 text-sm font-medium text-slate-900">Nenhuma agenda para exibir.</h3>
                <p class="mt-1 text-sm text-slate-500">Parece que está tudo tranquilo por aqui!</p>
            </div>
        @endforelse
    </div>
</div>
