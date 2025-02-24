@extends('admin.admin')

@section('title', 'Daftar Member')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Daftar Member</h1>

    <!-- Tombol Tambah Member -->
    <a href="{{ route('member.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Member</a>

    <!-- Form Upload Excel -->
    <form action="{{ route('member.import') }}" method="POST" enctype="multipart/form-data" class="mb-4">
        @csrf
        <div class="flex space-x-2">
            <input type="file" name="files" accept=".xlsx, .xls, .csv" required class="border rounded py-2 px-3">
            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Import Excel</button>
        </div>
    </form>

    <!-- Notifikasi -->
    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <!-- Tabel Member -->
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead>
                <tr class="bg-gray-100">
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">...</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($members as $member)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->nrp }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->email }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">...</td>
                        <td class="px-4 py-2 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('member.edit', $member->nrp) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                            <form action="{{ route('member.destroy', $member->nrp) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus member ini?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
