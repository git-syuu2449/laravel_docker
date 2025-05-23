@extends('layouts.app')

@section('title', 'エラーが発生しました(管理者)')
@section('content')
    <div class="text-center">
        <h1 class="text-4xl font-bold">500 Internal Server Error</h1>
        <p class="mt-4">問題が発生しました。しばらくしてから再度お試しください。</p>
        <a href="{{ url('/') }}" class="btn btn-primary mt-4">トップに戻る</a>
    </div>
@endsection