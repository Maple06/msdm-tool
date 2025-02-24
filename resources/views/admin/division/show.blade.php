@extends('admin.admin')

@section('title', 'Detail Divisi')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow rounded">
        <h2 class="text-xl font-bold mb-4">Detail Divisi</h2>

        <div class="mb-4">
            <strong>Nama:</strong> {{ $division->name }}
        </div>

        <div class="mb-4">
            <strong>Departemen:</strong> {{ $division->departement->name }}
        </div>

        <h3 class="text-lg font-semibold mb-2">Daftar Anggota:</h3>

        @if ($division->members->count() > 0)
            <table class="w-full border-collapse border">
                <thead>
                    <tr class="bg-gray-200">
                        <th class="border px-4 py-2">NRP</th>
                        <th class="border px-4 py-2">Nama</th>
                        </tr>
                </thead>
                <tbody>
                    @foreach ($division->members as $member)
                        <tr>
                            <td class="border px-4 py-2">{{ $member->nrp }}</td>
                            <td class="border px-4 py-2">{{ $member->name }}</td>
                            </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p>Tidak ada anggota dalam divisi ini.</p>
        @endif

        <a href="{{ route('division.index') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Kembali</a>
        <a href="{{ route('division.report',$division->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4">Print Laporan</a>
    </div>
@endsection
