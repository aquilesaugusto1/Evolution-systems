@csrf
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <label for="contrato_id" class="block font-medium text-sm text-gray-700">Contrato</label>
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
        <label for="consultor_id" class="block font-medium text-sm text-gray-700">Consultor</label>
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
        <label for="assunto" class="block font-medium text-sm text-gray-700">Assunto</label>
        <input type="text" name="assunto" id="assunto" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('assunto', $agenda->assunto ?? '') }}" required>
    </div>

    <div>
        <label for="data_hora" class="block font-medium text-sm text-gray-700">Data e Hora</label>
        <input type="datetime-local" name="data_hora" id="data_hora" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('data_hora', isset($agenda) ? $agenda->data_hora->format('Y-m-d\TH:i') : '') }}" required>
    </div>

    <div>
        <label for="status" class="block font-medium text-sm text-gray-700">Status</label>
        <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required>
            <option value="Agendada" {{ old('status', $agenda->status ?? 'Agendada') == 'Agendada' ? 'selected' : '' }}>Agendada</option>
            <option value="Realizada" {{ old('status', $agenda->status ?? '') == 'Realizada' ? 'selected' : '' }}>Realizada</option>
            <option value="Cancelada" {{ old('status', $agenda->status ?? '') == 'Cancelada' ? 'selected' : '' }}>Cancelada</option>
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="descricao" class="block font-medium text-sm text-gray-700">Descrição</label>
        <textarea name="descricao" id="descricao" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">{{ old('descricao', $agenda->descricao ?? '') }}</textarea>
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('agendas.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">
        {{ isset($agenda) ? 'Atualizar' : 'Salvar' }}
    </button>
</div>
