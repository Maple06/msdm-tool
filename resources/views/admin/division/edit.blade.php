@extends('admin.admin')

@section('title', 'Edit Divisi')

@section('content')
<div class="container mx-auto p-6 bg-white shadow rounded">
    <h2 class="text-xl font-bold mb-4">Edit Divisi</h2>

    @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

    <form action="{{ route('division.update', $division->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label for="name" class="block text-gray-700 font-bold mb-2">Nama:</label>
            <input type="text" name="name" id="name" class="border rounded w-full py-2 px-3" value="{{ $division->name }}" required>
        </div>

        <div class="mb-4">
            <label for="departement_code" class="block text-gray-700 font-bold mb-2">Departemen:</label>
            <select name="departement_code" id="departement_code" class="border rounded w-full py-2 px-3" required>
                @foreach ($departements as $departement)
                    <option value="{{ $departement->id }}" {{ $division->departement_code == $departement->id ? 'selected' : '' }}>{{ $departement->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
    </form>
</div>
@endsection
