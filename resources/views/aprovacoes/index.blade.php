<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight mb-6">
                Aprovações de Apontamentos Pendentes
            </h2>

            @if($apontamentos->isEmpty())
                <div class="text-center py-16 bg-white rounded-lg shadow-sm border">
                    <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-slate-900">Tudo certo por aqui!</h3>
                    <p class="mt-1 text-sm text-slate-500">Não há nenhum apontamento pendente de aprovação.</p>
                </div>
            @else
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                    @foreach ($apontamentos as $apontamento)
                        <div class="bg-white overflow-hidden shadow-lg rounded-lg border border-slate-200 flex flex-col">
                            <div class="p-6 flex-grow">
                                <div class="flex justify-between items-start mb-4">
                                    <div>
                                        <p class="font-bold text-lg text-slate-800">{{ $apontamento->consultor->nome }}</p>
                                        <p class="text-sm text-slate-600">
                                            Para <strong>{{ $apontamento->agenda->contrato->cliente->nome_empresa ?? 'Cliente não encontrado' }}</strong>
                                        </p>
                                        <p class="text-xs text-slate-500">Enviado em: {{ $apontamento->created_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                    <div class="text-right flex-shrink-0 ml-4">
                                        <p class="text-2xl font-bold text-indigo-600">{{ number_format($apontamento->horas_gastas, 1) }}h</p>
                                        <p class="text-xs font-semibold {{ $apontamento->faturavel ? 'text-emerald-600' : 'text-amber-600' }}">{{ $apontamento->faturavel ? 'Faturável' : 'Não Faturável' }}</p>
                                    </div>
                                </div>

                                <div class="mt-4 p-4 bg-slate-50 rounded-md">
                                    <p class="text-sm font-semibold text-slate-700 mb-1">Atividade: {{ $apontamento->agenda->assunto }}</p>
                                    <p class="text-sm text-slate-600 whitespace-pre-wrap">{{ $apontamento->descricao }}</p>
                                    @if($apontamento->caminho_anexo)
                                        <a href="{{ Storage::url($apontamento->caminho_anexo) }}" target="_blank" class="mt-3 inline-block text-sm font-medium text-indigo-600 hover:underline">
                                            Visualizar Anexo (PDF)
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @can('approve', $apontamento)
                            <div class="bg-slate-50 p-4 border-t border-slate-200">
                                <div class="flex flex-wrap items-center gap-4">
                                    <form action="{{ route('aprovacoes.aprovar', $apontamento) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full px-4 py-2 bg-emerald-500 text-white text-sm font-bold uppercase rounded-md hover:bg-emerald-600 transition-colors">Aprovar</button>
                                    </form>

                                    <form action="{{ route('aprovacoes.rejeitar', $apontamento) }}" method="POST" class="flex-grow-[2] w-full sm:w-auto">
                                        @csrf
                                        <div class="flex">
                                            <input type="text" name="motivo_rejeicao" placeholder="Motivo para rejeitar..." required class="w-full text-sm rounded-l-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <button type="submit" class="px-4 py-2 bg-red-500 text-white text-sm font-bold uppercase rounded-r-md hover:bg-red-600 transition-colors">Rejeitar</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @endcan
                        </div>
                    @endforeach
                </div>

                <div class="mt-6">
                    {{ $apontamentos->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>