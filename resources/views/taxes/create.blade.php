<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cadastrar Novo Imposto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Container principal com fundo branco --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('impostos.store') }}">
                        @csrf
                        {{-- O formulário está em um arquivo separado, que também precisará ser ajustado --}}
                        @include('taxes._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
