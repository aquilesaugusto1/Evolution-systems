<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gerar Nova Fatura') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h3 class="text-lg font-medium text-gray-900">1. Selecionar Período e Contrato</h3>
                    <form method="GET" action="{{ route('faturamento.create') }}" class="mt-6 space-y-6">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <x-input-label for="contrato_id" :value="__('Contrato')" />
                                <select id="contrato_id" name="contrato_id" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="">Selecione um contrato</option>
                                    @foreach($contratos as $contrato)
                                        <option value="{{ $contrato->id }}" @selected(request()->query('contrato_id') == $contrato->id)>
                                            {{ $contrato->numero_contrato }} - {{ $contrato->nome }} - {{ $contrato->empresaParceira->nome_fantasia }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <x-input-label for="data_inicio" :value="__('Data de Início')" />
                                <x-text-input id="data_inicio" name="data_inicio" type="date" class="mt-1 block w-full" :value="request()->query('data_inicio')" required />
                            </div>
                            <div>
                                <x-input-label for="data_fim" :value="__('Data Final')" />
                                <x-text-input id="data_fim" name="data_fim" type="date" class="mt-1 block w-full" :value="request()->query('data_fim')" required />
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <x-primary-button>{{ __('Buscar Apontamentos') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            @if(request()->query('contrato_id') && $contratoSelecionado)
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-full">
                    <h3 class="text-lg font-medium text-gray-900">2. Apontamentos a Faturar</h3>
                    <p class="mt-1 text-sm text-gray-600">
                        Os seguintes apontamentos aprovados e faturáveis foram encontrados para o período.
                    </p>

                    @if($apontamentos->isNotEmpty())
                        <form method="POST" action="{{ route('faturamento.store') }}">
                            @csrf
                            <input type="hidden" name="contrato_id" value="{{ request()->query('contrato_id') }}">
                            <input type="hidden" name="data_inicio" value="{{ request()->query('data_inicio') }}">
                            <input type="hidden" name="data_fim" value="{{ request()->query('data_fim') }}">

                            <div class="mt-6 border-t border-gray-200">
                                <dl class="divide-y divide-gray-200">
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Valor/Hora do Contrato</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-mono">{{ 'R$ ' . number_format($contratoSelecionado->valor_hora, 2, ',', '.') }}</dd>
                                    </div>
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-medium text-gray-500">Total de Horas</dt>
                                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 font-mono">{{ $totalHoras }}</dd>
                                    </div>
                                    <div class="py-4 sm:py-5 sm:grid sm:grid-cols-3 sm:gap-4">
                                        <dt class="text-sm font-bold text-gray-600">Valor Total a Faturar</dt>
                                        <dd class="mt-1 text-sm font-bold text-gray-900 sm:mt-0 sm:col-span-2 font-mono">{{ 'R$ ' . number_format($valorTotal, 2, ',', '.') }}</dd>
                                    </div>
                                </dl>
                            </div>

                            <div class="mt-6">
                                <x-input-label for="billing_type" :value="__('Formas de Pagamento')" />
                                <select id="billing_type" name="billing_type" class="mt-1 block w-full md:w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                                    <option value="UNDEFINED">PIX, Boleto e Cartão</option>
                                    <option value="PIX">Apenas PIX</option>
                                    <option value="BOLETO">Apenas Boleto</option>
                                    <option value="CREDIT_CARD">Apenas Cartão de Crédito</option>
                                </select>
                                <p class="mt-2 text-sm text-gray-500">Selecione as formas de pagamento a serem oferecidas ao cliente.</p>
                            </div>

                            <div class="mt-6 overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($apontamentos as $apontamento)
                                            <tr class="bg-white">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $apontamento->data_apontamento->format('d/m/Y') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $apontamento->consultor->nome }}</td>
                                                <td class="px-6 py-4 text-sm text-gray-500">{{ Str::limit($apontamento->descricao, 50) }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 font-mono">{{ $apontamento->horas_gastas }}</td>
                                                <input type="hidden" name="apontamento_ids[]" value="{{ $apontamento->id }}">
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="flex items-center gap-4 mt-6">
                                <x-primary-button>{{ __('Confirmar e Gerar Fatura') }}</x-primary-button>
                            </div>
                        </form>
                    @else
                        <p class="mt-6 text-center text-sm text-gray-500">Nenhum apontamento faturável encontrado para este período.</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
