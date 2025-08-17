<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Editar Consultor: {{ $consultor->nome }}
                    </h2>
                    <form action="{{ route('consultores.update', $consultor) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nome" class="block font-medium text-sm text-gray-700">Nome Completo</label>
                                <input type="text" name="nome" id="nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('nome', $consultor->nome) }}" required>
                            </div>
                             <div>
                                <label for="email" class="block font-medium text-sm text-gray-700">Email</label>
                                <input type="email" name="email" id="email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('email', $consultor->email) }}" required>
                            </div>
                             <div>
                                <label for="telefone" class="block font-medium text-sm text-gray-700">Telefone</label>
                                <input type="text" name="telefone" id="telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('telefone', $consultor->telefone) }}">
                            </div>
                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="Ativo" {{ old('status', $consultor->status) == 'Ativo' ? 'selected' : '' }}>Ativo</option>
                                    <option value="Inativo" {{ old('status', $consultor->status) == 'Inativo' ? 'selected' : '' }}>Inativo</option>
                                </select>
                            </div>
                            <div class="md:col-span-2">
                                <label for="tech_leads" class="block font-medium text-sm text-gray-700">Tech Leads Responsáveis</label>
                                <select name="tech_leads[]" id="tech_leads" multiple class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                    @foreach ($techLeads as $techLead)
                                        <option value="{{ $techLead->id }}" {{ $consultor->techLeads->contains($techLead) ? 'selected' : '' }}>
                                            {{ $techLead->nome }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('consultores.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
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
