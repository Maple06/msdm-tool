<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    @stack('styles')
</head>
<body class="bg-gray-100 font-sans">
    <!-- Navbar -->
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <a href="#" class="text-lg font-semibold">Sistem Monitoring</a>
            <ul class="flex space-x-4">
                <li><a href="{{ route('client.index') }}" class="hover:underline">Home</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 mt-10">
        &copy; 2024 Sistem Monitoring | All Rights Reserved
    </footer>

    @stack('scripts')
</body>
</html>
