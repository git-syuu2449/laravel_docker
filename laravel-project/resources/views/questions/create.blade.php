@extends('layouts.app')
@section('title', '質問登録')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/create.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/create.js') }}"></script>
@endpush

@section('content')
    <h2>質問登録</h2>

    <div id="app-vue">
        <question-form></question-form>
        <test-component></test-component>
    </div>
@endsection