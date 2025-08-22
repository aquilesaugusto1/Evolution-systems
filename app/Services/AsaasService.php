<?php

namespace App\Services;

use App\Models\EmpresaParceira;
use App\Models\Fatura;
use App\Models\User;
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
    
    public function criarTransferencia(User $colaborador, float $valor): ?array
    {
        $payload = null;
        $metodoPagamento = $colaborador->metodo_pagamento ?? 'pix';

        if ($metodoPagamento === 'pix' && !empty($colaborador->chave_pix) && !empty($colaborador->tipo_chave_pix)) {
            $payload = $this->buildPayloadPix($colaborador, $valor);
        } elseif ($metodoPagamento === 'ted') {
            $payload = $this->buildPayloadTed($colaborador, $valor);
        }

        if (!$payload) {
            Log::error("Dados de pagamento incompletos ou inválidos para o colaborador ID: {$colaborador->id}", ['metodo' => $metodoPagamento]);
            return null;
        }

        try {
            $response = $this->http->post('/transfers', $payload);

            if ($response->successful()) {
                return $response->json();
            }

            Log::error('Falha ao criar transferência no Asaas: ' . $response->body(), ['payload' => $payload]);
            return null;

        } catch (\Exception $e) {
            Log::error('Exceção ao criar transferência no Asaas: ' . $e->getMessage());
            return null;
        }
    }

    private function formatPixKey(string $key, string $type): string
    {
        $key = trim($key);
        switch ($type) {
            case 'CPF':
            case 'CNPJ':
                return preg_replace('/[^0-9]/', '', $key);
            case 'PHONE':
                $numericKey = preg_replace('/[^0-9]/', '', $key);
                if (strlen($numericKey) >= 10) { // Garante que tem pelo menos DDD + número
                    return '+55' . $numericKey;
                }
                return $key; // Retorna como está se não for um formato de telefone reconhecível
            default:
                return $key;
        }
    }

    private function buildPayloadPix(User $colaborador, float $valor): ?array
    {
        $formattedKey = $this->formatPixKey($colaborador->chave_pix, $colaborador->tipo_chave_pix);

        return [
            'operationType' => 'PIX',
            'value' => $valor,
            'pixAddressKey' => $formattedKey,
            'pixAddressKeyType' => $colaborador->tipo_chave_pix,
        ];
    }

    private function buildPayloadTed(User $colaborador, float $valor): ?array
    {
        $dadosBancarios = $colaborador->dados_bancarios;
        $dadosPj = $colaborador->dados_empresa_prestador;
        
        $bankCode = $this->getBankCode($dadosBancarios['banco'] ?? '');
        $cpfCnpj = preg_replace('/[^0-9]/', '', $dadosPj['cnpj'] ?? $colaborador->cpf);

        if (empty($bankCode) || empty($dadosBancarios['agencia']) || empty($dadosBancarios['conta']) || empty($cpfCnpj)) {
            return null;
        }

        $contaCompleta = $dadosBancarios['conta'];
        $conta = preg_replace('/[^0-9-]/', '', $contaCompleta);
        $digito = '';

        if (str_contains($conta, '-')) {
            $parts = explode('-', $conta);
            $conta = $parts[0];
            $digito = $parts[1];
        } else if (strlen($conta) > 1) {
            $digito = substr($conta, -1);
            $conta = substr($conta, 0, -1);
        }

        return [
            'operationType' => 'TED',
            'value' => $valor,
            'bankAccount' => [
                'bank' => ['code' => $bankCode],
                'accountName' => $dadosPj['razao_social'] ?? $colaborador->nome . ' ' . $colaborador->sobrenome,
                'ownerName' => $dadosPj['razao_social'] ?? $colaborador->nome . ' ' . $colaborador->sobrenome,
                'cpfCnpj' => $cpfCnpj,
                'agency' => $dadosBancarios['agencia'],
                'account' => $conta,
                'accountDigit' => $digito,
                'bankAccountType' => str_contains(strtolower($dadosBancarios['tipo_conta'] ?? 'corrente'), 'corrente') ? 'CONTA_CORRENTE' : 'CONTA_POUPANCA',
            ],
        ];
    }

    private function getBankCode(string $bankName): ?string
    {
        $banks = [
            'Banco do Brasil' => '001',
            'Bradesco' => '237',
            'Caixa Econômica Federal' => '104',
            'Itaú Unibanco' => '341',
            'Itaú' => '341',
            'Santander' => '033',
            'Nu Pagamentos S.A.' => '260',
            'Banco Inter' => '077',
            'C6 Bank' => '336',
        ];

        return $banks[$bankName] ?? null;
    }
}
