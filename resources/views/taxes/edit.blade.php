<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-slate-200 leading-tight">
            {{ __('Editar Imposto') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-slate-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-slate-100">
                    <form method="POST" action="{{ route('impostos.update', $imposto) }}">
                        @csrf
                        @method('PUT')
                        @include('taxes._form', ['imposto' => $imposto])
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>