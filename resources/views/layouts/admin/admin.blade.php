<!-- resources/views/layouts/admin.blade.php -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Dynamic Title -->
    <title>@yield('title', 'Admin Panel') - Complaint Portal</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    <!-- Common Header / Navbar -->
    <nav class="bg-blue-600 text-white p-4 shadow-md flex justify-between items-center">
        <h1 class="text-xl font-bold">
            <a href="{{ route('admin.dashboard') }}">Complaint Portal Admin</a>
        </h1>
        <div class="flex items-center space-x-4">
            <span>Welcome, {{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600 text-sm">Logout</button>
            </form>
        </div>
    </nav>

    <!-- Main Dynamic Content -->
    <main class="flex-grow max-w-7xl mx-auto w-full p-6">
        @yield('content')
    </main>

    <!-- Common Footer -->
    <footer class="bg-gray-800 text-white text-center p-4 mt-auto">
        <p>&copy; {{ date('Y') }} CGC University Complaint Portal. All rights reserved.</p>
    </footer>

</body>
</html>