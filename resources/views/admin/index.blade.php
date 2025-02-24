@extends('admin.admin')

@section('title', 'Dashboard')

@section('content')
<div class="container mx-auto p-6">
    <h1 class="text-2xl font-bold mb-6">Dashboard Admin</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @foreach ([
            ['title' => 'Total Member', 'value' => $totalMember],
            ['title' => 'Total Kegiatan', 'value' => $totalActivity],
            ['title' => 'Total Partisipasi', 'value' => $totalParticipation],
            ['title' => 'Tingkat Kehadiran Rata-rata', 'value' => round($averageAttendance, 2) . '%']
        ] as $stat)
        <div class="bg-white p-6 rounded-lg shadow">
            <h2 class="text-lg font-bold mb-2">{{ $stat['title'] }}</h2>
            <p class="text-3xl font-semibold">{{ $stat['value'] }}</p>
        </div>
        @endforeach
    </div>

    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">Grafik Partisipasi dan Kehadiran</h2>
        <div class="relative w-full h-96">
            <canvas id="participationChart"></canvas>
        </div>
    </div>

    @foreach ([
        ['title' => 'Kegiatan Terbaru', 'headers' => ['Nama Kegiatan', 'Divisi', 'Kategori'], 'data' => $recentActivities, 'columns' => ['name', 'owned_by', 'category']],
        ['title' => 'Member dengan Partisipasi Tertinggi', 'headers' => ['NRP', 'Nama', 'Total Partisipasi'], 'data' => $topMembers, 'columns' => ['nrp', 'name', 'total_participation']],
        ['title' => 'Kegiatan yang Paling Banyak Diikuti', 'headers' => ['Nama Kegiatan', 'Total Peserta'], 'data' => $topActivities, 'columns' => ['name', 'total_participants']]
    ] as $section)
    <div class="mt-8">
        <h2 class="text-xl font-bold mb-4">{{ $section['title'] }}</h2>
        <div class="overflow-x-auto bg-white shadow-md rounded-lg">
            <table class="min-w-full border border-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        @foreach ($section['headers'] as $header)
                            <th class="px-6 py-3 text-left text-sm font-semibold text-gray-700">{{ $header }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($section['data'] as $item)
                        <tr>
                            @foreach ($section['columns'] as $column)
                                <td class="px-6 py-4 text-sm text-gray-800">
                                    {{ data_get($item, $column, '-') }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    window.onload = function() {
        var ctx = document.getElementById('participationChart').getContext('2d');

        if (window.participationChartInstance) {
            window.participationChartInstance.destroy();
        }

        window.participationChartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [
                    {
                        label: 'Partisipasi',
                        data: @json($participationData ?? []),
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderWidth: 1
                    },
                    {
                        label: 'Kehadiran',
                        data: @json($attendanceData ?? []),
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderWidth: 1
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    };
</script>
@endsection
