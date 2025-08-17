<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Enviar Nova Sugestão
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('sugestoes.store') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <x-input-label for="titulo" value="Título da Sugestão *" />
                                <x-text-input id="titulo" name="titulo" type="text" class="mt-1 block w-full" :value="old('titulo')" required autofocus />
                                <x-input-error :messages="$errors->get('titulo')" class="mt-2" />
                            </div>
                            <div>
                                <x-input-label for="descricao" value="Descreva sua sugestão em detalhes *" />
                                <textarea id="descricao" name="descricao" rows="6" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" required>{{ old('descricao') }}</textarea>
                                <x-input-error :messages="$errors->get('descricao')" class="mt-2" />
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('sugestoes.index') }}" class="text-sm text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <x-primary-button>
                                Enviar Sugestão
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
