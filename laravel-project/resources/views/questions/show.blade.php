@extends('layouts.app')
@section('title', '質問詳細')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/show.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/show.js') }}"></script>
@endpush

@section(section: 'content')
    <section class="'text-gray-600 w-full flex flex-col items-center px-2">
        <h2 class="text-3xl font-bold mt-10">質問詳細</h2>

        <h3>
            {{ $question->question_text }}
        </h3>
        <span>
            {{ $question->pub_date }}
        </span>

        @if ($question->choices->isEmpty())
            <span>
                評価はありません。
            </span>
        @else
            <ul class="question_list">
                @foreach ($question->choices as $choice)
                    <li>
                        <span>
                            {{ $choice->updated_at }}
                        </span>
                        <p>
                            {{ $choice->choice_text }}
                        </p>
                        <span>{{ $choice->votes }}</span>
                    </li>
                @endforeach
            </ul>
        @endif


    </section>
@endsection
