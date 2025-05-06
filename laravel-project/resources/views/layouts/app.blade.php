<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'デフォルトタイトル')</title>

    {{-- 共通のCSS --}}
    @vite(['resources/css/app.css'])

    {{-- ページごとのCSS --}}
    @stack('css')
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <x-header />

    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    <x-footer />

    {{-- 共通のJS --}}
    @vite(['resources/js/app.js'])

    {{-- ページごとのJSはbody末尾で読み込む --}}
    @stack('scripts')
</body>
</html>