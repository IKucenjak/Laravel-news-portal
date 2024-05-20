<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-white">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Here you can search all the news!") }}
                </div>
            </div>
                <!-- Search Form -->
                <form action="{{ route('dashboard') }}" method="get" class="mt-4">
                        <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3">Search Keywords:</label>
                        <input type="text" name="search" id="search" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:focus:border-blue-500 sm:text-sm" placeholder="Enter keywords">
                        
                        <label for="sort" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3">Sort By:</label>
                        <select name="sort" id="sort" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:focus:border-blue-500 sm:text-sm">
                            @foreach($sortOptions as $option)
                                <option value="{{ $option }}" @if(request('sort') === $option) selected @endif>{{ ucfirst($option) }}</option>
                            @endforeach
                        </select>

                        <label for="language" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mt-3">Select Language:</label>
                        <select name="language" id="language" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:focus:border-blue-500 sm:text-sm">
                            @foreach($languages as $code => $name)
                            <option value="{{ $code }}" @if(request('language') === $code) selected @endif>{{ ucfirst($name) }}</option>                            @endforeach
                        </select>
                        
                        <button type="submit" class="hover:bg-gray-700 mt-3 px-4 py-2 border border-blue-500 text-white rounded-md">Search</button>
                        
                </form>
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

                                    <!-- Add to Favorites Form -->
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

                                    <!-- View Comments Form -->
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

