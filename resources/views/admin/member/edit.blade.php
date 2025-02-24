@extends('admin.admin')

@section('title', 'Edit Member')

@section('content')
    <h1 class="text-2xl font-bold mb-4">Edit Member</h1>

    @if (session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
        <form action="{{ route('member.update', $member->nrp) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">NRP</label>
                <input type="text" name="nrp" value="{{ $member->nrp }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                <input type="text" name="name" value="{{ $member->name }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Email</label>
                <input type="email" name="email" value="{{ $member->email }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Telepon</label>
                <input type="text" name="phone" value="{{ $member->phone }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Role</label>
                <select name="role" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    <option value="staff" {{ $member->role == 'staff' ? 'selected' : '' }}>Staff</option>
                    <option value="headofdivision" {{ $member->role == 'headofdivision' ? 'selected' : '' }}>Head of Division</option>
                    <option value="headofdepartement" {{ $member->role == 'headofdepartement' ? 'selected' : '' }}>Head of Department</option>
                    <option value="secretary" {{ $member->role == 'secretary' ? 'selected' : '' }}>Secretary</option>
                    <option value="finance" {{ $member->role == 'finance' ? 'selected' : '' }}>Finance</option>
                    <option value="vicechairman" {{ $member->role == 'vicechairman' ? 'selected' : '' }}>Vice Chairman</option>
                    <option value="chairman" {{ $member->role == 'chairman' ? 'selected' : '' }}>Chairman</option>
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Divisi</label>
                <select name="division_code" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach ($divisions as $division)
                        <option value="{{ $division->id }}" {{ $member->division_code == $division->id ? 'selected' : '' }}>
                            {{ $division->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Departemen</label>
                <select name="departement_code" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                    @foreach ($departements as $departement)
                        <option value="{{ $departement->id }}" {{ $member->departement_code == $departement->id ? 'selected' : '' }}>
                            {{ $departement->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center justify-between">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Update
                </button>
            </div>
        </form>
@endsection
