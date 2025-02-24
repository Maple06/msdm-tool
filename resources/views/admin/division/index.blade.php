@extends('admin.admin')

@section('title', 'Daftar Divisi')

@section('content')
    <div class="container mx-auto p-6">
        <h2 class="text-xl font-bold mb-4">Daftar Divisi</h2>

        <a href="{{ route('division.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4 inline-block">Tambah Divisi</a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach ($divisions as $division)
                <div class="bg-white shadow rounded p-4">
                    <h3 class="font-bold mb-2">{{ $division->name }}</h3>
                    <p class="text-gray-600 mb-2">ID: {{ $division->id }}</p>
                    <div class="flex space-x-2">
                        <a href="{{ route('division.show', $division->id) }}" class="bg-blue-500 text-white px-2 py-1 rounded">Show</a>
                        <a href="{{ route('division.edit', $division->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded">Edit</a>
                        <form action="{{ route('division.destroy', $division->id) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus divisi ini?')">Hapus</button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
