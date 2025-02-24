<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-blue-500 p-4">
        <div class="container mx-auto flex justify-between items-center">

            <a href="#" class="text-white font-bold text-xl">MSDM TOOL</a>

            <div class="flex space-x-4">
                <a href="{{ route('index') }}" class="text-white hover:text-gray-200">Dashboard</a>

                {{-- Dropdown Resource (Departemen, Divisi, Anggota) --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block">
                    <button @click="open = !open" class="text-white hover:text-gray-200">Resource</button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <a href="{{ route('departement.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Daftar Departemen</a>
                        <a href="{{ route('division.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-t">Daftar Divisi</a>
                        <a href="{{ route('member.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-t">Daftar Anggota</a>
                    </div>
                </div>

                {{-- Dropdown Aktivitas --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block">
                    <button @click="open = !open" class="text-white hover:text-gray-200">Aktivitas</button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <a href="{{ route('attendance.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Kehadiran</a>
                        <a href="{{ route('activity.index') }}" class="block px-4 py-2 text-gray-800 hover:bg-gray-100 border-t">Aktivitas</a>
                    </div>
                </div>

                {{-- Dropdown Pengaturan --}}
                <div x-data="{ open: false }" @click.away="open = false" class="relative inline-block">
                    <button @click="open = !open" class="text-white hover:text-gray-200">Pengaturan</button>
                    <div x-show="open" x-transition class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg z-10">
                        <a href="#" class="block px-4 py-2 text-gray-800 hover:bg-gray-100">Profil</a>

                        <!-- Form Logout -->
                        <form action="{{ route('logout') }}" method="POST" class="border-t">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-2 text-gray-800 hover:bg-gray-100">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        @yield('content')
    </div>

    @stack('scripts')
</body>
</html>
