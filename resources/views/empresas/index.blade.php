<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Cadastro de Clientes
                </h2>
                @can('create', App\Models\EmpresaParceira::class)
                    <a href="{{ route('empresas.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">
                        Novo Cliente
                    </a>
                @endcan
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                <form action="{{ route('empresas.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="nome_empresa" class="block text-sm font-medium text-gray-700">Nome da Empresa</label>
                            <input type="text" name="nome_empresa" id="nome_empresa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('nome_empresa') }}">
                        </div>
                        <div>
                            <label for="cnpj" class="block text-sm font-medium text-gray-700">CNPJ</label>
                            <input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('cnpj') }}">
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                <option value="Ativo" @selected(request('status') == 'Ativo')>Ativo</option>
                                <option value="Inativo" @selected(request('status') == 'Inativo')>Inativo</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">Filtrar</button>
                            <a href="{{ route('empresas.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nome da Empresa</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">CNPJ</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($empresas as $empresa)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $empresa->nome_empresa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $empresa->cnpj }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $empresa->status === 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $empresa->status }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('empresas.show', $empresa) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        @can('update', $empresa)
                                            <a href="{{ route('empresas.edit', $empresa) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Editar</a>
                                        @endcan
                                        @can('toggleStatus', $empresa)
                                            <form action="{{ route('empresas.toggleStatus', $empresa) }}" method="POST" class="inline ml-4">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $empresa->status === 'Ativo' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" onclick="return confirm('Tem certeza que deseja alterar o status deste cliente?')">{{ $empresa->status === 'Ativo' ? 'Desabilitar' : 'Habilitar' }}</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhum cliente encontrado para os filtros aplicados.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $empresas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
