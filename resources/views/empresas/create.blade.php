<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Cadastrar Novo Cliente
                    </h2>
                    <form action="{{ route('empresas.store') }}" method="POST">
                        @include('empresas._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>