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

    public function criarOuRecuperarCliente(EmpresaParceira $empresa): ?string
    {
        if ($empresa->asaas_customer_id) {
            return $empresa->asaas_customer_id;
        }

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
            if ($e instanceof \InvalidArgumentException) {
                throw $e;
            }
            Log::error('Exceção ao criar cliente no Asaas: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Cria uma cobrança no Asaas com o tipo especificado.
     */
    public function criarCobranca(Fatura $fatura, string $billingType): ?array
    {
        $clienteId = $this->criarOuRecuperarCliente($fatura->contrato->empresaParceira);

        if (!$clienteId) {
            return null;
        }

        try {
            $payload = [
                'customer' => $clienteId,
                'billingType' => $billingType,
                'value' => $fatura->valor_total,
                'dueDate' => $fatura->data_vencimento->format('Y-m-d'),
                'description' => 'Fatura de Serviços - ' . $fatura->numero_fatura,
            ];

            $response = $this->http->post('/payments', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Falha ao criar cobrança no Asaas: ' . $response->body(), ['payload' => $payload]);
            return null;

        } catch (\Exception $e) {
            Log::error('Exceção ao criar cobrança no Asaas: ' . $e->getMessage());
            return null;
        }
    }

    public function cancelarCobranca(string $paymentId): bool
    {
        try {
            $response = $this->http->delete("/payments/{$paymentId}");

            Log::channel('stack')->info('ASAAS LOG - Tentativa de cancelamento:', [
                'payment_id' => $paymentId,
                'status_code' => $response->status(),
                'response_body' => $response->json() ?? $response->body(),
            ]);

            if ($response->successful()) {
                return true;
            }
            
            return false;

        } catch (\Exception $e) {
            Log::channel('stack')->error("Exceção GERAL ao cancelar cobrança {$paymentId} no Asaas: " . $e->getMessage());
            return false;
        }
    }
}
