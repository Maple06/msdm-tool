@extends('client.master')

@section('title', 'Daftar Member')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-4 text-center">Daftar Member</h1>

    <form method="GET" action="{{ route('client.index') }}" class="mb-6 flex justify-center">
        <input type="text" name="search" placeholder="Cari berdasarkan nama member" value="{{ request('search') }}" class="px-4 py-2 border rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500">
        <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-r-md hover:bg-blue-600">Cari</button>
    </form>

    <div class="overflow-x-auto">
        <table class="w-full border-collapse bg-white shadow-lg rounded-lg">
            <thead>
                <tr class="bg-blue-500 text-white">
                    <th class="px-4 py-2">Nama Member</th>
                    <th class="px-4 py-2">Aksi</th> {{-- Tambahkan kolom Aksi --}}
                </tr>
            </thead>
            <tbody>
                @forelse ($members as $member)
                    <tr class="border-t">
                        <td class="px-4 py-2 text-center">{{ $member->name }}</td>
                        <td class="px-4 py-2 text-center"> {{-- Tambahkan tombol Show --}}
                            <a href="{{ route('client.show', $member->nrp) }}" class="px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Show</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="px-4 py-2 text-center text-gray-500">Tidak ada data ditemukan</td> {{-- colspan jadi 2 --}}
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6 flex justify-center">
        {{ $members->links() }}
    </div>

</div>
@endsection
