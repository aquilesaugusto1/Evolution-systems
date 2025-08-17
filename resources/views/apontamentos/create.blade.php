{{-- resources/views/apontamentos/create.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Novo Apontamento
                    </h2>
                    <form action="{{ route('apontamentos.store') }}" method="POST">
                        @csrf
                        @include('apontamentos.partials.form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>