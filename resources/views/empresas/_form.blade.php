@csrf
<div class="space-y-8">
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Informações da Empresa</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="md:col-span-2"><label for="nome_empresa" class="block font-medium text-sm text-gray-700">Nome da Empresa</label><input type="text" name="nome_empresa" id="nome_empresa" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('nome_empresa', $empresa->nome_empresa ?? '') }}" required></div>
            <div><label for="cnpj" class="block font-medium text-sm text-gray-700">CNPJ</label><input type="text" name="cnpj" id="cnpj" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('cnpj', $empresa->cnpj ?? '') }}" required maxlength="18"></div>
            <div><label for="status" class="block font-medium text-sm text-gray-700">Status</label><select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" required><option value="Ativo" @selected(old('status', $empresa->status ?? 'Ativo') == 'Ativo')>Ativo</option><option value="Inativo" @selected(old('status', $empresa->status ?? '') == 'Inativo')>Inativo</option></select></div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Endereço</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-6 gap-6">
            <div class="md:col-span-4"><label for="logradouro" class="block font-medium text-sm text-gray-700">Logradouro</label><input type="text" name="endereco_completo[logradouro]" id="logradouro" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.logradouro', $empresa->endereco_completo['logradouro'] ?? '') }}"></div>
            <div class="md:col-span-2"><label for="numero" class="block font-medium text-sm text-gray-700">Número</label><input type="text" name="endereco_completo[numero]" id="numero" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.numero', $empresa->endereco_completo['numero'] ?? '') }}"></div>
            <div class="md:col-span-3"><label for="complemento" class="block font-medium text-sm text-gray-700">Complemento</label><input type="text" name="endereco_completo[complemento]" id="complemento" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.complemento', $empresa->endereco_completo['complemento'] ?? '') }}"></div>
            <div class="md:col-span-3"><label for="bairro" class="block font-medium text-sm text-gray-700">Bairro</label><input type="text" name="endereco_completo[bairro]" id="bairro" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.bairro', $empresa->endereco_completo['bairro'] ?? '') }}"></div>
            <div class="md:col-span-3"><label for="cidade" class="block font-medium text-sm text-gray-700">Cidade</label><input type="text" name="endereco_completo[cidade]" id="cidade" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.cidade', $empresa->endereco_completo['cidade'] ?? '') }}"></div>
            <div class="md:col-span-1"><label for="uf" class="block font-medium text-sm text-gray-700">UF</label><input type="text" name="endereco_completo[uf]" id="uf" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.uf', $empresa->endereco_completo['uf'] ?? '') }}"></div>
            <div class="md:col-span-2"><label for="cep" class="block font-medium text-sm text-gray-700">CEP</label><input type="text" name="endereco_completo[cep]" id="cep" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('endereco_completo.cep', $empresa->endereco_completo['cep'] ?? '') }}"></div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Principal</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label for="principal_nome" class="block font-medium text-sm text-gray-700">Nome</label><input type="text" name="contato_principal[nome]" id="principal_nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_principal.nome', $empresa->contato_principal['nome'] ?? '') }}"></div>
            <div><label for="principal_email" class="block font-medium text-sm text-gray-700">Email</label><input type="email" name="contato_principal[email]" id="principal_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_principal.email', $empresa->contato_principal['email'] ?? '') }}"></div>
            <div><label for="principal_telefone" class="block font-medium text-sm text-gray-700">Telefone</label><input type="text" name="contato_principal[telefone]" id="principal_telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_principal.telefone', $empresa->contato_principal['telefone'] ?? '') }}"></div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Comercial</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label for="comercial_nome" class="block font-medium text-sm text-gray-700">Nome</label><input type="text" name="contato_comercial[nome]" id="comercial_nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_comercial.nome', $empresa->contato_comercial['nome'] ?? '') }}"></div>
            <div><label for="comercial_email" class="block font-medium text-sm text-gray-700">Email</label><input type="email" name="contato_comercial[email]" id="comercial_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_comercial.email', $empresa->contato_comercial['email'] ?? '') }}"></div>
            <div><label for="comercial_telefone" class="block font-medium text-sm text-gray-700">Telefone</label><input type="text" name="contato_comercial[telefone]" id="comercial_telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_comercial.telefone', $empresa->contato_comercial['telefone'] ?? '') }}"></div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Financeiro</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label for="financeiro_nome" class="block font-medium text-sm text-gray-700">Nome</label><input type="text" name="contato_financeiro[nome]" id="financeiro_nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_financeiro.nome', $empresa->contato_financeiro['nome'] ?? '') }}"></div>
            <div><label for="financeiro_email" class="block font-medium text-sm text-gray-700">Email</label><input type="email" name="contato_financeiro[email]" id="financeiro_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_financeiro.email', $empresa->contato_financeiro['email'] ?? '') }}"></div>
            <div><label for="financeiro_telefone" class="block font-medium text-sm text-gray-700">Telefone</label><input type="text" name="contato_financeiro[telefone]" id="financeiro_telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_financeiro.telefone', $empresa->contato_financeiro['telefone'] ?? '') }}"></div>
        </div>
    </div>
    <div>
        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Contato Técnico</h3>
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div><label for="tecnico_nome" class="block font-medium text-sm text-gray-700">Nome</label><input type="text" name="contato_tecnico[nome]" id="tecnico_nome" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_tecnico.nome', $empresa->contato_tecnico['nome'] ?? '') }}"></div>
            <div><label for="tecnico_email" class="block font-medium text-sm text-gray-700">Email</label><input type="email" name="contato_tecnico[email]" id="tecnico_email" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_tecnico.email', $empresa->contato_tecnico['email'] ?? '') }}"></div>
            <div><label for="tecnico_telefone" class="block font-medium text-sm text-gray-700">Telefone</label><input type="text" name="contato_tecnico[telefone]" id="tecnico_telefone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ old('contato_tecnico.telefone', $empresa->contato_tecnico['telefone'] ?? '') }}"></div>
        </div>
    </div>
</div>
<div class="flex items-center justify-end mt-6 pt-5 border-t">
    <a href="{{ route('empresas.index') }}" class="text-gray-600 hover:text-gray-900 mr-4">Cancelar</a>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-md shadow-sm">{{ isset($empresa) && $empresa->exists ? 'Atualizar Cliente' : 'Salvar Cliente' }}</button>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const cnpjInput = document.getElementById('cnpj');

        cnpjInput.addEventListener('input', function (e) {
            let value = e.target.value.replace(/\D/g, '');
            value = value.replace(/^(\d{2})(\d)/, '$1.$2');
            value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            value = value.replace(/\.(\d{3})(\d)/, '.$1/$2');
            value = value.replace(/(\d{4})(\d)/, '$1-$2');
            e.target.value = value.slice(0, 18);
        });
    });
</script>
@endpush
