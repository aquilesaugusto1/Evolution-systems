<x-app-layout>
    @push('styles')
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
        .view-btn.active {
            background-color: #4f46e5;
            color: white;
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex flex-wrap justify-between items-center mb-4 gap-4">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Gestão de Agendas
                </h2>
                <div class="flex items-center space-x-2">
                    <div class="inline-flex rounded-md shadow-sm">
                        <button id="lista-btn" class="view-btn active px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Lista</button>
                        <button id="calendario-btn" class="view-btn px-4 py-2 text-sm font-medium text-gray-700 bg-white border-t border-b border-r border-gray-300 rounded-r-md hover:bg-gray-50">Calendário</button>
                    </div>
                    @can('create', App\Models\Agenda::class)
                        <a href="{{ route('agendas.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700">
                            Nova Agenda
                        </a>
                    @endcan
                </div>
            </div>

            <div class="bg-white p-4 rounded-lg shadow-sm mb-4 border">
                <form action="{{ route('agendas.index') }}" method="GET">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="consultor_id" class="block text-sm font-medium text-gray-700">Consultor</label>
                            <select name="consultor_id" id="consultor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                @foreach($consultores as $consultor)
                                    <option value="{{ $consultor->id }}" @selected(request('consultor_id') == $consultor->id)>{{ $consultor->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="contrato_id" class="block text-sm font-medium text-gray-700">Contrato</label>
                            <select name="contrato_id" id="contrato_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                @foreach($contratos as $contrato)
                                    <option value="{{ $contrato->id }}" @selected(request('contrato_id') == $contrato->id)>{{ $contrato->empresaParceira->nome_empresa }} (#{{$contrato->id}})</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">Todos</option>
                                <option value="Agendada" @selected(request('status') == 'Agendada')>Agendada</option>
                                <option value="Realizada" @selected(request('status') == 'Realizada')>Realizada</option>
                                <option value="Cancelada" @selected(request('status') == 'Cancelada')>Cancelada</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">Filtrar</button>
                            <a href="{{ route('agendas.index') }}" class="ml-2 text-sm text-gray-600 hover:text-gray-900">Limpar</a>
                        </div>
                    </div>
                </form>
            </div>

            <div id="lista-view">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Data/Hora</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assunto</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Consultor</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th scope="col" class="relative px-6 py-3"><span class="sr-only">Ações</span></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($agendas as $agenda)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $agenda->data_hora ? $agenda->data_hora->format('d/m/Y H:i') : 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->assunto }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $agenda->consultor->nome ?? 'N/A' }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full @switch($agenda->status) @case('Agendada') bg-blue-100 text-blue-800 @break @case('Realizada') bg-green-100 text-green-800 @break @case('Cancelada') bg-red-100 text-red-800 @break @default bg-gray-100 text-gray-800 @endswitch">{{ $agenda->status }}</span></td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @can('view', $agenda)<a href="{{ route('agendas.show', $agenda) }}" class="text-indigo-600 hover:text-indigo-900">Ver</a>@endcan
                                            @can('update', $agenda)<a href="{{ route('agendas.edit', $agenda) }}" class="ml-4 text-indigo-600 hover:text-indigo-900">Editar</a>@endcan
                                            @can('delete', $agenda)<form action="{{ route('agendas.destroy', $agenda) }}" method="POST" class="inline ml-4">@csrf @method('DELETE')<button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Tem certeza?')">Remover</button></form>@endcan
                                        </td>
                                    </tr>
                                @empty
                                    <tr><td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">Nenhuma agenda encontrada para os filtros aplicados.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                        <div class="mt-4">{{ $agendas->links() }}</div>
                    </div>
                </div>
            </div>

            <div id="calendario-view" class="hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div id="calendar"></div>
                </div>
            </div>
        </div>
    </div>

@push('scripts')
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const listaBtn = document.getElementById('lista-btn');
    const calendarioBtn = document.getElementById('calendario-btn');
    const listaView = document.getElementById('lista-view');
    const calendarioView = document.getElementById('calendario-view');
    const calendarEl = document.getElementById('calendar');

    const calendar = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
        initialView: 'dayGridMonth',
        locale: 'pt-br',
        buttonText: { today: 'Hoje', month: 'Mês', week: 'Semana', day: 'Dia' },
        events: @json($eventosDoCalendario),
        eventClick: function(info) {
            window.location.href = info.event.extendedProps.url;
        },
        eventDidMount: function(info) {
            info.el.setAttribute('title', `${info.event.extendedProps.cliente}\nConsultor: ${info.event.extendedProps.consultor}`);
        }
    });

    listaBtn.addEventListener('click', () => {
        listaView.classList.remove('hidden');
        calendarioView.classList.add('hidden');
        listaBtn.classList.add('active');
        calendarioBtn.classList.remove('active');
    });

    calendarioBtn.addEventListener('click', () => {
        listaView.classList.add('hidden');
        calendarioView.classList.remove('hidden');
        listaBtn.classList.remove('active');
        calendarioBtn.classList.add('active');
        if (!calendar.isRendered) {
            calendar.render();
        }
    });
});
</script>
@endpush
</x-app-layout>
