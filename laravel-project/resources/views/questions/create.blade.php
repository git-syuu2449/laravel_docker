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

        <form method="POST" action="{{ route('questions.store') }}">
            @csrf
            <div id="app-vue">
                <question-form
                    :errors='@json($errors->toArray())'
                    :old='@json(old())'
                    :post-url="'{{ route('questions.store') }}'"
                ></question-form>
            </div>
            <button type="submit" class="btn btn-primary">登録</button>
        </form>
    </section>
@endsection

{{-- デバッグ用 --}}
<script>
    console.log("Old input:", @json(old()));
    console.log("Errors:", @json($errors->toArray()));
</script>