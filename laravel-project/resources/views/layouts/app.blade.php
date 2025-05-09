<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @csrf
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'デフォルトタイトル')</title>

    {{-- 共通のCSS.JS --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- ページごとのCSS --}}
    @stack('css')
</head>
<body class="min-h-screen flex flex-col bg-gray-50">

    <x-header />

    <main class="flex-1 container mx-auto p-4">
        @yield('content')
    </main>

    <x-footer />

    {{-- ページごとのJSはbody末尾で読み込む --}}
    @stack('scripts')
</body>
</html>