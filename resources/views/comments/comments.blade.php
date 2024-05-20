<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Article Comments') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-white">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <h3>Comments for Article: <strong>{{ $articleTitle }}</strong></h3>
                </div>
            </div>
            @if($comments->count() > 0)
                <ul>
                    @foreach($comments as $comment)
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-3">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <li class="flex justify-between">
                                <div>
                                    <p>{{ $comment->user->name }} says<strong>:</strong> </p>
                                    <p>{{ $comment->body }} </p>
                                </div>
                                
                                @if($isWithinFirst10Minutes || auth()->user()->role === 'admin')
                                    <a href="{{ route('comments.edit', ['user_id' => $comment->user_id, 'comment_id' => $comment->id]) }}" class="hover:bg-gray-700 border border-blue-500 px-4 py-2 rounded">
                                        Edit Comment
                                    </a>
                                @endif
                            </li>
                        </div>
                    </div>
                    @endforeach
                </ul>
            @else
                <p class="p-6 text-gray-900 dark:text-gray-100">No comments found for this article.</p>
            @endif
        </div>
    </div>
</x-app-layout>
