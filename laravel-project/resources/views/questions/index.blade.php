@extends('layouts.app')

@section('title', '質問一覧')

@push('css')
<link rel="stylesheet" href="{{ asset('css/questions/index.css') }}">
@endpush

@section('content')
    <h2>質問一覧</h2>

    <ul>
        @foreach ($questions as $question)
            <li>{{ $question->question_text }}</li>
        @endforeach
    </ul>
@endsection

@push('scripts')
<script src="{{ asset('js/questions/index.js') }}"></script>
@endpush