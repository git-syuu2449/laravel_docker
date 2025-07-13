@extends('layouts.app')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/admin/users/index.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/admin/users/index.ts') }}"></script>
@endpush

@section('title', 'ユーザー一覧')

@section('content')

    <div class="u-section-in-wrapper">
        <h2 class="u-title-h2">ユーザー一覧</h2>

        <div id='app-vue'>

        </div>

    </div>

@endsection