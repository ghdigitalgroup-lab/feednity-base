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
    <body x-data="{ sidebarOpen: window.innerWidth >= 768 }" @resize.window="sidebarOpen = window.innerWidth >= 768" class="font-sans antialiased">
        <x-banner />

        <div class="min-h-screen bg-gray-100 flex">
            <!-- Mobile overlay -->
            <div x-show="sidebarOpen" class="fixed inset-0 z-20 bg-black bg-opacity-50 md:hidden" @click="sidebarOpen = false"></div>

            <!-- Sidebar -->
            <aside x-show="sidebarOpen" x-transition class="fixed inset-y-0 left-0 z-30 w-64 overflow-y-auto bg-white shadow md:static md:block">
                <nav class="mt-5 space-y-1 px-2">
                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('dashboard') ? 'bg-gray-200 font-semibold' : '' }}">Dashboard</a>
                    <a href="{{ route('stores.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('stores.*') ? 'bg-gray-200 font-semibold' : '' }}">Connected Stores</a>
                    <a href="{{ route('rules.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('rules.*') ? 'bg-gray-200 font-semibold' : '' }}">Rule Engine</a>
                    <a href="{{ route('feeds.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('feeds.*') ? 'bg-gray-200 font-semibold' : '' }}">Feeds</a>
                    <a href="{{ route('billing.index') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('billing.*') ? 'bg-gray-200 font-semibold' : '' }}">Billing</a>
                    <a href="{{ route('profile.show') }}" class="block px-4 py-2 rounded hover:bg-gray-200 {{ request()->routeIs('profile.*') ? 'bg-gray-200 font-semibold' : '' }}">Account Settings</a>
                </nav>
            </aside>

            <!-- Content -->
            <div class="flex-1 flex flex-col">
                <!-- Mobile header -->
                <header class="bg-white shadow md:hidden">
                    <button @click="sidebarOpen = true" class="p-4 focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                </header>

                <main class="flex-1">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('modals')

        @livewireScripts
    </body>
</html>
