<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('User Settings') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('settings.update') }}" method="post" >
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="defaultCategory" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Default Category:</label>
                        <select name="defaultCategory" id="defaultCategory" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:focus:border-blue-500 sm:text-sm">
                            <option value="business" {{ optional($userSettings)->default_category === 'business' ? 'selected' : '' }}>Business</option>
                            <option value="technology" {{ optional($userSettings)->default_category === 'technology' ? 'selected' : '' }}>Technology</option>
                            <option value="entertainment" {{ optional($userSettings)->default_category === 'entertainment' ? 'selected' : '' }}>Entertainment</option>
                            <option value="general" {{ optional($userSettings)->default_category === 'general' ? 'selected' : '' }}>General</option>
                            <option value="health" {{ optional($userSettings)->default_category === 'health' ? 'selected' : '' }}>Health</option>
                            <option value="science" {{ optional($userSettings)->default_category === 'science' ? 'selected' : '' }}>Science</option>
                            <option value="sports" {{ optional($userSettings)->default_category === 'sports' ? 'selected' : '' }}>Sports</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label for="articlesPerPage" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Articles per page:</label>
                        <select name="articlesPerPage" id="articlesPerPage" class="mt-1 block w-full p-2 border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 rounded-md shadow-sm focus:outline-none focus:ring focus:border-blue-300 dark:focus:border-blue-500 sm:text-sm">
                            @for ($i = 5; $i <= 100; $i += 5)
                                <option value="{{ $i }}" {{ optional($userSettings)->articles_per_page == $i ? 'selected' : '' }}>{{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-md">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
