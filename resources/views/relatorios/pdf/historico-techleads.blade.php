<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Relatório - Histórico de Tech Leads</title>
    <style>
        @page { margin: 120px 50px 80px 50px; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; color: #333; font-size: 10px; }
        header { position: fixed; top: -100px; left: 0; right: 0; height: 80px; text-align: left; border-bottom: 2px solid #0d6efd; }
        header img { height: 50px; float: left; }
        header .header-text { float: right; text-align: right; }
        header h1 { margin: 0; font-size: 24px; color: #0d6efd; }
        header p { margin: 5px 0 0; font-size: 12px; }
        footer { position: fixed; bottom: -60px; left: 0; right: 0; height: 50px; text-align: center; border-top: 1px solid #ddd; font-size: 9px; }
        footer .pagenum:before { content: counter(page); }
        .content-title { font-size: 18px; font-weight: bold; margin-bottom: 5px; color: #333; }
        .content-subtitle { font-size: 12px; margin-bottom: 20px; color: #666; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        tbody tr:nth-child(even) { background-color: #f9f9f9; }
        .text-center { text-align: center; }
    </style>
</head>
<body>
    <header>
        <img src="{{ public_path('images/favicon.webp') }}" alt="Logo Progmud">
        <div class="header-text">
            <h1>Histórico de Tech Leads</h1>
            <p>Gerado em: {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </header>

    <footer>
        Relatório de Histórico | Progmud | Página <span class="pagenum"></span>
    </footer>

    <main>
        <div class="content-title">Contrato: {{ $contrato->numero_contrato }}</div>
        <div class="content-subtitle">Cliente: {{ $contrato->empresaParceira->nome_empresa }}</div>
        
        <table>
            <thead>
                <tr>
                    <th>Nome do Tech Lead</th>
                    <th class="text-center">Data de Início</th>
                    <th class="text-center">Data de Fim</th>
                </tr>
            </thead>
            <tbody>
                @forelse($historico as $entrada)
                    <tr>
                        <td>{{ $entrada->tech_lead_nome }}</td>
                        <td class="text-center">{{ \Carbon\Carbon::parse($entrada->data_inicio)->format('d/m/Y') }}</td>
                        <td class="text-center">
                            {{ $entrada->data_fim ? \Carbon\Carbon::parse($entrada->data_fim)->format('d/m/Y') : 'Atual' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" style="text-align: center; padding: 20px;">Nenhum histórico de Tech Leads encontrado para este contrato.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </main>
</body>
</html>
