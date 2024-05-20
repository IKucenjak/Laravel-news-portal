<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg border border-white">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    {{ __("Here are the users!") }}
                </div>
            </div>

            @if(count($users) > 0)
                <ul>
                    @foreach($users as $user)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <li>
                                    <strong>{{ $user->name }}</strong><br>
                                    <p class="mt-3">Email: {{ $user->email }}</p>
                                    <p class="mt-3">Role: {{ $user->role }}</p>
                                    <p class="mt-3">Category: {{ $user->category }}</p>
                                    <div class="mt-4 flex items-center justify-between">
                                        <div>
                                            <a class="hover:bg-gray-700 border border-blue-500 px-4 py-2 rounded" href="{{ route('admin.edit-user', ['user_id' => $user->id]) }}">Edit user data</a>
                                            <a class="hover:bg-gray-700 ml-4 border border-blue-500 px-4 py-2 rounded" href="{{ route('admin.user-comments', ['user_id' => $user->id]) }}">Edit user comments</a>
                                            <a class="hover:bg-gray-700 ml-4 border border-blue-500 px-4 py-2 rounded" href="{{ route('admin.user-favorites', ['user_id' => $user->id]) }}">View user favorites</a>
                                        </div>

                                        <form id="deleteUserForm{{ $user->id }}" method="POST" action="{{ route('admin.delete-user', ['user_id' => $user->id]) }}" class="ml-4">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button" class="bg-red-600 hover:bg-red-500 text-white px-4 py-2 rounded" onclick="confirmDeleteUser('{{ $user->id }}')">Delete User</button>
                                        </form>
                                    </div>

                                    <script>
                                        function confirmDeleteUser(user_id) {
                                            var confirmation = confirm('Are you sure you want to delete this user?');

                                            if (confirmation) {
                                                document.getElementById('deleteUserForm' + user_id).submit();
                                            }
                                        }
                                    </script>
                                </li>
                            </div>
                        </div>
                    @endforeach
                </ul>
            @else
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mt-4">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        {{ __("No users found.") }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
