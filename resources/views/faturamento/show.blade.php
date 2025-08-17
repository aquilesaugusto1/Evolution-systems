<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detalhes da Fatura: ') . $fatura->numero_fatura }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Dados da Fatura</h3>
                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Número:</dt>
                                <dd class="text-gray-900 font-semibold">{{ $fatura->numero_fatura }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Status:</dt>
                                <dd>
                                    @php
                                        $statusClass = match($fatura->status) {
                                            \App\Enums\FaturaStatusEnum::PAGA => 'bg-green-100 text-green-800',
                                            \App\Enums\FaturaStatusEnum::ATRASADA => 'bg-red-100 text-red-800',
                                            \App\Enums\FaturaStatusEnum::CANCELADA => 'bg-gray-100 text-gray-800',
                                            default => 'bg-yellow-100 text-yellow-800',
                                        };
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                        {{ $fatura->status->value }}
                                    </span>
                                </dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Emissão:</dt>
                                <dd class="text-gray-900">{{ $fatura->data_emissao->format('d/m/Y') }}</dd>
                            </div>
                             <div class="flex justify-between">
                                <dt class="text-gray-500">Vencimento:</dt>
                                <dd class="text-gray-900">{{ $fatura->data_vencimento->format('d/m/Y') }}</dd>
                            </div>
                             <div class="flex justify-between">
                                <dt class="text-gray-500">Criado por:</dt>
                                <dd class="text-gray-900">{{ $fatura->creator->name ?? 'Sistema' }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Dados do Cliente</h3>
                        <dl class="mt-4 space-y-2 text-sm">
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Cliente:</dt>
                                <dd class="text-gray-900 font-semibold">{{ $fatura->contrato->empresaParceira->nome_fantasia }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">Contrato:</dt>
                                <dd class="text-gray-900">{{ $fatura->contrato->numero_contrato }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="text-gray-500">CNPJ:</dt>
                                <dd class="text-gray-900">{{ $fatura->contrato->empresaParceira->cnpj }}</dd>
                            </div>
                        </dl>
                    </div>
                    <div class="flex flex-col justify-between items-end p-4 bg-gray-50 rounded-lg">
                        <div>
                            <span class="text-sm text-gray-500">Valor Total</span>
                            <p class="text-3xl font-bold text-gray-900 tracking-tight">{{ 'R$ ' . number_format($fatura->valor_total, 2, ',', '.') }}</p>
                        </div>
                        <div class="mt-4 flex gap-2">
                             @if($fatura->status !== \App\Enums\FaturaStatusEnum::CANCELADA)
                                <form action="{{ route('faturamento.destroy', $fatura) }}" method="POST" onsubmit="return confirm('Tem certeza que deseja cancelar esta fatura? Os apontamentos serão liberados para faturamento futuro.');">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button>Cancelar Fatura</x-danger-button>
                                </form>
                            @endif
                            <x-secondary-button>Baixar PDF</x-secondary-button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                 <h3 class="text-lg font-medium text-gray-900 mb-4">Apontamentos Incluídos</h3>
                 <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Descrição</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Horas</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($fatura->apontamentos as $apontamento)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $apontamento->data_apontamento->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $apontamento->consultor->nome }} {{ $apontamento->consultor->sobrenome }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $apontamento->descricao }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500 font-mono">{{ $apontamento->horas_gastas }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>