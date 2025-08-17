<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-8 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-2xl text-gray-800 leading-tight mb-6">Detalhes do Cliente</h2>
                    <div class="space-y-8">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações da Empresa</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-2"><dt class="text-sm font-medium text-gray-500">Nome da Empresa</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->nome_empresa }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">CNPJ</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->cnpj }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Status</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->status }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Endereço</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-2"><dt class="text-sm font-medium text-gray-500">Logradouro</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->endereco_completo['logradouro'] ?? 'N/A' }}, {{ $empresa->endereco_completo['numero'] ?? 'S/N' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Bairro</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->endereco_completo['bairro'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Complemento</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->endereco_completo['complemento'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Cidade / UF</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->endereco_completo['cidade'] ?? 'N/A' }} / {{ $empresa->endereco_completo['uf'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">CEP</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->endereco_completo['cep'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Principal</h3>
                             <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nome</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_principal['nome'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_principal['email'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Telefone</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_principal['telefone'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Comercial</h3>
                             <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nome</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_comercial['nome'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_comercial['email'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Telefone</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_comercial['telefone'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Financeiro</h3>
                             <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nome</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_financeiro['nome'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_financeiro['email'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Telefone</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_financeiro['telefone'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Técnico</h3>
                             <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Nome</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_tecnico['nome'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Email</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_tecnico['email'] ?? 'N/A' }}</dd></div>
                                <div class="sm:col-span-1"><dt class="text-sm font-medium text-gray-500">Telefone</dt><dd class="mt-1 text-sm text-gray-900">{{ $empresa->contato_tecnico['telefone'] ?? 'N/A' }}</dd></div>
                            </dl>
                        </div>
                        
                        <!-- Seção de Auditoria -->
                        <div>
                            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações de Auditoria</h3>
                            <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Criado por</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $empresa->creator->nome ?? 'Sistema' }} em {{ $empresa->created_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                                <div class="sm:col-span-1">
                                    <dt class="text-sm font-medium text-gray-500">Última Atualização por</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $empresa->updater->nome ?? 'Sistema' }} em {{ $empresa->updated_at->format('d/m/Y H:i') }}
                                    </dd>
                                </div>
                            </dl>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-8 pt-5 border-t">
                        <a href="{{ route('empresas.index') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold py-2 px-4 rounded-md shadow-sm">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
