<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fatura {{ $fatura->numero_fatura }}</title>
    <style>
        @page { margin: 0px; }
        body { font-family: 'Helvetica', DejaVu Sans, sans-serif; font-size: 12px; color: #333; margin: 0px; }
        .container { padding: 40px; }
        .header { margin-bottom: 30px; }
        .header h1 { margin: 0; font-size: 24px; color: #2d3748; }
        .header p { margin: 2px 0; font-size: 11px; color: #718096; }
        .details-section { margin-bottom: 30px; }
        .details-section h3 { font-size: 14px; margin-bottom: 10px; border-bottom: 1px solid #e2e8f0; padding-bottom: 5px; color: #4a5568; }
        .items-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .items-table th, .items-table td { padding: 10px; text-align: left; border-bottom: 1px solid #e2e8f0; }
        .items-table th { background-color: #f7fafc; font-weight: bold; color: #4a5568; text-transform: uppercase; font-size: 10px; }
        .items-table .text-right { text-align: right; }
        .items-table .text-center { text-align: center; }
        .summary-section { margin-top: 30px; page-break-inside: avoid; }
        .payment-section { margin-top: 30px; padding-top: 20px; border-top: 1px solid #e2e8f0; page-break-inside: avoid; }
        .payment-method { margin-bottom: 20px; }
        .payment-method h4 { margin-top: 0; margin-bottom: 10px; font-size: 13px; color: #2d3748; }
        .footer { position: fixed; bottom: 0; left: 0; right: 0; text-align: center; font-size: 10px; color: #a0aec0; padding: 20px; border-top: 1px solid #e2e8f0; }
        
        /* Helpers */
        .w-half { width: 50%; }
        .float-left { float: left; }
        .float-right { float: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
        .text-bold { font-weight: bold; }
        .text-gray { color: #718096; }
        .text-dark { color: #2d3748; }
        .font-mono { font-family: 'Courier New', Courier, monospace; }
        .barcode { font-size: 11px; word-wrap: break-word; background-color: #f7fafc; padding: 10px; border-radius: 4px; border: 1px solid #e2e8f0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            <div class="float-left w-half">
                <h1>Evolution Systems</h1>
                <p>Rua Edgar Ferraz, 85 - Centro</p>
                <p>Jaú, SP - 17201-440</p>
                <p>contato@evolutionsystems.com.br</p>
            </div>
            <div class="float-right w-half" style="text-align: right;">
                <h2 style="margin: 0; font-size: 28px; color: #4a5568;">FATURA</h2>
                <p class="text-dark"><span class="text-gray">Nº:</span> {{ $fatura->numero_fatura }}</p>
                <p class="text-dark"><span class="text-gray">Emissão:</span> {{ $fatura->data_emissao->format('d/m/Y') }}</p>
                <p class="text-dark text-bold"><span class="text-gray">Vencimento:</span> {{ $fatura->data_vencimento->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="details-section">
            <h3>Cobrança para</h3>
            <p><strong class="text-dark">{{ $fatura->contrato->empresaParceira->nome_empresa }}</strong></p>
            @if($fatura->contrato->empresaParceira->razao_social)
                <p class="text-gray">{{ $fatura->contrato->empresaParceira->razao_social }}</p>
            @endif
            <p class="text-gray">CNPJ: {{ $fatura->contrato->empresaParceira->cnpj }}</p>
            <p class="text-gray">Contrato: {{ $fatura->contrato->numero_contrato }}</p>
        </div>

        <div class="details-section">
            <h3>Resumo dos Serviços Prestados</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Consultor</th>
                        <th>Descrição do Serviço</th>
                        <th class="text-right">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fatura->apontamentos as $apontamento)
                    <tr>
                        <td class="text-center">{{ $apontamento->data_apontamento->format('d/m/Y') }}</td>
                        <td>{{ $apontamento->consultor->nome }} {{ $apontamento->consultor->sobrenome }}</td>
                        <td>{{ $apontamento->descricao }}</td>
                        <td class="text-right font-mono">{{ $apontamento->horas_gastas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="summary-section clearfix">
            <div class="float-right" style="width: 250px; text-align: right;">
                <table style="width: 100%;">
                    <tr>
                        <td class="text-gray">Total de Horas:</td>
                        <td class="font-mono">{{ \App\Traits\ConvertsTime::decimalToTime($fatura->apontamentos->sum(fn($a) => abs($a->horas_gastas_decimal))) }}</td>
                    </tr>
                    <tr>
                        <td class="text-gray">Valor / Hora:</td>
                        <td class="font-mono">R$ {{ number_format($fatura->contrato->valor_hora, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <td class="text-dark text-bold" style="font-size: 16px; padding-top: 10px;">Valor Total:</td>
                        <td class="text-dark text-bold font-mono" style="font-size: 16px; padding-top: 10px;">R$ {{ number_format($fatura->valor_total, 2, ',', '.') }}</td>
                    </tr>
                </table>
            </div>
        </div>

        @if($fatura->asaas_payment_id)
        <div class="payment-section">
            <h3>Informações para Pagamento</h3>

            @if($fatura->asaas_pix_qrcode)
            <div class="payment-method clearfix">
                <h4>Pague com PIX</h4>
                <div class="float-left" style="width: 35%;">
                    <img src="data:image/png;base64,{{ $fatura->asaas_pix_qrcode }}" alt="QR Code PIX" style="width: 140px; height: 140px;">
                </div>
                <div class="float-right" style="width: 65%;">
                    <p style="margin-top:0;">Use o QR Code ou o código abaixo para pagar via PIX:</p>
                    <p class="font-mono barcode">
                        {{ $fatura->asaas_pix_payload }}
                    </p>
                </div>
            </div>
            @endif

            @if($fatura->asaas_boleto_barcode)
            <div class="payment-method">
                <h4>Pague com Boleto</h4>
                <p>Linha Digitável:</p>
                <p class="font-mono barcode">{{ $fatura->asaas_boleto_barcode }}</p>
            </div>
            @endif
            
            @if($fatura->billing_type === 'UNDEFINED' && !$fatura->asaas_pix_qrcode && !$fatura->asaas_boleto_barcode)
            <div class="payment-method">
                <h4>Múltiplas Formas de Pagamento</h4>
                <p>Acesse o link de pagamento seguro enviado ao seu e-mail para escolher entre PIX, Boleto ou Cartão de Crédito.</p>
            </div>
            @endif
        </div>
        @endif

        <div class="footer">
            <p>Termos de Pagamento: Vencimento em {{ $fatura->data_vencimento->format('d/m/Y') }}. Agradecemos a sua parceria!</p>
        </div>
    </div>
</body>
</html>
