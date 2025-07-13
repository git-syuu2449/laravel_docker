@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/admin/dashboard.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/admin/dashboard.ts') }}"></script>
@endpush

@section('title', 'ダッシュボード')

@section('content')

    <div class="u-section-in-wrapper">
        <h2 class="u-title-h2">ダッシュボード</h2>

        <div id='app-vue'>

        </div>

    </div>

@endsection