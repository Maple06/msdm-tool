@extends('client.master')

@section('title', 'Performa Tahunan Anggota')

@section('content')
<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-4 text-center">Performa Tahunan Anggota</h1>

    <div class="mb-4">
        <p><strong>Nama:</strong> {{ $member->name }}</p>
        <p><strong>Kehadiran : </strong>{{ $performances['total_attendance'] }} dari {{ $performances['total_must_attend'] }} yang wajib dihadiri</p>
        <p><strong>Aktivitas yang di ikuti: </strong> {{ $performances['total_participation'] }} dari {{ $performances['total_event'] }} total event yang ada di himpunan</p>
    </div>

    <div class="mb-6">
        <canvas id="performanceChart" class="w-full h-64"></canvas>
    </div>

    <a href="{{ route('client.index') }}" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Kembali</a>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('performanceChart').getContext('2d');
        var monthlyPerformances = @json($monthlyPerformances);

        var performanceData = {
            labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
            datasets: [
                {
                    label: 'Persentase Kehadiran',
                    data: monthlyPerformances.map(performance => performance.attendance_percentage),
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.5)',
                    borderWidth: 2,
                    fill: true
                }
            ]
        };

        var performanceChart = new Chart(ctx, {
            type: 'line', // Atau 'bar' jika Anda mau grafik batang
            data: performanceData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: 100 // Sesuaikan sesuai kebutuhan
                    }
                }
            }
        });
    </script>
@endpush

@endsection
