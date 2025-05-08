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
    
    @if (session('status'))
        <div class="alert alert-success">
            {{ session('status') }}
        </div>
    @endif
    
    <div>
        <a href="{{ route('questions.create') }}">
            <button class="group relative inline-flex h-12 items-center justify-center overflow-hidden rounded-md bg-neutral-950 px-6 font-medium text-neutral-200 transition hover:scale-110"><span>投稿する（同期版）</span><div class="absolute inset-0 flex h-full w-full justify-center [transform:skew(-12deg)_translateX(-100%)] group-hover:duration-1000 group-hover:[transform:skew(-12deg)_translateX(100%)]"><div class="relative h-full w-8 bg-white/20"></div></div></button>
        </a>
        <a href="{{ route('questions.createA') }}">
            <button class="relative overflow-hidden rounded-md bg-neutral-950 px-5 py-2.5 text-white duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:translate-y-1 active:scale-x-110 active:scale-y-90">投稿する（非同期版）</button>
        </a>
    </div>

    @if ($questions->isEmpty())
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
                    <a href="{{ route('questions.show', $question->id) }}">
                        評価
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
@endsection
