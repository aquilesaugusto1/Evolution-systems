<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-between items-center mb-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestão de Contratos
                </h2>
                @can('create', App\Models\Contrato::class)
                    <a href="{{ route('contratos.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">
                        Novo Contrato
                    </a>
                @endcan
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm mb-4">
                <form action="{{ route('contratos.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="cliente_id" class="block text-sm font-medium text-gray-700">Cliente</label>
                            <select name="cliente_id" id="cliente_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                @foreach($clientes as $cliente)
                                    <option value="{{ $cliente->id }}" @selected(request('cliente_id') == $cliente->id)>{{ $cliente->nome_empresa }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                <option value="Ativo" @selected(request('status') == 'Ativo')>Ativo</option>
                                <option value="Inativo" @selected(request('status') == 'Inativo')>Inativo</option>
                            </select>
                        </div>
                        <div class="flex items-end col-span-1 md:col-span-2">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">Filtrar</button>
                            <a href="{{ route('contratos.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">Limpar Filtros</a>
                        </div>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nº Contrato</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Horas Originais</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Horas</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data Início</th>
                                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse ($contratos as $contrato)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $contrato->empresaParceira->nome_empresa }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contrato->numero_contrato ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contrato->baseline_horas_original ? number_format($contrato->baseline_horas_original, 2) . 'h' : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-bold">{{ $contrato->baseline_horas_mes ? number_format($contrato->baseline_horas_mes, 2) . 'h' : 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $contrato->status === 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $contrato->status }}</span></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $contrato->data_inicio->format('d/m/Y') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <a href="{{ route('contratos.show', $contrato) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>
                                        @can('update', $contrato)
                                            <a href="{{ route('contratos.edit', $contrato) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Editar</a>
                                        @endcan
                                        @can('toggleStatus', $contrato)
                                            <form action="{{ route('contratos.toggleStatus', $contrato) }}" method="POST" class="inline ml-4">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="{{ $contrato->status === 'Ativo' ? 'text-red-600 hover:text-red-900' : 'text-green-600 hover:text-green-900' }}" onclick="return confirm('Tem certeza que deseja alterar o status deste contrato?')">{{ $contrato->status === 'Ativo' ? 'Desabilitar' : 'Ativar' }}</button>
                                            </form>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhum contrato encontrado.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="mt-4">
                        {{ $contratos->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
