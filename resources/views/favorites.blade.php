<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Favorites') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-white">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Here are your favorite news articles!") }}
                </div>
            </div>
                @if(count($favorites) > 0)
                <ul>
                    @foreach($favorites as $favorite)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <li>
                        @if(isset($favorite->imageUrl))
                        <img src="{{ $favorite->imageUrl }}" alt="Article Image" class="max-w-full mb-4 rounded-md">
                        @endif
                        <strong>{{ $favorite->title }}</strong><br>
                        <p class="mt-3">{{ $favorite->description }}</p>
                        <p class="mt-3">Author: {{ $favorite->author }}</p>
                        <div class="mt-4 flex items-center">
                            <a class="hover:bg-gray-700 mt-4 border border-blue-500 px-4 py-2 rounded" href="{{ $favorite->url }}" target="_blank">
                                Read more
                            </a>

                            <!-- Delete Button -->
                            <form method="POST" action="{{ route('favorites.delete', ['favourite_id' => $favorite->id]) }}" class="ml-4">
                                @csrf
                                @method('DELETE')

                                <button type="submit" class="mt-4 bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded">Delete from favorites</button>
                            </form>
                        </div>
                    </li>
                    </div>
                    </div>
                    @endforeach
                </ul>
                @endif
        </div>
    </div>
</x-app-layout>
