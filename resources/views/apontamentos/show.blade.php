<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Detalhes do Apontamento
                    </h2>
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Cliente</h3>
                            <p>{{ $apontamento->agenda->empresaParceira->nome_empresa }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Consultor</h3>
                            <p>{{ $apontamento->consultor->nome }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Data do Apontamento</h3>
                            <p>{{ $apontamento->data_apontamento->format('d/m/Y') }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Horas Gastas</h3>
                            <p>{{ number_format($apontamento->horas_gastas, 2, ',', '.') }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Status da Fatura</h3>
                            <p>{{ $apontamento->faturado ? 'Faturado' : 'Não Faturado' }}</p>
                        </div>
                        <div class="col-span-2">
                             <h3 class="text-md font-bold text-gray-600 uppercase">Descrição das Atividades</h3>
                            <p class="mt-1 p-2 bg-gray-50 rounded-md whitespace-pre-wrap">{{ $apontamento->descricao }}</p>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('apontamentos.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
