<x-app-layout>
    @push('styles')
    {{-- Adiciona os estilos do FullCalendar --}}
    <style>
        #calendar {
            max-width: 1100px;
            margin: 0 auto;
        }
        /* Estilo para o switch 'Faturável' */
        .toggle-switch {
            position: relative;
            display: inline-block;
            width: 50px;
            height: 28px;
        }
        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 28px;
        }
        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        input:checked + .slider {
            background-color: #4f46e5; /* Indigo */
        }
        input:checked + .slider:before {
            transform: translateX(22px);
        }
    </style>
    @endpush

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div id="calendar"></div>
            </div>
        </div>
    </div>

    <!-- Modal de Apontamento -->
    <div id="apontamentoModal" class="fixed z-50 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <form id="apontamentoForm" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center">
                             <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Lançar Apontamento</h3>
                             <button type="button" id="closeModalButton" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Fechar</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                             </button>
                        </div>
                        
                        <div class="mt-4 space-y-4">
                            <input type="hidden" id="agenda_id" name="agenda_id">
                            <div id="info_rejeicao" class="hidden p-3 bg-red-50 border-l-4 border-red-400 text-red-700">
                                <p class="font-bold text-sm">Motivo da Rejeição:</p>
                                <p class="text-sm" id="motivo_rejeicao_text"></p>
                            </div>
                            
                            <div class="text-sm">
                                <p><strong>Consultor:</strong> <span id="modal_consultor"></span></p>
                                <p><strong>Contrato:</strong> <span id="modal_contrato"></span></p>
                                <p><strong>Assunto:</strong> <span id="modal_assunto"></span></p>
                            </div>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 items-end">
                                <div>
                                    <label for="hora_inicio" class="block text-sm font-medium text-gray-700">Início</label>
                                    <input type="time" name="hora_inicio" id="hora_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div>
                                    <label for="hora_fim" class="block text-sm font-medium text-gray-700">Fim</label>
                                    <input type="time" name="hora_fim" id="hora_fim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                </div>
                                <div class="bg-gray-100 p-2 rounded-md text-center">
                                    <label class="block text-sm font-medium text-gray-700">Duração</label>
                                    <span id="duracao" class="text-lg font-bold text-gray-900">00:00:00</span>
                                </div>
                            </div>
                            
                            <div>
                                <label for="descricao" class="block text-sm font-medium text-gray-700">Descrição das Atividades *</label>
                                <textarea id="descricao" name="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required></textarea>
                            </div>
                            
                            <div>
                                <label for="anexo" class="block text-sm font-medium text-gray-700">Anexo (PDF Obrigatório) *</label>
                                <input type="file" name="anexo" id="anexo" accept=".pdf" class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100" required>
                                <div id="anexo_existente" class="mt-2 text-sm"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <label for="faturavel" class="block text-sm font-medium text-gray-700">Faturável?</label>
                                <label class="toggle-switch">
                                    <input type="checkbox" id="faturavel" name="faturavel" value="1" checked>
                                    <span class="slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" id="saveButton" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">Salvar</button>
                        <button type="button" id="cancelModalButton" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    @push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const calendarEl = document.getElementById('calendar');
            const modal = document.getElementById('apontamentoModal');
            const form = document.getElementById('apontamentoForm');
            const closeModalButton = document.getElementById('closeModalButton');
            const cancelModalButton = document.getElementById('cancelModalButton');
            const horaInicioInput = document.getElementById('hora_inicio');
            const horaFimInput = document.getElementById('hora_fim');
            const duracaoSpan = document.getElementById('duracao');

            function calcularDuracao() {
                const inicio = horaInicioInput.value;
                const fim = horaFimInput.value;

                if (inicio && fim) {
                    const inicioDate = new Date(`1970-01-01T${inicio}:00`);
                    const fimDate = new Date(`1970-01-01T${fim}:00`);

                    if (fimDate > inicioDate) {
                        let diff = fimDate.getTime() - inicioDate.getTime();
                        const hours = Math.floor(diff / 1000 / 60 / 60);
                        diff -= hours * 1000 * 60 * 60;
                        const minutes = Math.floor(diff / 1000 / 60);
                        
                        duracaoSpan.textContent = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}:00`;
                    } else {
                        duracaoSpan.textContent = '00:00:00';
                    }
                }
            }

            horaInicioInput.addEventListener('change', calcularDuracao);
            horaFimInput.addEventListener('change', calcularDuracao);

            const calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: { left: 'prev,next today', center: 'title', right: 'dayGridMonth,timeGridWeek,timeGridDay' },
                initialView: 'dayGridMonth',
                locale: 'pt-br',
                buttonText: { today: 'Hoje', month: 'Mês', week: 'Semana', day: 'Dia' },
                events: '{{ route("api.agendas") }}',
                eventClick: function(info) {
                    const props = info.event.extendedProps;
                    
                    form.reset();
                    document.getElementById('agenda_id').value = info.event.id;
                    document.getElementById('modal_consultor').textContent = props.consultor;
                    document.getElementById('modal_assunto').textContent = props.assunto;
                    document.getElementById('modal_contrato').textContent = props.contrato;
                    document.getElementById('hora_inicio').value = props.hora_inicio;
                    document.getElementById('hora_fim').value = props.hora_fim;
                    document.getElementById('descricao').value = props.descricao;
                    document.getElementById('faturavel').checked = props.faturavel !== false; // Default to true
                    document.getElementById('anexo_existente').innerHTML = props.anexo_url ? `<a href="${props.anexo_url}" target="_blank" class="text-indigo-600 hover:underline">Ver anexo atual</a>` : '';
                    
                    const anexoInput = document.getElementById('anexo');
                    anexoInput.required = !props.anexo_url;

                    const infoRejeicao = document.getElementById('info_rejeicao');
                    if (props.status === 'Rejeitado' && props.motivo_rejeicao) {
                        document.getElementById('motivo_rejeicao_text').textContent = props.motivo_rejeicao;
                        infoRejeicao.classList.remove('hidden');
                    } else {
                        infoRejeicao.classList.add('hidden');
                    }

                    const saveButton = document.getElementById('saveButton');
                    const inputs = form.querySelectorAll('input, textarea, button, select');
                    if (props.status === 'Aprovado') {
                        inputs.forEach(el => el.disabled = true);
                        saveButton.textContent = 'Aprovado';
                        closeModalButton.disabled = false;
                        cancelModalButton.disabled = false;
                    } else {
                        inputs.forEach(el => el.disabled = false);
                        saveButton.textContent = 'Salvar';
                    }
                    
                    calcularDuracao();
                    modal.classList.remove('hidden');
                }
            });

            calendar.render();

            const hideModal = () => modal.classList.add('hidden');
            closeModalButton.addEventListener('click', hideModal);
            cancelModalButton.addEventListener('click', hideModal);

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const formData = new FormData(form);
                const saveButton = document.getElementById('saveButton');
                saveButton.disabled = true;
                saveButton.textContent = 'Salvando...';

                fetch('{{ route("apontamentos.store") }}', {
                    method: 'POST',
                    headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                    body: formData
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => { throw err; });
                    }
                    return response.json();
                })
                .then(result => {
                    alert('Sucesso: ' + result.message);
                    hideModal();
                    calendar.refetchEvents();
                })
                .catch(error => {
                    console.error('Error:', error);
                    let errorMessage = 'Ocorreu um erro ao salvar.';
                    if (error.errors) {
                        errorMessage = Object.values(error.errors).join('\n');
                    } else if (error.message) {
                        errorMessage = error.message;
                    }
                    alert('Erro: ' + errorMessage);
                })
                .finally(() => {
                    saveButton.disabled = false;
                    saveButton.textContent = 'Salvar';
                });
            });
        });
    </script>
    @endpush
</x-app-layout>
