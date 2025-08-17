<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - Visão Geral de Contratos</title>
    <style>
        @page { margin: 120px 50px 80px 50px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 9px; }
        header { position: fixed; top: -100px; left: 0; right: 0; height: 80px; text-align: left; border-bottom: 2px solid #0d6efd; }
        header img { height: 50px; float: left; }
        header .header-text { float: right; text-align: right; }
        header h1 { margin: 0; font-size: 24px; color: #0d6efd; }
        header p { margin: 5px 0 0; font-size: 12px; }
        footer { position: fixed; bottom: -60px; left: 0; right: 0; height: 50px; text-align: center; border-top: 1px solid #ddd; font-size: 9px; }
        footer .pagenum:before { content: counter(page); }
        .content-title { font-size: 18px; font-weight: bold; margin-bottom: 20px; color: #333; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-right { text-align: right; }
        .text-red { color: #dc3545; }
        .text-green { color: #198754; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/favicon.webp') }}" alt="Logo Progmud">
        <div class="header-text">
            <h1>Visão Geral de Contratos</h1>
            <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </header>

    <footer>
        Relatório de Contratos | Progmud | Página <span class="pagenum"></span>
    </footer>

    <main>
        <div class="content-title">Análise de Saldo de Horas</div>
        <table>
            <thead>
                <tr>
                    <th>Contrato</th>
                    <th>Cliente</th>
                    <th class="text-right">Horas Originais</th>
                    <th class="text-right">Horas Gastas</th>
                    <th class="text-right">Saldo de Horas</th>
                    <th class="text-right">Consumo (%)</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados as $resultado)
                    @php
                        $horasOriginais = $resultado['contrato']->baseline_horas_original ?? 0;
                        $limiteCritico = $horasOriginais * 0.1;
                    @endphp
                    <tr>
                        <td>{{ $resultado['contrato']->numero_contrato }}</td>
                        <td>{{ $resultado['contrato']->empresaParceira->nome_empresa }}</td>
                        <td class="text-right">{{ number_format($horasOriginais, 2, ',', '.') }}h</td>
                        <td class="text-right">{{ number_format($resultado['horas_gastas'], 2, ',', '.') }}h</td>
                        <td class="text-right @if($resultado['saldo_horas'] < $limiteCritico) text-red @else text-green @endif">
                            <strong>{{ number_format($resultado['saldo_horas'], 2, ',', '.') }}h</strong>
                        </td>
                        <td class="text-right">{{ number_format($resultado['percentual_gasto'], 1, ',', '.') }}%</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px;">Nenhum contrato selecionado ou dados encontrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
