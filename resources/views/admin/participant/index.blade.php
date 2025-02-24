@extends('admin.admin')

@section('title', 'Daftar Member')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Daftar Member</h1>

    <a href="{{ route('member.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Tambah Member</a>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach ($participants as $index => $member)
                    <tr>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->nrp }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->member->name }}</td>
                        <td class="px-4 py-2 whitespace-nowrap">{{ $member->email }}</td>
                        <td class="px-4 py-2 whitespace-nowrap flex space-x-2">
                            <a href="{{ route('member.edit', $member->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                            <form action="{{ route('member.destroy', $member->id) }}" method="POST" class="inline">
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
