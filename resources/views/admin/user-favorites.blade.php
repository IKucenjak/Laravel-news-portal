<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <p class="mt-3">{{ $user->name }}'s Favorite Articles</p>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                @if($user->favorites->count() > 0)
                    <ul>
                        @foreach($user->favorites as $favorite)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <li>
                                @if(isset($favorite->imageUrl))
                                <img src="{{ $favorite->imageUrl }}" alt="Article Image" class="max-w-full mb-4 rounded-md">
                                @endif
                                <strong>{{ $favorite->title }}</strong><br>
                                <p class="mt-3">{{ $favorite->description }}</p>
                                <p class="mt-3">Author: {{ $favorite->author }}</p>
                                <form method="POST" action="{{ route('delete-favorite', ['user_id' => $user->id, 'favorites_id' => $favorite->id]) }}" class="ml-4">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="mt-4 bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded">Delete from favorites</button>
                                </form>
                            </li>
                            </div>
                            </div>
                        @endforeach
                    </ul>
                @else
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            {{ __("No favorite articles found.") }}
                        </div>
                    </div>
                @endif
        </div>
    </div>
</x-app-layout>
