<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Central de Relatórios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">

                <!-- Card: Histórico de Tech Leads -->
                <a href="{{ route('relatorios.show', 'historico-techleads') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-purple-100 text-purple-600 rounded-full p-4 mb-4">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Histórico de Tech Leads</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Visualize todos os Tech Leads que já foram associados a um contrato específico, com datas de início e fim.
                    </p>
                </a>

                <!-- Card: Alocação de Consultores -->
                <a href="{{ route('relatorios.show', 'alocacao-consultores') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-teal-100 text-teal-600 rounded-full p-4 mb-4">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m-7.5-2.962a3.75 3.75 0 015.962 0L14.25 6h3.75m-3.75 0h-6.375a3.75 3.75 0 00-3.75 3.75v6.375c0 1.02.424 1.99.932 2.734.426.606 1.046 1.157 1.816 1.505a3.75 3.75 0 004.472-4.472z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Alocação de Consultores</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Analise o total de horas alocadas (apontadas e agendadas) para consultores PJ em um determinado mês.
                    </p>
                </a>

                <!-- Card: Visão Geral de Contratos -->
                <a href="{{ route('relatorios.show', 'visao-geral-contratos') }}" class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 flex flex-col items-center text-center hover:shadow-lg transition-shadow duration-300">
                    <div class="bg-sky-100 text-sky-600 rounded-full p-4 mb-4">
                        <svg class="h-8 w-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Visão Geral de Contratos</h3>
                    <p class="mt-2 text-sm text-gray-600">
                        Compare o total de horas contratadas com as horas já gastas e aprovadas para cada contrato ativo.
                    </p>
                </a>

            </div>
        </div>
    </div>
</x-app-layout>
