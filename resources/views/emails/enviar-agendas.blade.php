<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Enviar Agendas por Email
                    </h2>
                    <form action="{{ route('email.agendas.send') }}" method="POST">
                        @csrf
                        <div class="space-y-6">
                            <div>
                                <label for="consultor_id" class="block font-medium text-sm text-gray-700">Consultor</label>
                                <select name="consultor_id" id="consultor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Selecione um consultor</option>
                                    @foreach ($consultores as $consultor)
                                        <option value="{{ $consultor->id }}">{{ $consultor->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="data_inicio" class="block font-medium text-sm text-gray-700">Período - Início</label>
                                    <input type="date" name="data_inicio" id="data_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="data_fim" class="block font-medium text-sm text-gray-700">Período - Fim</label>
                                    <input type="date" name="data_fim" id="data_fim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                            </div>
                            <div>
                                <label for="recado" class="block font-medium text-sm text-gray-700">Recado (Opcional)</label>
                                <textarea name="recado" id="recado" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm"></textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Enviar Email
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
