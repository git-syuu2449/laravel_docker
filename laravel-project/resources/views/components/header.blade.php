<header class="bg-gray-800 text-white p-4">
    <div class="container mx-auto flex justify-between items-center">
        <h1 class="text-3xl bg-orange-300 font-bold">Laravel</h1>
        <nav>
            <ul class="flex space-x-4">
                <li><a href="/" class="hover:underline">Home</a></li>
                <li><a href="{{ route('questions.index') }}" class="hover:underline">質問一覧</a></li>
            </ul>
        </nav>
    </div>
</header>