<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Detalhes do Consultor: {{ $consultor->nome }}
                    </h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Nome</h3>
                            <p>{{ $consultor->nome }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Email</h3>
                            <p>{{ $consultor->email }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Telefone</h3>
                            <p>{{ $consultor->telefone ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h3 class="text-md font-bold text-gray-600 uppercase">Status</h3>
                             <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $consultor->status == 'Ativo' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                {{ $consultor->status }}
                            </span>
                        </div>
                        <div class="md:col-span-2">
                            <h3 class="text-md font-bold text-gray-600 uppercase">Tech Leads Responsáveis</h3>
                            <ul class="list-disc list-inside mt-2">
                                @forelse($consultor->techLeads as $techLead)
                                    <li>{{ $techLead->nome }}</li>
                                @empty
                                    <li>Nenhum Tech Lead associado.</li>
                                @endforelse
                            </ul>
                        </div>
                    </div>
                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('consultores.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
