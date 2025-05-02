@extends('layouts.app')

@section('title', '質問一覧')
    @push('css')
        @once
            @vite(['resources/css/questions/index.css'])
        @endonce
    @endpush
    @push('scripts')
        @once
            @vite(['resources/js/questions/index.js'])
        @endonce
    @endpush

@section('content')
    <h2>質問一覧</h2>
        <div>
        <a href="{{ route('questions.create') }}">
            <button>
                投稿する
            </button>
        </a>
    </div>

    @if (!$questions->isEmpty())
        <span>
            投稿はありません。
        </span>
    @else
        <ul class="question_list">
            @foreach ($questions as $question)
                <li>
                    <span>
                        {{ $question->pub_date }}
                    </span>
                    <p>
                        {{ $question->question_text }}
                    </p>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
