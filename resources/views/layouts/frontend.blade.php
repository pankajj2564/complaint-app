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
    <body class="bg-gray-50 font-sans antialiased text-gray-900 flex flex-col min-h-screen">
        
        <!-- TOP HEADER (Logo on Left & Secondary Image on Right) -->
        <header class="bg-white border-b border-gray-200 h-24 fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-6 shadow-xs">
            
            <!-- Left: Main Brand Logo -->
            <div class="flex items-center gap-3">
                <a href="{{ 
                    Auth::check() 
                        ? (Auth::user()->role == 'admin' 
                            ? route('admin.dashboard') 
                            : (Auth::user()->role == 'employee' 
                                ? route('employee.dashboard') 
                                : route('student.dashboard'))) 
                        : url('/auth/login') 
                }}" class="flex items-center gap-2">
                    <img src="{{ asset('UniversityLogo.svg') }}" class="h-16 w-auto" alt="University Logo">
                </a>
            </div>

            <!-- Right: Secondary Image / Accreditation / Badge -->
            <?php /* <div class="flex items-center gap-3">
                <!-- Apni dusri image ka path yahan asset() ke andar dein -->
                <img src="{{ asset('secondary-logo.svg') }}" class="h-12 w-auto" alt="Secondary Logo">
            </div> */ ?>

        </header>

        <!-- MAIN CONTAINER (pt-24 rakha hai taaki fixed header content ko na dhake) -->
        <div class="flex-grow pt-24 flex flex-col">
            <main class="flex-1 max-w-7xl mx-auto w-full p-6 sm:p-8 bg-gray-50/50">
                {{ $slot }}
            </main>
        </div>

        <!-- FOOTER (Copyright Info) -->
        <footer class="bg-white border-t border-gray-200 py-4 px-6 text-center text-xs text-gray-500 mt-auto shadow-inner">
            &copy; {{ date('Y') }} CGC University Mohali. All rights reserved.
        </footer>

    </body>
</html>