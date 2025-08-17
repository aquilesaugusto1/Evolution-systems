<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Detalhes do Colaborador
                    </h2>
                    
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Pessoais e Endereço</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nome Completo</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->nome }} {{ $colaborador->sobrenome }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Data de Nascimento</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->data_nascimento ? $colaborador->data_nascimento->format('d/m/Y') : 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nacionalidade</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->nacionalidade ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Naturalidade</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->naturalidade ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-2"><dt class="text-sm font-medium text-gray-500">Endereço</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->endereco['rua'] ?? '' }}, {{ $colaborador->endereco['bairro'] ?? '' }} - {{ $colaborador->endereco['cidade'] ?? '' }}/{{ $colaborador->endereco['estado'] ?? '' }}</dd></div>
                            </dl>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Profissionais</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email Agen</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->email ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email Totvs Partner</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->email_totvs_partner ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->status ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Tipo de Contrato</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->tipo_contrato ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Cargo</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->cargo ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nível</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->nivel ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-2">
                                    <dt class="text-sm font-medium text-gray-500">Subordinado a (Tech Leads)</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        @forelse($colaborador->techLeads as $lead)
                                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs font-medium mr-2 px-2.5 py-0.5 rounded-full">{{ $lead->nome }}</span>
                                        @empty
                                            Nenhum
                                        @endforelse
                                    </dd>
                                </div>
                            </dl>
                        </div>

                        @if(in_array($colaborador->tipo_contrato, ['PJ Mensal', 'PJ Horista']))
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados da Empresa (PJ)</h3>
                             <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-4 gap-y-6">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Razão Social</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->dados_empresa_prestador['razao_social'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">CNPJ</dt><dd class="mt-1 text-sm text-gray-900">{{ $colaborador->dados_empresa_prestador['cnpj'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        @endif

                        <!-- Seção de Auditoria -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações de Auditoria</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Criado por</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $colaborador->creator->nome ?? 'Sistema' }} em {{ $colaborador->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Última Atualização por</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $colaborador->updater->nome ?? 'Sistema' }} em {{ $colaborador->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6 pt-5 border-t">
                        <a href="{{ route('colaboradores.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
