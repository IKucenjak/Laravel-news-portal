<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <div class="flex justify-center items-center">
                @if(session('success'))
                <div class="alert alert-danger my-custom-class" style="background-color: #dff0d8; border-color: #d6e9c6; color: #3c763d; padding: 10px; border: 1px solid transparent; border-radius: 4px; padding-left: 20px; padding-right: 20px; margin-left: 10px; margin-right: 10px;">                        {{ session('success') }}
                    </div>
                @endif
            </div>

            <div class="flex justify-center items-center">
                @if(session('error'))
                    <div class="alert alert-danger my-custom-class" style="background-color: #f2dede; border-color: #ebccd1; color: #a94442; padding: 10px; border: 1px solid transparent; border-radius: 4px; padding-left: 20px; padding-right: 20px; margin-left: 10px; margin-right: 10px;">
                        {{ session('error') }}
                    </div>
                @endif
            </div>


            
            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
