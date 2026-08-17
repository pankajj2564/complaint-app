<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'CGC University Portal') }}</title>

        <!-- Scripts & Styles (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex flex-col">
            
            <!-- Frontend Specific Navbar -->
            <header class="bg-white shadow-sm border-b border-gray-200 p-4">
                <div class="max-w-7xl mx-auto flex justify-between items-center">
                    <h1 class="font-bold text-lg text-indigo-600">CGC Student Portal</h1>
                    <div>
                        @auth
                            <span class="text-sm text-gray-700 mr-4">Hello, {{ Auth::user()->name }}</span>
                            <form method="POST" action="{{ route('logout') }}" class="inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 font-semibold">Logout</button>
                            </form>
                        @else
                            <a href="{{ route('login') }}" class="text-sm text-indigo-600 font-semibold">Login</a>
                        @endauth
                    </div>
                </div>
            </header>

            <!-- Main Dynamic Content -->
            <main class="flex-grow max-w-7xl mx-auto w-full p-6">
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white border-t border-gray-200 text-center py-4 text-xs text-gray-500">
                &copy; {{ date('Y') }} CGC University Mohali. All rights reserved.
            </footer>
        </div>
    </body>
</html>