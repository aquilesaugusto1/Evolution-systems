<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 bg-white border-b border-gray-200">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                        Editar Agenda
                    </h2>

                    @if ($errors->any())
                        <div class="mb-4 rounded-md bg-red-50 p-4">
                            <div class="flex">
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-red-800">Ocorreram alguns erros:</h3>
                                    <div class="mt-2 text-sm text-red-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('agendas.update', $agenda) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <x-input-label for="contrato_id" :value="__('Contrato')" />
                                <select name="contrato_id" id="contrato_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Selecione um contrato</option>
                                    @foreach ($contratos as $contrato)
                                        <option value="{{ $contrato->id }}" {{ old('contrato_id', $agenda->contrato_id ?? '') == $contrato->id ? 'selected' : '' }}>
                                            {{ $contrato->empresaParceira->nome_empresa }} (#{{ $contrato->id }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <x-input-label for="consultor_id" :value="__('Consultor')" />
                                <select name="consultor_id" id="consultor_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="">Selecione um contrato primeiro</option>
                                    @if(isset($consultores))
                                        @foreach ($consultores as $consultor)
                                            <option value="{{ $consultor->id }}" {{ old('consultor_id', $agenda->consultor_id ?? '') == $consultor->id ? 'selected' : '' }}>
                                                {{ $consultor->nome }} {{ $consultor->sobrenome }}
                                            </option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="assunto" :value="__('Assunto')" />
                                <input type="text" name="assunto" id="assunto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('assunto', $agenda->assunto ?? '') }}" required>
                            </div>

                            <div class="md:col-span-2">
                                <label for="faturavel" class="flex items-center">
                                    <input type="checkbox" name="faturavel" id="faturavel" value="1" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" {{ old('faturavel', $agenda->faturavel ?? false) ? 'checked' : '' }}>
                                    <span class="ml-2 text-sm text-gray-600">Faturável</span>
                                </label>
                            </div>
                            
                            <div>
                                <x-input-label for="data" :value="__('Data')" />
                                <input type="date" name="data" id="data" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('data', isset($agenda) ? $agenda->data->format('Y-m-d') : '') }}" required>
                            </div>

                            <div>
                                <x-input-label for="tipo_periodo" :value="__('Tipo de Período')" />
                                <select name="tipo_periodo" id="tipo_periodo" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="Período Inteiro" @selected(old('tipo_periodo', $agenda->tipo_periodo ?? '') == 'Período Inteiro')>Período Inteiro (9h)</option>
                                    <option value="Meio Período" @selected(old('tipo_periodo', $agenda->tipo_periodo ?? '') == 'Meio Período')>Meio Período</option>
                                    <option value="Personalizado" @selected(old('tipo_periodo', $agenda->tipo_periodo ?? '') == 'Personalizado')>Personalizado</option>
                                </select>
                            </div>

                            <div>
                                <x-input-label for="hora_inicio" :value="__('Hora de Início')" />
                                <input type="time" name="hora_inicio" id="hora_inicio" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('hora_inicio', $agenda->hora_inicio ?? '') }}" required>
                            </div>

                            <div>
                                <x-input-label for="hora_fim" :value="__('Hora de Fim')" />
                                <input type="time" name="hora_fim" id="hora_fim" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('hora_fim', $agenda->hora_fim ?? '') }}">
                            </div>

                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                                    <option value="Agendada" @selected(old('status', $agenda->status ?? 'Agendada') == 'Agendada')>Agendada</option>
                                    <option value="Realizada" @selected(old('status', $agenda->status ?? '') == 'Realizada')>Realizada</option>
                                    <option value="Cancelada" @selected(old('status', $agenda->status ?? '') == 'Cancelada')>Cancelada</option>
                                </select>
                            </div>

                            <div class="md:col-span-2">
                                <x-input-label for="descricao" :value="__('Descrição')" />
                                <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descricao', $agenda->descricao ?? '') }}</textarea>
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-6">
                            <a href="{{ route('agendas.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">
                                Atualizar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const contratoSelect = document.getElementById('contrato_id');
        const consultorSelect = document.getElementById('consultor_id');
        const currentConsultorId = "{{ old('consultor_id', $agenda->consultor_id ?? '') }}";

        function fetchConsultores(contratoId, selectedConsultorId = null) {
            consultorSelect.innerHTML = '<option value="">Carregando...</option>';
            consultorSelect.disabled = true;

            if (!contratoId) {
                consultorSelect.innerHTML = '<option value="">Selecione um contrato para ver os consultores</option>';
                return;
            }

            fetch(`/api/contratos/${contratoId}/consultores`)
                .then(response => response.json())
                .then(consultores => {
                    consultorSelect.innerHTML = '<option value="">Selecione um consultor</option>';
                    if (consultores.length > 0) {
                        consultores.forEach(consultor => {
                            const option = document.createElement('option');
                            option.value = consultor.id;
                            option.textContent = `${consultor.nome} ${consultor.sobrenome || ''}`.trim();
                            if (consultor.id == selectedConsultorId) {
                                option.selected = true;
                            }
                            consultorSelect.appendChild(option);
                        });
                        consultorSelect.disabled = false;
                    } else {
                        consultorSelect.innerHTML = '<option value="">Nenhum consultor associado a este contrato</option>';
                    }
                })
                .catch(error => {
                    console.error('Erro ao buscar consultores:', error);
                    consultorSelect.innerHTML = '<option value="">Erro ao carregar consultores</option>';
                });
        }

        contratoSelect.addEventListener('change', function () {
            fetchConsultores(this.value);
        });

        if (contratoSelect.value) {
            fetchConsultores(contratoSelect.value, currentConsultorId);
        }

        // Lógica para o período
        const tipoPeriodoSelect = document.getElementById('tipo_periodo');
        const horaInicioInput = document.getElementById('hora_inicio');
        const horaFimInput = document.getElementById('hora_fim');

        function atualizarHoraFim() {
            if (tipoPeriodoSelect.value === 'Período Inteiro' && horaInicioInput.value) {
                horaFimInput.disabled = true;
                const [horas, minutos] = horaInicioInput.value.split(':').map(Number);
                const dataInicio = new Date();
                dataInicio.setHours(horas, minutos, 0, 0);
                dataInicio.setHours(dataInicio.getHours() + 9);
                
                const horasFim = String(dataInicio.getHours()).padStart(2, '0');
                const minutosFim = String(dataInicio.getMinutes()).padStart(2, '0');
                
                horaFimInput.value = `${horasFim}:${minutosFim}`;
            } else {
                horaFimInput.disabled = false;
            }
        }

        tipoPeriodoSelect.addEventListener('change', atualizarHoraFim);
        horaInicioInput.addEventListener('change', atualizarHoraFim);

        atualizarHoraFim();
    });
    </script>
    @endpush
</x-app-layout>