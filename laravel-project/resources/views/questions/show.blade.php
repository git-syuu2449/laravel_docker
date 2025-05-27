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

        @if (session('message'))
            <div class="alert alert-success">
                {{ session('message') }}
            </div>
        @endif

        <span>
            {{ $question->pub_date }}
        </span>
        <h3>
            {{ $question->question_text }}
        </h3>

        <div>
            @if (!$question->questionImages->isEmpty())
                @foreach ($question->questionImages as $image)
                    <img src="{{ Storage::url($image->image) }}">
                @endforeach
            @endif
        </div>


        <div id="app-vue">
            <choice-modal-wrapper
                {{-- :errors='@json($errors->toArray())'
                :old='@json(old())' --}}
                :post-url="'{{ route('choices.store', $question->id) }}'"
                :question-id="{{ $question->id }}"
            ></choice-modal-wrapper>
        </div>
        {{-- <button class="relative overflow-hidden rounded-md bg-neutral-950 px-5 py-2.5 text-white duration-300 [transition-timing-function:cubic-bezier(0.175,0.885,0.32,1.275)] active:translate-y-1 active:scale-x-110 active:scale-y-90">評価する</button> --}}


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
