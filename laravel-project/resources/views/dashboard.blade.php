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

        <div id='app-vue'>

            {{-- ユーザーがした質問一覧、質問に紐づく評価一覧 --}}
            <question-list
                v-bind:questions='@json($questions)'
                v-bind:get-url="'{{ route('api.dashboard.questions.index') }}'"
            ></question-list>

            <hr class="border-b-8">
            {{-- ユーザーがした評価 --}}
            <choice-list
                v-bind:choices='@json($choices)'
                v-bind:get-url="'{{ route('api.dashboard.choices.index') }}'"
            ></choice-list>

        </div>

    </div>

@endsection