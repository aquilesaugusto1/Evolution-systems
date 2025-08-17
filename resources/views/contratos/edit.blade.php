<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 p-6">
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold mb-6">Editar Contrato</h1>
            <form action="{{ route('contratos.update', $contrato) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                @include('contratos._form', ['contrato' => $contrato])
            </form>
        </div>
    </div>
</x-app-layout>
