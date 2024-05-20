<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <p class="mt-3">{{ $user->name }}'s Comments</p>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if($userComments->count() > 0)
                <ul>
                    @foreach($userComments as $comment)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                            <div class="p-6 text-gray-900 dark:text-gray-100 flex items-center justify-between">
                                <li>
                                    {{ $comment->body }}
                                </li>

                                <div class="flex items-center">
                                    <form method="GET" action="{{ route('comments.edit', ['comment_id' => $comment->id]) }}" class="ml-4">
                                        <button type="submit" class="hover:bg-gray-700 border border-blue-500 px-4 py-2 rounded">Edit</button>
                                    </form>

                                    <form method="POST" action="{{ route('comments.delete', ['comment_id' => $comment->id]) }}" class="ml-4">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="bg-red-600 hover:bg-red-500 px-4 py-2 rounded">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </ul>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <p>No comments found for this user.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
