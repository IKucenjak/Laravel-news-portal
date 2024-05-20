<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Articles') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-white">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Here are all the articles!") }}
                </div>
            </div>
                @if(count($news) > 0)
                <ul>
                    @foreach($news as $article)
                        @if($article['title'] !== '[Removed]')
                            @php
                                $commentCount = \App\Models\Comment::where('url', $article['url'])->count();
                            @endphp
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                <li>
                                    @if(isset($article['urlToImage']))
                                    <img src="{{ $article['urlToImage'] }}" alt="Article Image" class="max-w-full mb-4 rounded-md">
                                    @endif
                                    <strong>{{ $article['title'] }}</strong><br>
                                    <p class="mt-3">{{ $article['description'] }}</p>
                                    <p class="mt-3">Author: {{ $article['author'] }}</p>
                                    <p>Published at: {{ $article['publishedAt'] }}</p>
                                    <div class="mt-4 flex items-center">
                                        <a class="hover:bg-gray-700 mt-4 border border-blue-500 px-4 py-2 rounded" href="{{ $article['url'] }}" target="_blank">
                                            Read more
                                        </a>

                                        <form method="POST" action="{{ route('favorites.add') }}" class="ml-4">
                                            @csrf
                                            <input type="hidden" name="title" value="{{ $article['title'] }}">
                                            <input type="hidden" name="url" value="{{ $article['url'] }}">
                                            <input type="hidden" name="author" value="{{ $article['author'] }}">
                                            <input type="hidden" name="description" value="{{ $article['description'] }}">
                                            @if(isset($article['urlToImage']))
                                                <input type="hidden" name="imageUrl" value="{{ $article['urlToImage'] }}">
                                            @endif
                                            <button type="submit" class="hover:bg-gray-700 mt-4 border border-blue-500 px-4 py-2 rounded">Add to Favorites</button>
                                        </form>

                                        <form method="GET" action="{{ route('articles.view-comments') }}" class="ml-4">
                                            @csrf
                                            <input type="hidden" name="url" value="{{ $article['url'] }}">
                                            <input type="hidden" name="title" value="{{ $article['title'] }}">
                                            <button type="submit" class="hover:bg-gray-700 mt-4 border border-blue-500 px-4 py-2 rounded">View comments ({{ $commentCount }})</button>
                                        </form>
                                    </div>
                                    <div class="mt-4 flex items-center">
                                        <form method="post" action="{{ route('comments.store') }}" class="mt-4 ml-4 flex items-center w-full">
                                            @csrf
                                            <input type="hidden" name="url" value="{{ $article['url'] }}">
                                            <textarea class="p-6 text-gray-900 dark:text-gray-900 w-full" name="body" rows="3"></textarea>
                                            <button type="submit" class="hover:bg-gray-700 border border-blue-500 px-4 py-2 rounded ml-4">Submit Comment</button>
                                        </form>
                                    </div>
                                </li>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </ul>
            @endif
        </div>
    </div>
</x-app-layout>

