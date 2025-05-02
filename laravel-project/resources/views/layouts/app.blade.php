<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'デフォルトタイトル')</title>
    {{-- css,jsの読み込み --}}
    @once
        @vite(['resources/css/app.css'])
    @endonce
    @stack('css')
    @once
        @vite(['resources/js/app.js'])
    @endonce
    @stack('scripts')
</head>
<body class="min-h-screen flex flex-col">

    <x-header />

    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
