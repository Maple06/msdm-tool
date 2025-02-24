@extends('admin.admin')

@section('title', 'Detail Activity')

@section('content')
    <div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
        <h1 class="text-2xl font-bold mb-4">Detail Activity</h1>

        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        <!-- Detail Activity -->
        <div class="mb-6">
            <h2 class="text-xl font-bold">Nama Aktivitas: {{ $activity->name }}</h2>
            <p class="text-gray-700">Ketua Pelaksana: {{ $activity->leader->name }}</p>
            <p class="text-gray-700">Divisi Penanggung Jawab: {{ $activity->owned_by }}</p>
        </div>

        <!-- Form Upload CSV -->
        <div class="mb-6 bg-gray-100 p-4 rounded">
            <h2 class="text-lg font-bold mb-2">Import Participants (CSV)</h2>
            <form action="{{ route('participant.import', $activity->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="csv_file" accept=".csv" required class="block w-full text-gray-700 border rounded p-2 mb-4">
                <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Upload CSV
                </button>
            </form>
        </div>

        <!-- Button Tambah Participant -->
        <button onclick="openModal()" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded mb-4">
            Tambah Participant Manual
        </button>

        <!-- Daftar Participants -->
        <h2 class="text-lg font-bold mb-4">Daftar Participants</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NRP</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($participants as $index => $participant)
                        <tr>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $index + 1 }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $participant->nrp }}</td>
                            <td class="px-4 py-2 whitespace-nowrap">{{ $participant->member->name }}</td>
                            <td class="px-4 py-2 whitespace-nowrap flex space-x-2">
                                <form action="{{ route('participant.destroy', $participant->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded" onclick="return confirm('Apakah Anda yakin ingin menghapus participant ini?')">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">Belum ada participant</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Tambah Participant -->
    <div id="modalTambahParticipant" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden flex items-center justify-center">
        <div class="bg-white p-6 rounded-lg w-1/3 shadow-lg">
            <h2 class="text-xl font-bold mb-4">Tambah Participants</h2>

            <!-- Pencarian -->
            <input type="text" id="searchMember" placeholder="Cari NRP atau Nama..." class="w-full p-2 border rounded mb-4">

            <form action="{{ route('activity.participant.store', $activity->id) }}" method="POST">
                @csrf
                <div id="member-list" class="max-h-64 overflow-y-auto">
                    @foreach ($members as $member)
                        <div class="flex items-center space-x-2 p-2 border-b">
                            <input type="checkbox" name="participants[]" value="{{ $member->nrp }}">
                            <span class="font-semibold">{{ $member->nrp }}</span>
                            <span>{{ $member->name }}</span>
                        </div>
                    @endforeach
                </div>

                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" onclick="closeModal()" class="bg-gray-500 text-white px-4 py-2 rounded">Batal</button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript -->
    <script>
        function openModal() {
            document.getElementById('modalTambahParticipant').classList.remove('hidden');
        }

        function closeModal() {
            document.getElementById('modalTambahParticipant').classList.add('hidden');
        }

        document.getElementById('searchMember').addEventListener('input', function() {
            let query = this.value.toLowerCase();
            let members = document.querySelectorAll('#member-list div');

            members.forEach(member => {
                let text = member.innerText.toLowerCase();
                if (text.includes(query)) {
                    member.style.display = 'flex';
                } else {
                    member.style.display = 'none';
                }
            });
        });
    </script>
@endsection
