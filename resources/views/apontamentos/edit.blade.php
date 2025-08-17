{{-- resources/views/apontamentos/edit.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Editar Apontamento
                    </h2>
                    <form action="{{ route('apontamentos.update', $apontamento) }}" method="POST">
                        @csrf
                        @method('PUT')
                        @include('apontamentos.partials.form', ['apontamento' => $apontamento])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>