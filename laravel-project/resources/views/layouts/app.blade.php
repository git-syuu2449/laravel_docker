<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ページごとのCSS --}}
    @stack('css')
</head>
<body class="font-sans antialiased">
    {{-- Vueをマウントする要素 --}}
    <div id="app" class="min-h-screen bg-gray-100">

        {{-- ナビゲーション（ログイン時のみ） --}}
        @auth
            @include('layouts.navigation')
        @endauth

        {{-- ヘッダー --}}
        @isset($header)
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        {{-- メインコンテンツ --}}
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if(isset($slot))
                    {{ $slot }}
                @else
                    @yield('content')
                @endif
            </div>
        </main>

        {{-- フッター --}}
        <x-footer />
    </div>

    {{-- ページごとのJSはbody末尾で読み込む --}}
    @stack('scripts')
</body>
</html>
