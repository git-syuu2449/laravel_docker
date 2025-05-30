@extends('layouts.app')
@section('title', '質問登録')

@push('css')
    <link rel="stylesheet" href="{{ Vite::asset('resources/css/questions/create.css') }}" />
@endpush

@push('scripts')
    <script type="module" src="{{ Vite::asset('resources/js/questions/create.js') }}"></script>
@endpush

@section(section: 'content')
    <div class="bg-white p-6 sm:p-10 rounded-lg shadow-md">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">投稿フォーム</h2>

        <form method="POST" action="{{ route('questions.store') }} " enctype="multipart/form-data" class="space-y-6">
            @csrf
            <div id="app-vue">
                <question-form
                    v-bind:errors='@json($errors->toArray())'
                    v-bind:old='@json(old())'
                ></question-form>
            </div>
            <div class="text-right">
                <button
                    type="submit"
                    class="u-form-primary-btn"
                >
                    登録
                </button>
            </div>
        </form>
        
    </div>
@endsection

{{-- デバッグ用 --}}
<script>
    console.log("Old input:", @json(old()));
    console.log("Errors:", @json($errors->toArray()));
</script>