<x-mail::message>
# Olá!

{{ $recado }}

Aqui está um resumo da sua agenda de atividades para o período selecionado.

@foreach($agendas as $agenda)
<x-mail::panel>
**Assunto:** {{ $agenda->assunto }}<br>
**Data e Hora:** {{ $agenda->data_hora->format('d/m/Y \à\s H:i') }}<br>
**Cliente:** {{ $agenda->contrato->empresaParceira->nome_empresa ?? 'N/A' }}<br>
**Contrato:** {{ $agenda->contrato->numero_contrato ?? 'N/A' }}<br>
**Status:** {{ $agenda->status }}
</x-mail::panel>
@endforeach

<x-mail::button :url="route('agendas.index')">
Acessar Minhas Agendas
</x-mail::button>

Qualquer dúvida, por favor, entre em contato.

Atenciosamente,<br>
**{{ $remetente->nome }}**<br>
{{ config('app.name') }}
</x-mail::message>
