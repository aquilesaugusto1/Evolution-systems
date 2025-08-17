<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório de Alocação de Consultores</title>
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
        .filters-summary { margin-bottom: 25px; border-left: 4px solid #0dcaf0; padding: 10px 15px; background-color: #f8f9fa; }
        .filters-summary h5 { margin: 0 0 10px; font-size: 12px; font-weight: bold; color: #0dcaf0; }
        .filters-summary p { margin: 0; padding: 2px 0; }
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
            <h1>Alocação de Consultores</h1>
            <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </header>

    <footer>
        Relatório de Alocação | Progmud | Página <span class="pagenum"></span>
    </footer>

    <main>
        <div class="filters-summary">
            <h5>FILTROS APLICADOS</h5>
            <p><strong>Período:</strong> {{ \Carbon\Carbon::parse($filtros['data_inicio'])->format('d/m/Y') }} a {{ \Carbon\Carbon::parse($filtros['data_fim'])->format('d/m/Y') }}</p>
            @if(!empty($resultados))
                <p><strong>Dias Úteis no Período:</strong> {{ $resultados[0]['dias_uteis'] ?? 0 }}</p>
            @endif
        </div>

        <div class="content-title">Detalhes da Alocação</div>
        <table>
            <thead>
                <tr>
                    <th>Consultor</th>
                    <th class="text-right">Horas Apontadas</th>
                    <th class="text-right">Nº de Agendas</th>
                    <th class="text-right">Horas Úteis no Período</th>
                    <th class="text-right">Horas Úteis Restantes</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados as $resultado)
                    <tr>
                        <td>{{ $resultado['consultor']->nome }}</td>
                        <td class="text-right">{{ number_format($resultado['horas_apontadas'], 2, ',', '.') }}h</td>
                        <td class="text-right">{{ $resultado['numero_agendas'] }}</td>
                        <td class="text-right">{{ number_format($resultado['horas_uteis_periodo'], 2, ',', '.') }}h</td>
                        <td class="text-right @if($resultado['horas_uteis_restantes'] < 0) text-red @else text-green @endif">
                            <strong>{{ number_format($resultado['horas_uteis_restantes'], 2, ',', '.') }}h</strong>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align: center; padding: 20px;">Nenhum consultor selecionado ou dados encontrados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
