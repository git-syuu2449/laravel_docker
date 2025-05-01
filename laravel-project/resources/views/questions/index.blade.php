@extends('layouts.app')

@section('title', '質問一覧')

@push('css')
@vite(['resources/css/questions/index.css'])
@endpush

@section('content')
    <h2>質問一覧</h2>

    <ul class="question_list">
        @foreach ($questions as $question)
            <li>{{ $question->question_text }}</li>
        @endforeach
    </ul>
@endsection

@push('scripts')
@vite(['resources/js/questions/index.js'])
@endpush