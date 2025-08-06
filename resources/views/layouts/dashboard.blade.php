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

        <!-- Styles -->
        @livewireStyles
    </head>
    <body class="font-sans antialiased bg-gray-100 dark:bg-gray-900">
        <x-banner />

        <div class="min-h-screen flex">
            <!-- Sidebar -->
            <aside class="hidden md:flex md:w-64 bg-white dark:bg-gray-800">
                <div class="flex flex-col w-full">
                    <div class="h-16 flex items-center px-6">
                        <img src="/images/feednity-logo.svg" alt="Feednity" class="h-8 w-auto" />
                    </div>
                    <!-- Sidebar content -->
                </div>
            </aside>

            <!-- Main content area -->
            <div class="flex-1 flex flex-col">
                <!-- Top navigation -->
                <nav class="bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                        <div class="flex justify-between h-16">
                            <div class="flex items-center">
                                <!-- Mobile sidebar toggle -->
                                <button class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                                    </svg>
                                </button>
                                <img src="/images/feednity-logo.svg" alt="Feednity" class="h-8 w-auto ml-4 md:ml-0" />
                            </div>
                            <div class="flex items-center">
                                <!-- Top nav content -->
                            </div>
                        </div>
                    </div>
                </nav>

                @if (isset($title) || isset($breadcrumbs))
                    <header class="bg-gray-50 dark:bg-gray-900 border-b border-gray-200 dark:border-gray-700">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            @isset($title)
                                <h1 class="text-2xl font-semibold text-gray-900 dark:text-gray-100">{{ $title }}</h1>
                            @endisset
                            @isset($breadcrumbs)
                                <div class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $breadcrumbs }}
                                </div>
                            @endisset
                        </div>
                    </header>
                @endif

                <!-- Page Content -->
                <main class="flex-1 p-4">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
