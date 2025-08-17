<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Detalhes do Tech Lead: {{ $techlead->nome }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Nome</h3>
                            <p>{{ $techlead->nome }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Email</h3>
                            <p>{{ $techlead->email }}</p>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-md font-bold text-gray-600 uppercase">Consultores Liderados</h3>
                            <ul class="list-disc list-inside mt-2">
                                @forelse($techlead->consultoresLiderados as $consultor)
                                    <li>{{ $consultor->nome }}</li>
                                @empty
                                    <li>Nenhum consultor liderado no momento.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('techleads.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
