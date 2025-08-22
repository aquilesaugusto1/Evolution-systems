<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div>
        <x-input-label for="nome" :value="__('Nome do Imposto')" />
        {{-- Classes de cor para o modo claro adicionadas aqui --}}
        <x-text-input id="nome" class="block mt-1 w-full border-slate-300 bg-slate-100 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="text" name="nome" :value="old('nome', $imposto->nome ?? '')" required autofocus />
        <x-input-error :messages="$errors->get('nome')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="aliquota" :value="__('Alíquota (%)')" />
        {{-- Classes de cor para o modo claro adicionadas aqui --}}
        <x-text-input id="aliquota" class="block mt-1 w-full border-slate-300 bg-slate-100 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" type="number" name="aliquota" :value="old('aliquota', $imposto->aliquota ?? '')" required step="0.01" />
        <x-input-error :messages="$errors->get('aliquota')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tipo" :value="__('Tipo de Imposto')" />
        {{-- Classes de cor para o modo claro ajustadas aqui --}}
        <select name="tipo" id="tipo" class="block mt-1 w-full border-slate-300 bg-slate-100 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="federal" {{ old('tipo', $imposto->tipo ?? '') == 'federal' ? 'selected' : '' }}>Federal</option>
            <option value="estadual" {{ old('tipo', $imposto->tipo ?? '') == 'estadual' ? 'selected' : '' }}>Estadual</option>
            <option value="municipal" {{ old('tipo', $imposto->tipo ?? '') == 'municipal' ? 'selected' : '' }}>Municipal</option>
        </select>
        <x-input-error :messages="$errors->get('tipo')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="tipo_aliquota" :value="__('Tipo de Alíquota')" />
        {{-- Classes de cor para o modo claro ajustadas aqui --}}
        <select name="tipo_aliquota" id="tipo_aliquota" class="block mt-1 w-full border-slate-300 bg-slate-100 text-slate-700 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
            <option value="percentual" {{ old('tipo_aliquota', $imposto->tipo_aliquota ?? 'percentual') == 'percentual' ? 'selected' : '' }}>Percentual</option>
            <option value="fixa" {{ old('tipo_aliquota', $imposto->tipo_aliquota ?? '') == 'fixa' ? 'selected' : '' }}>Valor Fixo</option>
        </select>
        <x-input-error :messages="$errors->get('tipo_aliquota')" class="mt-2" />
    </div>

    <div class="md:col-span-2">
        <label for="ativo" class="flex items-center">
            <input id="ativo" type="checkbox" name="ativo" value="1" {{ old('ativo', $imposto->ativo ?? true) ? 'checked' : '' }} class="rounded border-slate-300 bg-slate-100 text-indigo-600 shadow-sm focus:ring-indigo-500">
            <span class="ms-2 text-sm text-gray-600">{{ __('Imposto Ativo') }}</span>
        </label>
    </div>
</div>

<div class="flex items-center justify-end mt-6">
    <a href="{{ route('impostos.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        {{ __('Cancelar') }}
    </a>

    <x-primary-button class="ms-4">
        {{ __('Salvar') }}
    </x-primary-button>
</div>