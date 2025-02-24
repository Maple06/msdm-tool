@extends('admin.admin')

@section('title', 'Attendance List')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-4">Attendance List</h1>

    <a href="{{ route('attendance.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mb-4 inline-block">Add Attendance</a>

    @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

    <form action="{{ route('attendance.import') }}" method="POST" enctype="multipart/form-data" class="mb-4 flex items-center"> {{-- Flexbox untuk form import --}}
        @csrf
        <input type="file" name="files" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline mr-2"> {{-- Margin right untuk button --}}
        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">Import Excel</button>
    </form>

    <div class="overflow-x-auto"> {{-- Tambahkan div untuk overflow --}}
        <table class="min-w-full divide-y divide-gray-200 table-auto">
            <thead>
                <tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($attendances as $index => $attendance)
                <tr>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $attendance->nrp }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ $attendance->member->name }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        {{ $attendance->participant && $attendance->participant->activity ? $attendance->participant->activity->name : $attendance->volunteer->activity->name }}
                    </td>
                    <td class="px-4 py-2 whitespace-nowrap">{{ ucfirst($attendance->status) }}</td>
                    <td class="px-4 py-2 whitespace-nowrap">
                        <a href="{{ route('attendance.edit', $attendance->id) }}" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-1 px-2 rounded">Edit</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div> {{-- Penutup div overflow --}}
</div>
@endsection
