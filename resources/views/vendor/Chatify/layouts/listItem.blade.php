{{-- -------------------- A contact item from the list -------------------- --}}
@if($get == 'users')
<div class="messenger-list-item" data-contact="{{ $user->id }}">
    {{-- Avatar --}}
    <div class="avatar av-m" style="background-image: url('{{ $user->foto_url ?? asset('vendor/chatify/images/avatar.png') }}');">
    </div>
    {{-- User details --}}
    <div class="user-details">
        <p data-id="{{ $user->id }}" data-type="user">{{ strlen($user->nome) > 12 ? trim(substr($user->nome,0,12)).'..' : $user->nome }}</p>
        {{-- Last message --}}
        <span>
            @if($lastMessage)
                {!! $lastMessage->from_id == auth()->user()->id ? '<span class="lastMessageIndicator">Você:</span>' : '' !!}
                @if($lastMessage->attachment == null)
                    {{ strlen($lastMessage->body) > 20 ? trim(substr($lastMessage->body, 0, 20)).'..' : $lastMessage->body }}
                @else
                    <span class="fas fa-file"></span> Anexo
                @endif
            @else
                Inicie uma conversa!
            @endif
        </span>
    </div>
    {{-- Time and seen indicator --}}
    <div class="time-seen">
        <span class="time">{{ $lastMessage ? $lastMessage->created_at->diffForHumans() : '' }}</span>
        @if($lastMessage && $lastMessage->from_id == auth()->user()->id)
            <span class="seen-indicator">
                {!! $lastMessage->seen > 0 ? '<span class="fas fa-check-double seen"></span>' : '<span class="fas fa-check"></span>' !!}
            </span>
        @endif
    </div>
</div>
@endif

{{-- -------------------- A search item from the list -------------------- --}}
@if($get == 'search_item')
<div class="messenger-list-item" data-contact="{{ $user->id }}">
    {{-- Avatar --}}
    <div class="avatar av-m" style="background-image: url('{{ $user->foto_url ?? asset('vendor/chatify/images/avatar.png') }}');">
    </div>
    {{-- User details --}}
    <div class="user-details">
        <p data-id="{{ $user->id }}" data-type="user">
        {{ strlen($user->nome) > 12 ? trim(substr($user->nome,0,12)).'..' : $user->nome }}</p>
    </div>
</div>
@endif

{{-- -------------------- Shared photos item -------------------- --}}
@if($get == 'sharedPhoto')
<div class="shared-photo-item">
    <a href="{{ $image }}" data-lightbox="shared-photos">
        <div class="shared-photo" style="background-image: url('{{ $image }}')"></div>
    </a>
</div>
@endif