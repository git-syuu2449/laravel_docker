@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/rankings/index.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/rankings/index.js') }}"></script>
@endpush

@section('title', 'ランキング')

@section('content')
{{  $default_type }}
  <div id="app-vue">
    <ranking_top_area
      v-bind:get-url="'{{ route('api.rankings.index') }}'"
      v-bind:types = '@json($types)'
      v-bind:default-type="'{{ $default_type }}'"
      v-bind:show-base-url="'{{ rtrim(route('questions.show', ['id' => 1]),1) }}'"
    ></ranking_top_area>
  </div>
</div>

@endsection

