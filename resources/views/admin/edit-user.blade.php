<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit User Information') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-900">
                    <form method="POST" action="{{ route('admin.update-user', ['user_id' => $user->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="name" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Name:</label>
                            <input type="text" name="name" id="name" value="{{ $user->name }}" class="mt-1 p-2 w-full" required>
                        </div>

                        <div class="mb-4">
                            <label for="category" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Category:</label>
                            <select name="category" id="category" class="mt-1 p-2 w-full" required>
                                <option value="business" {{ $user->category === 'business' ? 'selected' : '' }}>Business</option>
                                <option value="technology" {{ $user->category === 'technology' ? 'selected' : '' }}>Technology</option>
                                <option value="entertainment" {{ $user->category === 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                                <option value="general" {{ $user->category === 'general' ? 'selected' : '' }}>General</option>
                                <option value="health" {{ $user->category === 'health' ? 'selected' : '' }}>Health</option>
                                <option value="science" {{ $user->category === 'science' ? 'selected' : '' }}>Science</option>
                                <option value="sports" {{ $user->category === 'sports' ? 'selected' : '' }}>Sports</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="role" class="block text-sm font-medium text-gray-600 dark:text-gray-300">Role:</label>
                            <select name="role" id="role" class="mt-1 p-2 w-full" required>
                                <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>User</option>
                            </select>
                        </div>
                        <div class="mt-4">
                            <button type="submit" class="hover:bg-gray-700 border border-blue-500 text-white px-4 py-2 rounded">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
