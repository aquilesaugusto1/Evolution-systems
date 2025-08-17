<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-lg rounded-sm border border-slate-200 p-6 md:p-8">
                <div class="flex justify-between items-start mb-6">
                    <div>
                        <h1 class="text-2xl md:text-3xl text-slate-800 font-bold">{{ $agenda->assunto }}</h1>
                        <span class="text-sm text-slate-500">Agendado para {{ $agenda->data_hora->format('d/m/Y \à\s H:i') }}</span>
                    </div>
                    <div class="px-3 py-1 text-xs font-semibold rounded-full {{ 
                        match($agenda->status) {
                            'Agendada' => 'bg-blue-100 text-blue-800',
                            'Realizada' => 'bg-emerald-100 text-emerald-800',
                            'Cancelada' => 'bg-amber-100 text-amber-800',
                            default => 'bg-slate-100 text-slate-800'
                        } 
                    }}">
                        {{ $agenda->status }}
                    </div>
                </div>

                <div class="space-y-6">
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Detalhes da Agenda</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4 text-sm">
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Consultor:</span>
                                <span class="text-slate-800">{{ $agenda->consultor->nome ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Cliente:</span>
                                <span class="text-slate-800">{{ $agenda->contrato->empresaParceira->nome_empresa ?? 'N/A' }}</span>
                            </div>
                            <div class="flex justify-between border-b py-2">
                                <span class="font-medium text-slate-600">Contrato:</span>
                                <span class="text-slate-800">#{{ $agenda->contrato->numero_contrato ?? $agenda->contrato->id }}</span>
                            </div>
                        </div>
                    </div>
                    
                    @if($agenda->descricao)
                    <div>
                        <h3 class="text-lg font-semibold text-slate-700 border-b pb-2 mb-3">Descrição</h3>
                        <div class="p-4 bg-slate-50 rounded-lg text-sm text-slate-600 whitespace-pre-wrap">
                            {{ $agenda->descricao }}
                        </div>
                    </div>
                    @endif
                </div>

                <div class="flex items-center justify-end mt-8 pt-5 border-t border-slate-200">
                    <a href="{{ route('agendas.index') }}" class="inline-flex items-center px-4 py-2 bg-white border border-slate-300 rounded-md font-semibold text-xs text-slate-700 uppercase tracking-widest shadow-sm hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                        Voltar para a Lista
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
