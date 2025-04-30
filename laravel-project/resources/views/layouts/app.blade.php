<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'デフォルトタイトル')</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    @stack('css')
</head>
<body class="min-h-screen flex flex-col">

    <x-header />

    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    <x-footer />

    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
