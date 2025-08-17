<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Editar Tech Lead: {{ $techlead->nome }}
                    </h2>
                    <form action="{{ route('techleads.update', $techlead) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nome" class="block font-medium text-sm text-gray-700">Nome Completo</label>
                                <input type="text" name="nome" id="nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('nome', $techlead->nome) }}" required>
                            </div>
                             <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email', $techlead->email) }}" required>
                            </div>
                            <div class="md:col-span-2">
                                <label for="consultores" class="block font-medium text-sm text-gray-700">Consultores Liderados</label>
                                <select name="consultores[]" id="consultores" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach ($consultores as $consultor)
                                        <option value="{{ $consultor->id }}" {{ $techlead->consultoresLiderados->contains($consultor) ? 'selected' : '' }}>
                                            {{ $consultor->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('techleads.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
