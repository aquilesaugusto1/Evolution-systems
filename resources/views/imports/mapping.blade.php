<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mapeamento das Colunas') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <p class="mb-6">
                        Associe as colunas do seu arquivo (à direita) com os campos obrigatórios do sistema (à esquerda).
                    </p>

                    <form action="{{ route('imports.processMapping') }}" method="POST">
                        @csrf

                        <div class="space-y-4">
                            @foreach ($dbColumns as $dbKey => $dbLabel)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 items-center">
                                    <x-input-label for="mappings_{{ $dbKey }}" :value="$dbLabel" class="font-bold" />
                                    
                                    <select id="mappings_{{ $dbKey }}" name="mappings[{{ $dbKey }}]" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                        <option value="">-- Ignorar esta coluna --</option>
                                        @foreach ($headings as $heading)
                                            <option value="{{ $heading }}" 
                                                {{ str_contains(strtolower($heading), strtolower(explode(' ', $dbLabel)[0])) ? 'selected' : '' }}>
                                                {{ $heading }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('mappings.' . $dbKey)
                                        <p class="text-sm text-red-600 dark:text-red-400 mt-2 md:col-span-2">{{ $message }}</p>
                                    @enderror
                                </div>
                            @endforeach
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <x-primary-button>
                                {{ __('Processar Importação') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
