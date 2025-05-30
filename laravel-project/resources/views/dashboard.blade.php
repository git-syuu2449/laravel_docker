@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/dashboard.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/dashboard.js') }}"></script>
@endpush

@section('title', 'ダッシュボード')

@section('content')

    <div class="u-section-in-wrapper">
        <h2 class="u-title-h2">ダッシュボード</h2>

        <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの投稿</h4>
        @if ($questions->isEmpty())
            投稿はありません。
        @else
            {{-- スクロール表示 --}}
            <ul class="overflow-scroll divide-y-4 divide-gray-200 bg-white shadow-md rounded-md">
                @foreach ($questions as $question)
                    <li class="p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-center mb-2">
                            <h3 class="text-lg font-semibold text-gray-800">{{ $question->title }}</h3>
                            <span class="text-sm text-gray-500 px-2">{{ $question->pub_date }}</span>
                        </div>
                        <p class="text-gray-700 whitespace-pre-line mb-2">
                            {!! nl2br(e($question->body)) !!}
                        </p>

                        {{-- todo:分割表示する --}}
                        
                        @if ($question->choices->isEmpty())
                            評価はありません。
                        @else
                            {{-- 確認用 --}}
                            <h5 class="text-xl font-semibold text-gray-800 mb-4">質問に対する評価</h5>
                            @foreach ($question->choices as $choice)
                                {{ $choice->updated_at }}
                                {{ $choice->choice_text }}
                                {{ $choice->votes }}
                            @endforeach
                        @endif
                    </li>
                @endforeach
            </ul>
        @endif

        <hr class="border-b-8">

        <h4 class="text-xl font-semibold text-gray-800 mb-4">あなたの評価</h4>
        @if ($choices->isEmpty())
            <p class="text-gray-500">評価はありません。</p>
        @else
            {{-- スクロール表示 --}}
            <ul class="divide-y divide-gray-200 bg-gray-50 rounded-md shadow-sm">
                @foreach ($choices as $choice)
                <li class="p-4">
                    <div class="flex justify-between items-center mb-1 text-sm text-gray-500">
                    <span>更新日時: {{ $choice->updated_at }}</span>
                    <span class="text-blue-600 font-medium">得点: {{ $choice->votes }}</span>
                    </div>
                    <p class="text-gray-800">{!! nl2br(e($choice->choice_text)) !!}</p>
                </li>
                @endforeach
            </ul>
        @endif

    </div>

@endsection