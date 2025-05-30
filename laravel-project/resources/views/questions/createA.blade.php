@extends('layouts.app')
@section('title', '質問登録')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/create.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/createA.js') }}"></script>
@endpush

@section(section: 'content')
    <div class="bg-white p-6 sm:p-10 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">質問登録</h2>

        <div id="app-vue">
            <question-form
                {{-- vueの場合はerrors,oldの受け渡しは不要 --}}
                {{-- :errors='@json($errors->toArray())'
                :old='@json(old())' --}}
                :post-url="'{{ route('questions.store') }}'"
            ></question-form>
        </div>
@endsection