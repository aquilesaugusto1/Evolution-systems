<?php

namespace Tests\Feature;

use App\Models\EmpresaParceira;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmpresaParceiraTest extends TestCase
{
    use RefreshDatabase; // Este trait reseta o banco de dados a cada teste

    /**
     * Prepara o ambiente para cada teste.
     * Cria um usuário administrador e faz login.
     */
    protected function setUp(): void
    {
        parent::setUp();

        // Cria um usuário com a função de admin para ter as permissões necessárias
        $adminUser = User::factory()->create(['funcao' => 'admin']);
        
        // Atua como este usuário para todas as requisições do teste
        $this->actingAs($adminUser);
    }

    /** @test */
    public function a_pagina_de_cadastro_de_empresa_pode_ser_renderizada()
    {
        // Ação: Faz uma requisição GET para a página de criação
        $response = $this->get(route('empresas.create'));

        // Verificação: Garante que a página foi carregada com sucesso (status 200)
        $response->assertStatus(200);
        $response->assertSee('Cadastrar Nova Empresa Parceira');
    }

    /** @test */
    public function um_administrador_pode_cadastrar_uma_nova_empresa_parceira()
    {
        // Preparação: Cria os dados para a nova empresa
        $dadosEmpresa = [
            'nome_empresa' => 'Nova Empresa de Teste',
            'nome_fantasia' => 'Empresa Teste',
            'cnpj' => '56.343.625/0001-66', // CNPJ válido para teste
            'email_contato' => 'contato@empresa.com',
            'telefone_contato' => '11999998888',
            'status' => 'Ativo',
        ];

        // Ação: Envia uma requisição POST para a rota de armazenamento
        $response = $this->post(route('empresas.store'), $dadosEmpresa);

        // Verificações:
        // 1. Garante que o usuário foi redirecionado para a lista de empresas
        $response->assertRedirect(route('empresas.index'));
        
        // 2. Garante que a mensagem de sucesso está na sessão
        $response->assertSessionHas('success', 'Empresa parceira cadastrada com sucesso.');

        // 3. Garante que os dados da empresa existem no banco de dados
        $this->assertDatabaseHas('empresas_parceiras', [
            'cnpj' => '56343625000166' // Verifica o CNPJ sem formatação
        ]);
    }

    /** @test */
    public function o_cadastro_falha_se_o_cnpj_for_invalido_ou_ja_existir()
    {
        // Preparação: Cria uma empresa com um CNPJ que já existe
        EmpresaParceira::factory()->create(['cnpj' => '56.343.625/0001-66']);

        $dadosEmpresa = [
            'nome_empresa' => 'Empresa com CNPJ Repetido',
            'nome_fantasia' => 'Teste CNPJ',
            'cnpj' => '56.343.625/0001-66', // CNPJ repetido
            'email_contato' => 'contato@repetido.com',
            'telefone_contato' => '11999998888',
            'status' => 'Ativo',
        ];

        // Ação: Envia a requisição POST
        $response = $this->post(route('empresas.store'), $dadosEmpresa);

        // Verificação: Garante que a validação falhou e retornou um erro para o campo 'cnpj'
        $response->assertSessionHasErrors('cnpj');
    }
    
    /** @test */
    public function o_cadastro_falha_se_o_nome_da_empresa_nao_for_fornecido()
    {
        // Preparação: Dados sem o campo 'nome_empresa'
        $dadosEmpresa = [
            'nome_fantasia' => 'Empresa Sem Nome',
            'cnpj' => '32.613.515/0001-28',
            'email_contato' => 'contato@semnome.com',
            'telefone_contato' => '11999998888',
            'status' => 'Ativo',
        ];

        // Ação: Envia a requisição POST
        $response = $this->post(route('empresas.store'), $dadosEmpresa);

        // Verificação: Garante que a validação falhou e retornou um erro para o campo 'nome_empresa'
        $response->assertSessionHasErrors('nome_empresa');
    }
}
