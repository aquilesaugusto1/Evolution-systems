<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        {{-- ATUALIZAÇÃO: O título agora usa a variável de configuração do app --}}
        <title>{{ config('app.name', 'Progmud') }}</title>
        
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        <div class="min-h-screen flex bg-slate-50">
            
            <!-- Secção da Imagem (Esquerda) -->
            <div class="hidden lg:flex w-1/2 items-center justify-center p-12 bg-slate-900 bg-cover bg-center" style="background-image: url('https://images.unsplash.com/photo-1581092921440-4c3043594451?q=80&w=2835&auto=format&fit=crop');">
                <div class="text-white text-center">
                    <h1 class="text-4xl font-bold tracking-tight">Bem-vindo de volta ao Portal Progmud</h1>
                    <p class="mt-4 text-lg text-slate-300">A sua plataforma central para a gestão de consultoria.</p>
                </div>
            </div>

            <!-- Secção do Formulário (Direita) -->
            <div class="w-full lg:w-1/2 flex items-center justify-center p-6 sm:p-12">
                <div class="w-full max-w-md">
                    <div class="mb-8 text-center lg:text-left">
                        {{-- ATUALIZAÇÃO VISUAL: Aplicado o mesmo estilo de 'emblema' da sidebar --}}
                        <a href="/" class="flex items-center justify-center lg:justify-start space-x-4">
                            <div class="flex items-center justify-center h-12 w-12 rounded-full bg-slate-200">
                                <img class="h-7 w-7" src="{{ asset('images/favicon.webp') }}" alt="Logo da Progmud">
                            </div>
                            <span class="text-3xl font-bold text-gray-800">Portal Progmud</span>
                        </a>
                    </div>
    
                    <div class="w-full px-8 py-10 bg-white shadow-xl rounded-lg">
                        {{ $slot }}
                    </div>
                </div>
            </div>

        </div>
    </body>
</html>
