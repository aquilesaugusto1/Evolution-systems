<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Fatura {{ $fatura->numero_fatura }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; font-size: 12px; color: #333; }
        .container { width: 100%; margin: 0 auto; }
        .header, .footer { text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .header p { margin: 5px 0; }
        .content { margin-top: 30px; }
        .details-table, .items-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .details-table td { padding: 8px; }
        .items-table th, .items-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        .items-table th { background-color: #f2f2f2; }
        .total-section { margin-top: 20px; text-align: right; }
        .total-section p { font-size: 16px; font-weight: bold; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .company-info { float: left; width: 50%; }
        .invoice-info { float: right; width: 50%; text-align: right; }
        .clearfix::after { content: ""; clear: both; display: table; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header clearfix">
            <div class="company-info">
                <h1>Evolution Systems</h1>
                <p>Rua Exemplo, 123 - Centro</p>
                <p>Jaú, SP - 17201-000</p>
                <p>contato@evolutionsystems.com.br</p>
            </div>
            <div class="invoice-info">
                <h2>FATURA</h2>
                <p><strong>Nº:</strong> {{ $fatura->numero_fatura }}</p>
                <p><strong>Data de Emissão:</strong> {{ $fatura->data_emissao->format('d/m/Y') }}</p>
                <p><strong>Data de Vencimento:</strong> {{ $fatura->data_vencimento->format('d/m/Y') }}</p>
            </div>
        </div>

        <div class="content">
            <h3>Detalhes do Cliente</h3>
            <table class="details-table">
                <tr>
                    <td><strong>Cliente:</strong></td>
                    <td>{{ $fatura->contrato->empresaParceira->nome_fantasia }}</td>
                </tr>
                <tr>
                    <td><strong>Razão Social:</strong></td>
                    <td>{{ $fatura->contrato->empresaParceira->razao_social }}</td>
                </tr>
                <tr>
                    <td><strong>CNPJ:</strong></td>
                    <td>{{ $fatura->contrato->empresaParceira->cnpj }}</td>
                </tr>
                 <tr>
                    <td><strong>Contrato:</strong></td>
                    <td>{{ $fatura->contrato->numero_contrato }}</td>
                </tr>
            </table>

            <h3>Itens da Fatura</h3>
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
                        <td class="text-right">{{ $apontamento->horas_gastas }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="total-section">
                <p>Valor Hora: R$ {{ number_format($fatura->contrato->valor_hora, 2, ',', '.') }}</p>
                <p>Total de Horas: {{ \App\Traits\ConvertsTime::decimalToTime($fatura->apontamentos->sum('horas_gastas_decimal')) }}</p>
                <p>Valor Total: R$ {{ number_format($fatura->valor_total, 2, ',', '.') }}</p>
            </div>
        </div>

        <div class="footer">
            <p>Obrigado por sua preferência!</p>
        </div>
    </div>
</body>
</html>
