<?php

namespace App\Services;

use App\Models\EmpresaParceira;
use App\Models\Fatura;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    protected $http;

    public function __construct()
    {
        $this->http = Http::withHeaders([
            'access_token' => config('asaas.api_key'),
            'Content-Type' => 'application/json',
        ])->baseUrl(config('asaas.api_url'));
    }

    /**
     * Cria ou recupera um cliente no Asaas.
     */
    public function criarOuRecuperarCliente(EmpresaParceira $empresa): ?string
    {
        if ($empresa->asaas_customer_id) {
            return $empresa->asaas_customer_id;
        }

        // CORREÇÃO: Usando o campo 'nome_empresa' que é o correto.
        $nomeCliente = $empresa->nome_empresa;
        if (empty($nomeCliente)) {
            throw new \InvalidArgumentException("O cliente (ID: {$empresa->id}) não possui um 'Nome da Empresa' cadastrado. Por favor, atualize o cadastro.");
        }

        try {
            $response = $this->http->post('/customers', [
                'name' => $nomeCliente,
                'cpfCnpj' => $empresa->cnpj,
                'email' => optional($empresa->contato_financeiro)['email'] ?? optional($empresa->contato_comercial)['email'],
                'phone' => optional($empresa->contato_principal)['telefone'] ?? '',
                'notificationDisabled' => false,
            ]);

            if ($response->successful()) {
                $customerId = $response->json('id');
                $empresa->update(['asaas_customer_id' => $customerId]);
                return $customerId;
            }

            if ($response->status() === 400 && str_contains($response->body(), 'cpfCnpj_already_used')) {
                Log::info('Cliente já existe no Asaas, buscando pelo CNPJ: ' . $empresa->cnpj);
                $response = $this->http->get('/customers', ['cpfCnpj' => $empresa->cnpj]);
                if($response->successful() && count($response->json('data')) > 0) {
                    $customerId = $response->json('data.0.id');
                    $empresa->update(['asaas_customer_id' => $customerId]);
                    return $customerId;
                }
            }

            Log::error('Falha ao criar cliente no Asaas: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            // Se a exceção for a que nós criamos, relança ela para o controller mostrar a mensagem bonita.
            if ($e instanceof \InvalidArgumentException) {
                throw $e;
            }
            Log::error('Exceção ao criar cliente no Asaas: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria uma cobrança PIX no Asaas.
     */
    public function criarCobranca(Fatura $fatura): ?array
    {
        $clienteId = $this->criarOuRecuperarCliente($fatura->contrato->empresaParceira);

        if (!$clienteId) {
            return null;
        }

        try {
            $response = $this->http->post('/payments', [
                'customer' => $clienteId,
                'billingType' => 'PIX',
                'value' => $fatura->valor_total,
                'dueDate' => $fatura->data_vencimento->format('Y-m-d'),
                'description' => 'Fatura de Serviços - ' . $fatura->numero_fatura,
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Falha ao criar cobrança no Asaas: ' . $response->body());
            return null;

        } catch (\Exception $e) {
            Log::error('Exceção ao criar cobrança no Asaas: ' . $e->getMessage());
            return null;
        }
    }
}
