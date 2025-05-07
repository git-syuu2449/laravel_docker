@extends('layouts.app')
@section('title', '質問登録')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/create.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/create.js') }}"></script>
@endpush

@section(section: 'content')
    <section class="'text-gray-600 w-full flex flex-col items-center px-2">
        <h2 class="text-3xl font-bold mt-10">質問登録</h2>

        <div id="app-vue">
            <question-form post-url="{{ route('questions.store')}}"></question-form>
            <test-component></test-component>
        </div>
    </section>
@endsection