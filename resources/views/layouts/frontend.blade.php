<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        
        <meta name="robots" content="noindex, nofollow">
        
        <title>{{ config('app.name', 'Complaint Portal | CGC University Mohali') }}</title>
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('favicon.svg') }}">
        <!-- Fonts & Tailwind CSS (Vite) -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="bg-gray-50 font-sans antialiased text-gray-900">
        <div class="min-h-screen flex flex-col">
            
            <!-- 1. TOP HEADER (Centralized Logo & Profile Menu) -->
            <header class="bg-white border-b border-gray-200 h-24 fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 shadow-xs">
                <!-- Centralized / Brand Logo -->
                <div class="flex items-center gap-3">
                    <div class="rounded-xl flex items-center justify-center overflow-hidden ">
                    <a href="{{ 
                        Auth::check() 
                            ? (Auth::user()->role == 'admin' 
                                ? route('admin.dashboard') 
                                : (Auth::user()->role == 'employee' 
                                    ? route('employee.dashboard') 
                                    : route('student.dashboard'))) 
                            : url('/auth/login') 
                    }}" class="flex items-center gap-2">
                        <img src="{{ asset('UniversityLogo.svg') }}" {{ $attributes->merge(['class' => 'h-16 w-auto']) }} alt="Logo">
                    </a>
                    </div>
                    
                </div>

                <!-- Profile Related Menu (Dropdown / Auth info) -->
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-indigo-600 font-medium capitalize">{{ Auth::user()->role }}</p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="px-3 py-1.5 bg-gray-100 hover:bg-red-50 hover:text-red-600 text-gray-600 text-xs font-semibold rounded-lg transition">
                            Logout
                        </button>
                    </form>
                </div>
            </header>

            <!-- CONTAINER (Sidebar + Main Content) -->
            <div class="flex pt-16 min-h-screen">

                <!-- 3. MIDDLE CONTENT AREA -->
                <main class="flex-1 md:ml-64 p-6 sm:p-8 bg-gray-50/50">
                    {{ $slot }}
                </main>

            </div>
        </div>
    </body>
</html>