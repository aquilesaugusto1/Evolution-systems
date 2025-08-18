<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Mural de Habilidades
        </h2>
    </x-slot>

    <div x-data="{ openModal: false, selectedUser: null }" class="py-12">
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
                    <div @click="selectedUser = {{ json_encode($usuario) }}; openModal = true" class="bg-white rounded-lg shadow-md overflow-hidden transform hover:scale-105 transition-transform duration-300 cursor-pointer">
                        <div class="p-6">
                            <div class="flex items-center space-x-4">
                                <img class="h-16 w-16 rounded-full object-cover" src="{{ $usuario->foto_url ?? 'https://ui-avatars.com/api/?name='.urlencode($usuario->nome).'&color=7F9CF5&background=EBF4FF' }}" alt="Foto de {{ $usuario->nome }}">
                                <div>
                                    <h3 class="text-lg font-bold text-gray-900">{{ $usuario->nome }} {{ $usuario->sobrenome }}</h3>
                                    <p class="text-sm text-indigo-600">{{ $usuario->cargo }}</p>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm mt-4 truncate">{{ $usuario->bio ?? 'Nenhuma biografia disponível.' }}</p>
                        </div>
                    </div>
                @empty
                    <p class="col-span-full text-center text-gray-500 py-10">Nenhum colaborador encontrado com os filtros aplicados.</p>
                @endforelse
            </div>

            <div class="mt-8">
                {{ $usuarios->appends(request()->query())->links() }}
            </div>
        </div>

        <!-- Modal -->
        <div x-show="openModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" style="display: none;">
            <div @click.away="openModal = false" class="bg-white rounded-lg shadow-xl w-full max-w-3xl max-h-[90vh] flex flex-col">
                <div class="p-6 border-b flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-900" x-text="selectedUser ? selectedUser.nome + ' ' + (selectedUser.sobrenome || '') : ''"></h2>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600">&times;</button>
                </div>
                <div class="p-6 overflow-y-auto">
                    <div class="flex flex-col md:flex-row items-start space-y-6 md:space-y-0 md:space-x-8">
                        <div class="flex-shrink-0">
                            <img class="h-32 w-32 rounded-full object-cover shadow-lg" :src="selectedUser.foto_url || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(selectedUser.nome) + '&color=7F9CF5&background=EBF4FF&size=128'" alt="Foto">
                        </div>
                        <div class="flex-1">
                            <p class="text-lg font-semibold text-indigo-600" x-text="selectedUser.cargo || 'Cargo não informado'"></p>
                            <p class="text-gray-700 mt-2" x-text="selectedUser.bio || 'Nenhuma biografia disponível.'"></p>
                            <div class="flex space-x-4 mt-4" x-show="selectedUser.redes_sociais">
                                <a x-show="selectedUser.redes_sociais && selectedUser.redes_sociais.linkedin" :href="selectedUser.redes_sociais.linkedin" target="_blank" class="text-gray-500 hover:text-blue-700">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                                </a>
                                <a x-show="selectedUser.redes_sociais && selectedUser.redes_sociais.github" :href="selectedUser.redes_sociais.github" target="_blank" class="text-gray-500 hover:text-gray-900">
                                    <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                </a>
                            </div>
                        </div>
                    </div>

                    @if($podeVerSkills)
                        <div class="mt-6 pt-6 border-t" x-show="selectedUser && selectedUser.skills && selectedUser.skills.length > 0">
                            <h3 class="text-xl font-bold text-gray-800 mb-4">Habilidades</h3>
                            <div class="space-y-4">
                                <template x-for="(skills, categoria) in _.groupBy(selectedUser.skills, 'categoria')" :key="categoria">
                                    <div>
                                        <h4 class="text-md font-semibold text-gray-700 mb-3" x-text="categoria"></h4>
                                        <div class="grid grid-cols-1 md:grid-cols-2 gap-x-8 gap-y-3">
                                            <template x-for="skill in skills" :key="skill.id">
                                                <div class="text-sm">
                                                    <div class="flex justify-between mb-1">
                                                        <span class="font-medium text-gray-600" x-text="skill.nome"></span>
                                                        <span class="text-gray-500" x-text="skill.pivot.nivel + '/5'"></span>
                                                    </div>
                                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                                        <div class="bg-indigo-500 h-2 rounded-full" :style="'width: ' + (skill.pivot.nivel * 20) + '%'"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/lodash@4.17.21/lodash.min.js"></script>
</x-app-layout>
