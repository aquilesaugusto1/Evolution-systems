{{-- resources/views/relatorios/partials/sumario.blade.php --}}
<table class="min-w-full divide-y divide-gray-200 mb-4">
    <thead class="bg-gray-50">
        <tr>
            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                {{ $tipoRelatorio === 'por_cliente' ? 'Cliente' : 'Consultor' }}
            </th>
            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                Total de Horas Gastas
            </th>
        </tr>
    </thead>
    <tbody class="bg-white divide-y divide-gray-200">
        @forelse ($resultados as $resultado)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">{{ $resultado->nome }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-semibold">{{ number_format($resultado->total_horas, 2, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="2" class="px-6 py-4 text-center text-sm text-gray-500">Nenhum resultado encontrado.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot class="bg-gray-100">
        <tr>
            <td class="px-6 py-3 text-right text-sm font-bold text-gray-700 uppercase">Total Geral de Horas:</td>
            <td class="px-6 py-3 text-right text-sm font-bold text-gray-700">{{ number_format($totalGeralHoras, 2, ',', '.') }}</td>
        </tr>
    </tfoot>
</table>
