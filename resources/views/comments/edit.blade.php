<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            <p class="mt-3">{{ __('Edit Comment') }}</p>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    @if($isWithinFirst10Minutes || auth()->user()->role === 'admin')
                        <form method="POST" action="{{ route('comments.update', ['comment_id' => $comment->id]) }}">
                            @csrf
                            @method('PUT')

                            <label for="body" class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                                {{ __('Comment') }}
                            </label>
                            <textarea id="body" name="body" rows="4" class="form-input rounded-md shadow-sm mt-1 block text-gray-900 dark:text-gray-900 w-full"
                                      required>{{ old('body', $comment->body) }}</textarea>
                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="hover:bg-gray-700 border border-blue-500 px-4 py-2 rounded">
                                    {{ __('Update Comment') }}
                                </button>
                            </div>
                        </form>
                    @else
                        <!-- Display a message or redirect the user as needed -->
                        <p>You do not have permission to edit this comment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
