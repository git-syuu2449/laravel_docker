@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/index.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/index.js') }}"></script>
@endpush

@section('title', '質問一覧')

@section('content')

<div class="bg-white p-6 sm:p-10 rounded-lg shadow-md max-w-7xl mx-auto mt-8">
  <h2 class="text-2xl font-semibold text-gray-800 mb-6">質問一覧</h2>

  @if (session('status'))
    <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">{{ session('status') }}</div>
  @endif

  <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4">
    <a href="{{ route('questions.create') }}">
      <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded transition">
        投稿する（同期版）
      </button>
    </a>
    <a href="{{ route('questions.createA') }}">
      <button class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded transition">
        投稿する（非同期版）
      </button>
    </a>
  </div>

  <div id="app-vue">
    <question-area
      :get-url="'{{ route('api.questions.index') }}'"
      :initial-questions='@json($questions)'
    ></question-area>
  </div>
</div>

@endsection

