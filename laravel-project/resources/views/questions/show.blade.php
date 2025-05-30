@extends('layouts.app')
@section('title', '質問詳細')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/show.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/show.js') }}"></script>
@endpush

@section('content')
<div class="bg-white p-6 sm:p-10 rounded-lg shadow-md max-w-5xl mx-auto mt-8">
  <h2 class="text-3xl font-bold text-gray-800 mb-6">質問詳細</h2>

  @if (session('message'))
    <div class="mb-4 text-green-600 bg-green-100 p-3 rounded">
      {{ session('message') }}
    </div>
  @endif

  <div class="mb-6">
    <div class="text-sm text-gray-500 mb-1">{{ $question->pub_date }}</div>
    <h3 class="text-2xl font-semibold text-gray-800 mb-3">{{ $question->title }}</h3>
    <p class="text-gray-700 whitespace-pre-line mb-4">{!! nl2br(e($question->body)) !!}</p>

    @if (!$question->questionImages->isEmpty())
      <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
        @foreach ($question->questionImages as $image)
          <img
            src="{{ Storage::url($image->image) }}"
            alt="質問画像"
            class="rounded-md border shadow-sm"
          >
        @endforeach
      </div>
    @endif
  </div>

  <div id="app-vue" class="mb-10">
    <choice-modal-wrapper
      :post-url="'{{ route('choices.store', $question->id) }}'"
      :question-id="{{ $question->id }}"
    ></choice-modal-wrapper>
  </div>

  <div>
    <h4 class="text-xl font-semibold text-gray-800 mb-4">評価一覧</h4>
    @if ($question->choices->isEmpty())
      <p class="text-gray-500">評価はありません。</p>
    @else
      <ul class="divide-y divide-gray-200 bg-gray-50 rounded-md shadow-sm">
        @foreach ($question->choices as $choice)
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
</div>
@endsection
