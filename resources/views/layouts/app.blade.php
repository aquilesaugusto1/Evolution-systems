<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-g">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        @stack('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                <!-- Session Messages -->
                @if (session('success') || session('error') || $errors->any())
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 pt-6">
                    @if ($message = session('success'))
                        <div class="p-4 mb-4 text-sm text-green-700 bg-green-100 rounded-lg border border-green-200" role="alert">
                            <span class="font-medium">Sucesso!</span> {{ $message }}
                        </div>
                    @endif
                    @if ($message = session('error'))
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                            <span class="font-medium">Erro!</span> {{ $message }}
                        </div>
                    @endif
                     @if ($errors->any())
                        <div class="p-4 mb-4 text-sm text-red-700 bg-red-100 rounded-lg border border-red-200" role="alert">
                            <span class="font-medium">Erro de Validação!</span> Por favor, verifique os erros no formulário.
                        </div>
                    @endif
                </div>
                @endif

                {{ $slot }}
            </main>
        </div>
        @stack('scripts')
    </body>
</html>
