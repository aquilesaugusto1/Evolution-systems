<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Caixa de Sugestões
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Botão de Nova Sugestão -->
            <div class="mb-6 flex justify-end">
                <a href="{{ route('sugestoes.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Enviar Nova Sugestão
                </a>
            </div>

            <!-- Filtros -->
            <div class="mb-6">
                <form method="GET" action="{{ route('sugestoes.index') }}">
                    <div class="bg-white p-4 rounded-lg shadow-sm flex items-center space-x-4">
                        <div class="flex-1">
                            <x-input-label for="status" value="Filtrar por Status" />
                            {{-- Caixa de seleção com estilo visual melhorado --}}
                            <select name="status" id="status" class="mt-1 block w-full md:w-1/3 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                <option value="">Todos os Status</option>
                                <option value="Pendente" @selected(request('status') == 'Pendente')>Pendente</option>
                                <option value="Em Análise" @selected(request('status') == 'Em Análise')>Em Análise</option>
                                <option value="Concluída" @selected(request('status') == 'Concluída')>Concluída</option>
                                <option value="Rejeitada" @selected(request('status') == 'Rejeitada')>Rejeitada</option>
                            </select>
                        </div>
                        <div class="pt-6">
                            <x-primary-button type="submit">Filtrar</x-primary-button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Grid de Sugestões -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($sugestoes as $sugestao)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 flex flex-col">
                        <div class="p-6 flex-1">
                            <div class="flex justify-between items-start mb-2">
                                <h3 class="text-lg font-bold text-gray-900">{{ $sugestao->titulo }}</h3>
                                @php
                                    $statusClasses = [
                                        'Pendente' => 'bg-yellow-100 text-yellow-800',
                                        'Em Análise' => 'bg-blue-100 text-blue-800',
                                        'Concluída' => 'bg-green-100 text-green-800',
                                        'Rejeitada' => 'bg-red-100 text-red-800',
                                    ];
                                @endphp
                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $statusClasses[$sugestao->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $sugestao->status }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-600 mt-2">{{ $sugestao->descricao }}</p>
                        </div>
                        <div class="bg-gray-50 px-6 py-4 border-t flex justify-between items-center">
                            <div>
                                <p class="text-xs text-gray-500">Enviado por: <span class="font-medium">{{ $sugestao->usuario->nome ?? 'N/A' }}</span></p>
                                <p class="text-xs text-gray-500">{{ $sugestao->created_at->format('d/m/Y \à\s H:i') }}</p>
                            </div>
                            @can('update', $sugestao)
                                <form action="{{ route('sugestoes.update', $sugestao) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <select name="status" class="form-select text-xs rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="this.form.submit()">
                                        @foreach(['Pendente', 'Em Análise', 'Concluída', 'Rejeitada'] as $status)
                                            <option value="{{ $status }}" @selected($sugestao->status == $status)>{{ $status }}</option>
                                        @endforeach
                                    </select>
                                </form>
                            @endcan
                        </div>
                    </div>
                @empty
                    <div class="md:col-span-2 lg:col-span-3 text-center py-12">
                        <p class="text-gray-500">Nenhuma sugestão encontrada.</p>
                    </div>
                @endforelse
            </div>
            
            <div class="mt-8">
                {{ $sugestoes->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
