<x-mail::message>
# Olá, {{ $agenda->consultor->nome ?? '' }}!

Uma atividade na sua agenda foi **{{ $acao }}**.

Aqui estão os detalhes:

- **Cliente:** {{ $agenda->contrato->empresaParceira->nome_empresa ?? 'N/A' }}
- **Contrato:** {{ $agenda->contrato->numero_contrato ?? 'N/A' }}
- **Assunto:** {{ $agenda->assunto }}
- **Data e Hora:** {{ $agenda->data_hora->format('d/m/Y \à\s H:i') }}
- **Status:** {{ $agenda->status }}

@if($agenda->descricao)
**Descrição:**<br>
{{ $agenda->descricao }}
@endif

<x-mail::button :url="route('agendas.show', $agenda)">
Ver Detalhes da Agenda
</x-mail::button>

Qualquer dúvida, por favor, entre em contato com o seu Tech Lead.

Atenciosamente,<br>
{{ config('app.name') }}
</x-mail::message>
