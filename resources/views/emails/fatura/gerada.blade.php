<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sua Fatura Chegou - Evolution Systems</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif, 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol';
            background-color: #f8f9fa;
            color: #343a40;
            margin: 0;
            padding: 20px;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background-color: #343a40;
            padding: 40px 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 200px;
            height: auto;
        }
        .email-body {
            padding: 30px 40px;
            line-height: 1.6;
            font-size: 16px;
        }
        .email-body h1 {
            font-size: 24px;
            color: #212529;
            margin-top: 0;
        }
        .invoice-details {
            background-color: #f8f9fa;
            border-radius: 6px;
            padding: 20px;
            margin: 25px 0;
            border: 1px solid #e9ecef;
        }
        .detail-item {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #e9ecef;
        }
        .detail-item:last-child {
            border-bottom: none;
        }
        .detail-item strong {
            font-weight: 600;
            color: #212529;
        }
        .cta-button-container {
            text-align: center;
            margin: 30px 0;
        }
        .cta-button {
            background-color: #0d6efd;
            color: #ffffff;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            font-size: 16px;
            display: inline-block;
        }
        .email-footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #6c757d;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <!-- Coloque a URL pública da sua logo aqui -->
            <link rel="icon" type="image/webp" href="{{ asset('images/logoevo.png') }}">
        </div>
        <div class="email-body">
            <h1>Sua fatura está pronta!</h1>
            <p>Olá, {{ $nomeCliente }},</p>
            <p>A fatura referente aos serviços prestados pela Evolution Systems já está disponível. O PDF com os detalhes e o QR Code para pagamento via PIX está anexado a este e-mail.</p>
            
            <div class="invoice-details">
                <div class="detail-item">
                    <span>Número da Fatura</span>
                    <strong>{{ $numeroFatura }}</strong>
                </div>
                <div class="detail-item">
                    <span>Data de Vencimento</span>
                    <strong>{{ $vencimentoFatura }}</strong>
                </div>
                <div class="detail-item">
                    <span>Valor Total</span>
                    <strong style="font-size: 18px;">R$ {{ $valorFatura }}</strong>
                </div>
            </div>

            <p>Para sua conveniência, você também pode visualizar e pagar sua fatura online através do nosso portal de pagamentos seguro.</p>
            
            <div class="cta-button-container">
                <a href="{{ $fatura->asaas_payment_url }}" class="cta-button">Pagar Fatura Online</a>
            </div>

            <p>Se tiver qualquer dúvida, basta responder a este e-mail.</p>
            <p>Agradecemos a sua parceria!<br><strong>Equipe Evolution Systems</strong></p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Evolution Systems. Todos os direitos reservados.</p>
            <p>Este é um e-mail automático. Por favor, não o responda diretamente.</p>
        </div>
    </div>
</body>
</html>
