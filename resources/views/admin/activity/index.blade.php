@extends('admin.admin')

@section('title', 'Activity')

@section('content')
<h1 class="text-2xl font-bold mb-4">Daftar Kegiatan</h1>

<a href="{{ route('activity.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Kegiatan</a>

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

<div class="overflow-x-auto">  {{-- Tambahkan div untuk overflow --}}
    <table class="min-w-full divide-y divide-gray-200 table-auto">
        <thead>
            <tr>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penanggung Jawab</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($activities as $activity)
            <tr>
                <td class="px-4 py-2 whitespace-nowrap">{{ $activity->id }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $activity->name }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $activity->leader->name }}</td>
                <td class="px-4 py-2 whitespace-nowrap">{{ $activity->category }}</td>
                <td class="px-4 py-2 whitespace-nowrap flex space-x-2"> {{-- Flexbox untuk tombol --}}
                    <a href="{{ route('activity.show', $activity->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded">Show</a>
                    <a href="{{ route('activity.edit', $activity->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                    <form action="{{ route('activity.destroy', $activity->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?')">Hapus</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div> {{-- Penutup div overflow --}}
@endsection
