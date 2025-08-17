@if ($errors->any())
    <div class="alert alert-danger mb-4 rounded-md bg-red-50 p-4">
        <div class="flex">
            <div class="flex-shrink-0">
                <svg class="h-5 w-5 text-red-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.28 7.22a.75.75 0 00-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 101.06 1.06L10 11.06l1.72 1.72a.75.75 0 101.06-1.06L11.06 10l1.72-1.72a.75.75 0 00-1.06-1.06L10 8.94 8.28 7.22z" clip-rule="evenodd" /></svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">Foram encontrados {{ count($errors->all()) }} erros:</h3>
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

<div class="space-y-8">
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Pessoais e Endereço</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div><x-input-label for="nome" :value="__('Nome')" /><x-text-input id="nome" name="nome" type="text" class="mt-1 block w-full" :value="old('nome', $colaborador->nome ?? '')" required autofocus /></div>
                <div><x-input-label for="data_nascimento" :value="__('Data de Nascimento')" /><x-text-input id="data_nascimento" name="data_nascimento" type="date" class="mt-1 block w-full" :value="old('data_nascimento', isset($colaborador) ? $colaborador->data_nascimento?->format('Y-m-d') : '')" /></div>
                <div><x-input-label for="naturalidade" :value="__('Natural de:')" /><x-text-input id="naturalidade" name="naturalidade" type="text" class="mt-1 block w-full" :value="old('naturalidade', $colaborador->naturalidade ?? '')" /></div>
                <div><x-input-label for="rua" :value="__('Rua')" /><x-text-input id="rua" name="endereco[rua]" type="text" class="mt-1 block w-full" :value="old('endereco.rua', $colaborador->endereco['rua'] ?? '')" /></div>
                <div><x-input-label for="cidade" :value="__('Cidade')" /><x-text-input id="cidade" name="endereco[cidade]" type="text" class="mt-1 block w-full" :value="old('endereco.cidade', $colaborador->endereco['cidade'] ?? '')" /></div>
                <div><x-input-label for="pais" :value="__('País')" /><x-text-input id="pais" name="endereco[pais]" type="text" class="mt-1 block w-full" :value="old('endereco.pais', $colaborador->endereco['pais'] ?? '')" /></div>
            </div>
            <div class="space-y-4">
                <div><x-input-label for="sobrenome" :value="__('Sobrenome')" /><x-text-input id="sobrenome" name="sobrenome" type="text" class="mt-1 block w-full" :value="old('sobrenome', $colaborador->sobrenome ?? '')" /></div>
                <div><x-input-label for="nacionalidade" :value="__('Nacionalidade')" /><x-text-input id="nacionalidade" name="nacionalidade" type="text" class="mt-1 block w-full" :value="old('nacionalidade', $colaborador->nacionalidade ?? '')" /></div>
                <div><x-input-label for="bairro" :value="__('Bairro')" /><x-text-input id="bairro" name="endereco[bairro]" type="text" class="mt-1 block w-full" :value="old('endereco.bairro', $colaborador->endereco['bairro'] ?? '')" /></div>
                <div><x-input-label for="estado" :value="__('Estado')" /><x-text-input id="estado" name="endereco[estado]" type="text" class="mt-1 block w-full" :value="old('endereco.estado', $colaborador->endereco['estado'] ?? '')" /></div>
            </div>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Profissionais</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div><x-input-label for="email" :value="__('Email Progmud (Login)')" /><x-text-input id="email" name="email" type="email" class="mt-1 block w-full" :value="old('email', $colaborador->email ?? '')" required /></div>
                <div>
                    <x-input-label for="status" :value="__('Status')" />
                    <select name="status" id="status" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="Ativo" @selected(old('status', $colaborador->status ?? 'Ativo') == 'Ativo')>Ativo</option>
                        <option value="Inativo" @selected(old('status', $colaborador->status ?? '') == 'Inativo')>Inativo</option>
                        <option value="Férias" @selected(old('status', $colaborador->status ?? '') == 'Férias')>Férias</option>
                    </select>
                </div>
                <div><x-input-label for="cargo" :value="__('Cargo')" /><x-text-input id="cargo" name="cargo" type="text" class="mt-1 block w-full" :value="old('cargo', $colaborador->cargo ?? '')" /></div>
                <div>
                    <x-input-label for="select-techleads" :value="__('Subordinado a (Tech Leads)')" />
                    <select name="tech_leads[]" id="select-techleads" multiple>
                        @php
                            $selectedLeads = old('tech_leads', isset($colaborador) ? $colaborador->techLeads->pluck('id')->toArray() : []);
                        @endphp
                        @foreach($techLeads as $lead)
                            <option value="{{ $lead->id }}" @selected(in_array($lead->id, $selectedLeads))>
                                {{ $lead->nome }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="space-y-4">
                <div><x-input-label for="email_totvs_partner" :value="__('Email Totvs Partner')" /><x-text-input id="email_totvs_partner" name="email_totvs_partner" type="email" class="mt-1 block w-full" :value="old('email_totvs_partner', $colaborador->email_totvs_partner ?? '')" /></div>
                <div>
                    <x-input-label for="tipo_contrato" :value="__('Tipo de contrato')" />
                    <select name="tipo_contrato" id="tipo_contrato" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Selecione...</option>
                        @foreach(['CLT', 'PJ Mensal', 'PJ Horista', 'Estágio', 'Outros'] as $tipo)
                            <option value="{{ $tipo }}" @selected(old('tipo_contrato', $colaborador->tipo_contrato ?? '') == $tipo)>{{ $tipo }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="funcao" :value="__('Perfil (Função)')" />
                    <select name="funcao" id="funcao" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                        <option value="">Selecione...</option>
                        @php
                            $perfis = ['administrativo' => 'Administrativo', 'consultor' => 'Consultor', 'techlead' => 'Tech Lead', 'coordenador_operacoes' => 'Coordenador Operações', 'coordenador_tecnico' => 'Coordenador Técnico', 'comercial' => 'Comercial'];
                        @endphp
                        @foreach($perfis as $value => $label)
                            <option value="{{ $value }}" @selected(old('funcao', $colaborador->funcao ?? '') == $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <x-input-label for="nivel" :value="__('Nível')" />
                    <select name="nivel" id="nivel" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        <option value="">Selecione...</option>
                        @foreach(['Junior', 'Pleno', 'Sênior'] as $n)
                            <option value="{{ $n }}" @selected(old('nivel', $colaborador->nivel ?? '') == $n)>{{ $n }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
    </div>
    
    <div id="dados_empresa_prestador_container" class="hidden">
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados da Empresa Prestadora (PJ)</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div><x-input-label for="razao_social" :value="__('Razão Social')" /><x-text-input id="razao_social" name="dados_empresa_prestador[razao_social]" type="text" class="mt-1 block w-full" :value="old('dados_empresa_prestador.razao_social', $colaborador->dados_empresa_prestador['razao_social'] ?? '')" /></div>
            <div><x-input-label for="cnpj" :value="__('CNPJ')" /><x-text-input id="cnpj" name="dados_empresa_prestador[cnpj]" type="text" class="mt-1 block w-full" :value="old('dados_empresa_prestador.cnpj', $colaborador->dados_empresa_prestador['cnpj'] ?? '')" /></div>
        </div>
    </div>
    
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Dados Bancários</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><x-input-label for="banco" :value="__('Banco')" /><x-text-input id="banco" name="dados_bancarios[banco]" type="text" class="mt-1 block w-full" :value="old('dados_bancarios.banco', $colaborador->dados_bancarios['banco'] ?? '')" /></div>
            <div><x-input-label for="agencia" :value="__('Agência')" /><x-text-input id="agencia" name="dados_bancarios[agencia]" type="text" class="mt-1 block w-full" :value="old('dados_bancarios.agencia', $colaborador->dados_bancarios['agencia'] ?? '')" /></div>
            <div><x-input-label for="conta" :value="__('Conta')" /><x-text-input id="conta" name="dados_bancarios[conta]" type="text" class="mt-1 block w-full" :value="old('dados_bancarios.conta', $colaborador->dados_bancarios['conta'] ?? '')" /></div>
        </div>
    </div>

    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Credenciais de Acesso</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <x-input-label for="password" :value="__('Senha')" />
                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
                @if(isset($colaborador))
                    <small class="text-gray-500">Deixe em branco para não alterar.</small>
                @endif
            </div>
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirmar Senha')" />
                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" />
            </div>
        </div>
    </div>
</div>

<div class="flex items-center justify-end mt-8 pt-5 border-t border-gray-200">
    <a href="{{ route('colaboradores.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
    <x-primary-button>
        {{ isset($colaborador) ? 'Atualizar Colaborador' : 'Cadastrar Colaborador' }}
    </x-primary-button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        
        new TomSelect('#select-techleads',{
            plugins: ['remove_button'],
            placeholder: 'Selecione os gestores...'
        });

       
        const tipoContratoSelect = document.getElementById('tipo_contrato');
        const dadosEmpresaContainer = document.getElementById('dados_empresa_prestador_container');
        const pjTypes = ['PJ Mensal', 'PJ Horista'];
        function toggleDadosEmpresa() {
            if (pjTypes.includes(tipoContratoSelect.value)) {
                dadosEmpresaContainer.classList.remove('hidden');
            } else {
                dadosEmpresaContainer.classList.add('hidden');
            }
        }
        tipoContratoSelect.addEventListener('change', toggleDadosEmpresa);
        toggleDadosEmpresa();
    });
</script>
@endpush