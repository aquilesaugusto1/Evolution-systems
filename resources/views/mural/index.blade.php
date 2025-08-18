<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mural de Habilidades
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Filtros -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('mural.index') }}" method="GET">
                        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Buscar por Nome ou Cargo</label>
                                <input type="text" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" value="{{ request('search') }}">
                            </div>

                            @if($podeVerSkills)
                                <div>
                                    <label for="skill" class="block text-sm font-medium text-gray-700">Filtrar por Habilidade</label>
                                    <select name="skill" id="skill" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Todas</option>
                                        @foreach($skills->groupBy('categoria') as $categoria => $skillsCategoria)
                                            <optgroup label="{{ $categoria }}">
                                                @foreach($skillsCategoria as $skill)
                                                    <option value="{{ $skill->id }}" {{ request('skill') == $skill->id ? 'selected' : '' }}>{{ $skill->nome }}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label for="nivel" class="block text-sm font-medium text-gray-700">Nível Mínimo</label>
                                    <select name="nivel" id="nivel" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <option value="">Qualquer</option>
                                        <option value="1" {{ request('nivel') == 1 ? 'selected' : '' }}>1 - Básico</option>
                                        <option value="2" {{ request('nivel') == 2 ? 'selected' : '' }}>2 - Iniciante</option>
                                        <option value="3" {{ request('nivel') == 3 ? 'selected' : '' }}>3 - Intermediário</option>
                                        <option value="4" {{ request('nivel') == 4 ? 'selected' : '' }}>4 - Avançado</option>
                                        <option value="5" {{ request('nivel') == 5 ? 'selected' : '' }}>5 - Especialista</option>
                                    </select>
                                </div>
                            @endif

                            <div class="flex items-end">
                                <button type="submit" class="w-full justify-center inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                                    Filtrar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Grid de Usuários -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @forelse($usuarios as $usuario)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300">
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ $usuario->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($usuario->nome).'&color=7F9CF5&background=EBF4FF' }}" alt="Foto de {{ $usuario->nome }}">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $usuario->nome }} {{ $usuario->sobrenome }}</h3>
                                    <p class="text-sm text-indigo-600">{{ $usuario->cargo }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mt-4">{{ $usuario->bio ?? 'Nenhuma biografia disponível.' }}</p>

                            @if($podeVerSkills && $usuario->skills->isNotEmpty())
                                <div class="mt-4 pt-4 border-t border-gray-200">
                                    <h4 class="text-sm font-semibold text-gray-700 mb-2">Principais Habilidades</h4>
                                    <div class="space-y-2">
                                        @foreach($usuario->skills->take(3) as $skill)
                                            <div class="text-xs">
                                                <div class="flex justify-between mb-1">
                                                    <span class="font-medium text-gray-600">{{ $skill->nome }}</span>
                                                    <span class="text-gray-500">{{ $skill->pivot->nivel }}/5</span>
                                                </div>
                                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                                    <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ $skill->pivot->nivel * 20 }}%"></div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-10">Nenhum colaborador encontrado com os filtros aplicados.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $usuarios->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
