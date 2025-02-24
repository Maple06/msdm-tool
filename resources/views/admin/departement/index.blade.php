@extends('admin.admin')

@section('title', 'Daftar Departemen')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Daftar Departemen</h2>

        <a href="{{ route('departement.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mb-4">Tambah Departemen</a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <table class="w-full border-collapse border">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">ID</th>
                    <th class="border px-4 py-2">Nama</th>
                    <th class="border px-4 py-2">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($departements as $departement)
                    <tr>
                        <td class="border px-4 py-2">{{ $departement->id }}</td>
                        <td class="border px-4 py-2">{{ $departement->name }}</td>
                        <td class="border px-4 py-2">
                            <a href="{{ route('departement.edit', $departement->id) }}" class="bg-yellow-500 text-white px-2 py-1 rounded mr-2">Edit</a>
                            <form action="{{ route('departement.destroy', $departement->id) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 text-white px-2 py-1 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus departemen ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
