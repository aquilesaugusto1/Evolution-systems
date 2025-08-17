<x-app-layout>
    <div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-4xl mx-auto">
        <div class="bg-white shadow-lg rounded-sm border border-slate-200 p-6">
            <h1 class="text-2xl md:text-3xl text-slate-800 font-bold mb-6">Criar Novo Contrato</h1>
            
            {{-- A CORREÇÃO ESTÁ AQUI --}}
            <form action="{{ route('contratos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('contratos._form')
            </form>

        </div>
    </div>
</x-app-layout>