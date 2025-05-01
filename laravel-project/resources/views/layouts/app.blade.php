<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'デフォルトタイトル')</title>
    @once
        @vite(['resources/css/app.css'])
        @stack('css')

        @vite(['resources/js/app.js'])
        @stack('scripts')
    @endonce
</head>
<body class="min-h-screen flex flex-col">

    <x-header />

    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    <x-footer />
</body>
</html>
