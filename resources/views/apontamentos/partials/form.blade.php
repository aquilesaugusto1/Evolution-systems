@if (isset($apontamento))
    {{-- Modo Edição --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block font-medium text-sm text-gray-700">Agenda</label>
            <p class="mt-1 p-2 bg-gray-100 rounded-md">{{ $apontamento->agenda->data_hora->format('d/m/Y') }} - {{ $apontamento->agenda->assunto }}</p>
        </div>
        <div>
            <label for="data_apontamento" class="block font-medium text-sm text-gray-700">Data do Apontamento</label>
            <input type="date" name="data_apontamento" id="data_apontamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('data_apontamento', $apontamento->data_apontamento->format('Y-m-d')) }}" required>
        </div>
        <div>
            <label for="horas_gastas" class="block font-medium text-sm text-gray-700">Horas Gastas</label>
            <input type="number" step="0.01" name="horas_gastas" id="horas_gastas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('horas_gastas', $apontamento->horas_gastas) }}" required>
        </div>
        @if(Auth::user()->funcao === 'admin')
            <div>
                <label for="faturado" class="block font-medium text-sm text-gray-700">Status Fatura</label>
                 <select name="faturado" id="faturado" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                    <option value="0" {{ old('faturado', $apontamento->faturado) == 0 ? 'selected' : '' }}>Não Faturado</option>
                    <option value="1" {{ old('faturado', $apontamento->faturado) == 1 ? 'selected' : '' }}>Faturado</option>
                </select>
            </div>
        @endif
        <div class="md:col-span-2">
            <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição das Atividades</label>
            <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('descricao', $apontamento->descricao) }}</textarea>
        </div>
    </div>
@else
    {{-- Modo Criação --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="md:col-span-2">
            <label for="agenda_id" class="block font-medium text-sm text-gray-700">Agenda Realizada (Apenas agendas sem apontamento são listadas)</label>
            <select name="agenda_id" id="agenda_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
                <option value="">Selecione uma agenda</option>
                @foreach($agendas as $agenda)
                    <option value="{{ $agenda->id }}" {{ old('agenda_id') == $agenda->id ? 'selected' : '' }}>
                        {{ $agenda->data_hora->format('d/m/Y') }} - {{ $agenda->empresaParceira->nome_empresa }} - {{ $agenda->assunto }}
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="data_apontamento" class="block font-medium text-sm text-gray-700">Data do Apontamento</label>
            <input type="date" name="data_apontamento" id="data_apontamento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('data_apontamento', date('Y-m-d')) }}" required>
        </div>
        <div>
            <label for="horas_gastas" class="block font-medium text-sm text-gray-700">Horas Gastas</label>
            <input type="number" step="0.01" name="horas_gastas" id="horas_gastas" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('horas_gastas') }}" required>
        </div>
        <div class="md:col-span-2">
            <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição das Atividades</label>
            <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>{{ old('descricao') }}</textarea>
        </div>
    </div>
@endif

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('apontamentos.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
        {{ isset($apontamento) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
