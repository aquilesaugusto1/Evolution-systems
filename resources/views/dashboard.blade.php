<x-app-layout>
    <div x-data="dashboard()" x-init="init()">
        <div class="p-4 sm:p-6 lg:p-8">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-slate-800">Olá, {{ Auth::user()->nome }}!</h1>
                    <p class="mt-1 text-lg text-slate-600">Bem-vindo(a) de volta ao seu painel.</p>
                </div>
                <div>
                    <x-secondary-button @click="modalOpen = true">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" viewBox="0 0 20 20" fill="currentColor"><path d="M5 4a1 1 0 00-2 0v7.268a2 2 0 000 3.464V16a1 1 0 102 0v-1.268a2 2 0 000-3.464V4zM11 4a1 1 0 10-2 0v1.268a2 2 0 000 3.464V16a1 1 0 102 0V8.732a2 2 0 000-3.464V4zM16 3a1 1 0 011 1v7.268a2 2 0 010 3.464V16a1 1 0 11-2 0v-1.268a2 2 0 010-3.464V4a1 1 0 011-1z" /></svg>
                        Personalizar
                    </x-secondary-button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <div class="lg:col-span-2 space-y-8" id="coluna-1">
                    @foreach($widgetsLayout[0] as $widget)
                        <div data-id="{{ $widget['id'] }}" data-component="{{ $widget['component'] }}">
                            @include('dashboard.widgets.' . $widget['component'], ['widgetData' => $widgetData[$widget['id']]])
                        </div>
                    @endforeach
                </div>
                <div class="lg:col-span-1 space-y-8" id="coluna-2">
                    @foreach($widgetsLayout[1] as $widget)
                         <div data-id="{{ $widget['id'] }}" data-component="{{ $widget['component'] }}">
                            @include('dashboard.widgets.' . $widget['component'], ['widgetData' => $widgetData[$widget['id']]])
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Modal de Personalização -->
        <div x-show="modalOpen" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="modalOpen = false" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div x-show="modalOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="sm:flex sm:items-start">
                            <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Personalizar Dashboard</h3>
                                <div class="mt-4 grid grid-cols-3 gap-6">
                                    <div class="col-span-1">
                                        <h4 class="font-semibold mb-2">Widgets Disponíveis</h4>
                                        <div id="available-widgets" class="p-4 bg-gray-100 rounded-lg min-h-[200px] space-y-2">
                                            @foreach($availableWidgets as $widget)
                                                <div class="p-3 bg-white border rounded shadow-sm cursor-move" data-id="{{ $widget['id'] }}" data-component="{{ $widget['component'] }}">{{ $widget['name'] }}</div>
                                            @endforeach
                                        </div>
                                    </div>
                                    <div class="col-span-2 grid grid-cols-2 gap-4">
                                        <div>
                                            <h4 class="font-semibold mb-2">Coluna 1 (Larga)</h4>
                                            <div id="modal-coluna-1" class="p-4 bg-gray-100 rounded-lg min-h-[400px] space-y-2"></div>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold mb-2">Coluna 2 (Estreita)</h4>
                                            <div id="modal-coluna-2" class="p-4 bg-gray-100 rounded-lg min-h-[400px] space-y-2"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="savePreferences" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">Salvar</button>
                        <button @click="modalOpen = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:w-auto sm:text-sm">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
    <script>
        function dashboard() {
            return {
                modalOpen: false,
                sortableColumns: [],
                init() {
                    this.$watch('modalOpen', (value) => {
                        if (value) {
                            this.initSortable();
                        } else {
                            this.destroySortable();
                        }
                    });
                },
                initSortable() {
                    this.populateModal();
                    const options = {
                        group: 'widgets',
                        animation: 150,
                        ghostClass: 'bg-blue-100'
                    };
                    this.sortableColumns.push(new Sortable(document.getElementById('available-widgets'), {...options, group: { name: 'widgets', pull: 'clone', put: false }, sort: false }));
                    this.sortableColumns.push(new Sortable(document.getElementById('modal-coluna-1'), options));
                    this.sortableColumns.push(new Sortable(document.getElementById('modal-coluna-2'), options));
                },
                destroySortable() {
                    this.sortableColumns.forEach(s => s.destroy());
                    this.sortableColumns = [];
                },
                populateModal() {
                    document.getElementById('modal-coluna-1').innerHTML = '';
                    document.getElementById('modal-coluna-2').innerHTML = '';
                    document.querySelectorAll('#coluna-1 > div').forEach(el => {
                        document.getElementById('modal-coluna-1').appendChild(this.createModalWidget(el));
                    });
                    document.querySelectorAll('#coluna-2 > div').forEach(el => {
                        document.getElementById('modal-coluna-2').appendChild(this.createModalWidget(el));
                    });
                },
                createModalWidget(originalEl) {
                    const newEl = document.createElement('div');
                    newEl.className = 'p-3 bg-white border rounded shadow-sm cursor-move';
                    newEl.dataset.id = originalEl.dataset.id;
                    newEl.dataset.component = originalEl.dataset.component;
                    const widgetName = @json(collect($availableWidgets)->pluck('name', 'id'));
                    newEl.innerText = widgetName[originalEl.dataset.id] || 'Widget Desconhecido';
                    return newEl;
                },
                savePreferences() {
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                    
                    // LINHA DE DEBUG ADICIONADA
                    alert('Token encontrado: ' + csrfToken);

                    const layout = [
                        Array.from(document.getElementById('modal-coluna-1').children).map(el => ({ id: el.dataset.id, component: el.dataset.component })),
                        Array.from(document.getElementById('modal-coluna-2').children).map(el => ({ id: el.dataset.id, component: el.dataset.component }))
                    ];

                    fetch('{{ route("dashboard.preferences.update") }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': csrfToken
                        },
                        body: JSON.stringify({ layout: layout })
                    })
                    .then(response => {
                        if (!response.ok) {
                           throw new Error('Erro na resposta do servidor: ' + response.status);
                        }
                        return response.json();
                    })
                    .then(data => {
                        if(data.success) {
                            window.location.reload();
                        } else {
                            alert('Ocorreu um erro ao salvar a dashboard.');
                        }
                    })
                    .catch((error) => {
                        console.error('Erro no fetch:', error);
                        alert('Ocorreu um erro de comunicação. Verifique o console do navegador para mais detalhes.');
                    });
                }
            }
        }
    </script>
    @endpush
</x-app-layout>
