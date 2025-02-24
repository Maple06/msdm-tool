@extends('admin.admin')

@section('title', 'Create Activity')

@section('content')
<h1 class="text-2xl font-bold mb-4">Tambah Kegiatan</h1>

@if (session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<form action="{{ route('activity.store') }}" method="POST">
    @csrf

    <div class="mb-4">
        <label for="name" class="block text-gray-700 font-bold mb-2">Nama:</label>
        <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
    </div>

    <div class="mb-4">
        <label for="nrp" class="block text-gray-700 font-bold mb-2">Leader:</label>  {{-- Label yang lebih deskriptif --}}
        <select name="nrp" id="nrp" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @foreach ($members as $member)
                <option value="{{ $member->nrp }}">{{ $member->name }}</option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label for="owned_by" class="block text-gray-700 font-bold mb-2">Departemen/Divisi:</label> {{-- Label yang lebih deskriptif --}}
        <select name="owned_by" id="owned_by" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            @foreach ($departements as $departement)
                <option value="{{ $departement->id }}">Departemen {{ $departement->name }}</option>
            @endforeach
            @foreach ($divisions as $division)
                <option value="{{ $division->id }}">Divisi {{ $division->name }}</option>
            @endforeach
            <option value="lainnya">Lainnya</option>
        </select>
    </div>

    <div class="mb-4">
        <label for="must_attend" class="block text-gray-700 font-bold mb-2">Jumlah Wajib Hadir:</label>
        <input type="number" name="must_attend" id="must_attend" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
    </div>

    <div class="mb-4">
        <label for="category" class="block text-gray-700 font-bold mb-2">Kategori:</label>
        <select name="category" id="category" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            <option value="rapat">Rapat</option>
            <option value="proker">Proker</option>
            <option value="lainnya">Lainnya</option>
        </select>
    </div>

    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Simpan</button>
</form>

@endsection
