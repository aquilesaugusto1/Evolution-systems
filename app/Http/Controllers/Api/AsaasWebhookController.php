<?php

namespace App\Http\Controllers\Api;

use App\Enums\FaturaStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    /**
     * Handle incoming Asaas webhook requests.
     */
    public function handle(Request $request)
    {
        // 1. Validar a assinatura do Webhook
        $secret = config('asaas.webhook_secret');
        if ($secret && $request->header('asaas-webhook-token') !== $secret) {
            Log::warning('Asaas Webhook: Assinatura inválida.');
            return response()->json(['error' => 'Assinatura inválida'], 401);
        }

        $event = $request->input('event');
        $payment = $request->input('payment');

        Log::info('Asaas Webhook Recebido:', $request->all());

        // 2. Processar apenas eventos de pagamento recebido
        if ($event === 'PAYMENT_RECEIVED') {
            $paymentId = $payment['id'];

            // 3. Encontrar a fatura local
            $fatura = Fatura::where('asaas_payment_id', $paymentId)->first();

            if ($fatura) {
                // 4. Atualizar o status da fatura
                if ($fatura->status !== FaturaStatusEnum::PAGA) {
                    $fatura->status = FaturaStatusEnum::PAGA;
                    $fatura->save();
                    Log::info("Fatura {$fatura->numero_fatura} atualizada para PAGA via webhook.");
                } else {
                    Log::info("Fatura {$fatura->numero_fatura} já estava PAGA. Webhook ignorado.");
                }
            } else {
                Log::warning("Webhook Asaas: Fatura com payment_id {$paymentId} não encontrada.");
            }
        }

        // 5. Retornar uma resposta de sucesso para o Asaas
        return response()->json(['status' => 'ok'], 200);
    }
}
