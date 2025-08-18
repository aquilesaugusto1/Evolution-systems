<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @php
        $stats = $widgetData ?? [];
        $colors = ['bg-indigo-500', 'bg-purple-500', 'bg-teal-500', 'bg-sky-500'];
        $i = 0;
    @endphp
    @foreach($stats as $label => $value)
    <div class="p-6 rounded-lg shadow-lg text-white {{ $colors[$i++ % count($colors)] }}">
        <p class="text-sm font-medium opacity-80">{{ $label }}</p>
        <p class="mt-1 text-4xl font-bold">{{ $value }}</p>
    </div>
    @endforeach
</div>
