@extends('layouts.app')
@section('title', '質問登録')

@push('css')
    @once
        @vite(['resources/css/questions/create.css'])
    @endonce
@endpush

@push('scripts')
    @once
        @vite(['resources/js/questions/create.js'])
    @endonce
@endpush

@section('content')
    <h2>質問登録</h2>
@endsection