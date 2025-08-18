<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fatura {{ $fatura->numero_fatura }}</title>
    <style>
        body { font-family: 'Helvetica', sans-serif; color: #333; font-size: 12px; line-height: 1.6; }
        .container { width: 100%; margin: 0 auto; padding: 20px; }
        .header { text-align: center; margin-bottom: 40px; }
        .header h1 { margin: 0; font-size: 24px; color: #2d3748; }
        .header p { margin: 5px 0; color: #718096; }
        .details { margin-bottom: 40px; }
        .details table { width: 100%; border-collapse: collapse; }
        .details th, .details td { padding: 8px 0; text-align: left; }
        .details .right { text-align: right; }
        .items table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .items th, .items td { border: 1px solid #e2e8f0; padding: 8px; text-align: left; }
        .items th { background-color: #f7fafc; font-weight: bold; }
        .items .total-row td { border-top: 2px solid #2d3748; font-weight: bold; font-size: 14px; }
        .items .hours { text-align: right; }
        .footer { margin-top: 50px; text-align: center; color: #718096; font-size: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>FATURA</h1>
            <p>Evolution Systems</p>
        </div>

        <div class="details">
            <table>
                <tr>
                    <td>
                        <strong>CLIENTE:</strong><br>
                        {{ $fatura->contrato->empresaParceira->nome_empresa }}<br>
                        CNPJ: {{ $fatura->contrato->empresaParceira->cnpj }}
                    </td>
                    <td class="right">
                        <strong>FATURA Nº:</strong> {{ $fatura->numero_fatura }}<br>
                        <strong>Data de Emissão:</strong> {{ $fatura->data_emissao->format('d/m/Y') }}<br>
                        <strong>Data de Vencimento:</strong> {{ $fatura->data_vencimento->format('d/m/Y') }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="items">
            <h2>Detalhes dos Serviços Prestados</h2>
            <table>
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Consultor</th>
                        <th>Descrição do Serviço</th>
                        <th class="hours">Horas</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($fatura->apontamentos as $apontamento)
                    <tr>
                        <td>{{ $apontamento->data_apontamento->format('d/m/Y') }}</td>
                        <td>{{ $apontamento->consultor->nome }}</td>
                        <td>{{ $apontamento->descricao }}</td>
                        <td class="hours">{{ $apontamento->horas_gastas }}</td>
                    </tr>
                    @endforeach
                    <tr class="total-row">
                        <td colspan="3" class="right"><strong>VALOR TOTAL</strong></td>
                        <td class="hours"><strong>R$ {{ number_format($fatura->valor_total, 2, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="footer">
            <p>Esta é uma fatura gerada automaticamente pelo sistema Agen.</p>
            <p>Para dúvidas ou informações, entre em contato conosco.</p>
        </div>
    </div>
</body>
</html>
