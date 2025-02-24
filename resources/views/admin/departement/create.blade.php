@extends('admin.admin')

@section('title', 'Tambah Departemen Baru')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Tambah Departemen Baru</h2>
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('departement.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="id" class="block text-gray-700 font-bold mb-2">ID:</label>
                <input type="text" name="id" id="id" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-gray-700 font-bold mb-2">Nama:</label>
                <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
        </form>
    </div>
@endsection
