<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Performa Divisi {{ $division['name'] }}</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .member-card {
            background: #f1f1f1;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-wrap: wrap;
        }
        .chart-container {
            flex: 1;
            min-width: 100px;
            height: 200px;
        }
        h1 {
            text-align: center;
        }
        @media print {
            body {
                background: none;
            }
            .container {
                box-shadow: none;
            }
            .member-card {
                page-break-inside: avoid;
            }
            .member-card:nth-child(2n+10) {
                page-break-before: always;
            }
        }
    </style>
</head>
<body>

<div class="container">
    <div class="member-card">
        <div class="member-info" style="flex: 1; min-width: 250px; padding-left: 50px; font-size: 14px;">
            <h3 style="font-size: 13px; font-weight: bold;">Divisi {{ $division->name }}</h3>
            <p style="font-size: 11px;">Rata-rata kehadiran bulan ini : {{ $rerata }}%</p>
            <p style="font-size: 10px;">{!! $pesan !!}</p>
        </div>
    </div>
    @foreach ($reportData as $index => $data)
        <div class="member-card">
            <div class="member-info" style="flex: 1; min-width: 250px; padding-left: 50px; font-size: 14px;">
                <h3 style="font-size: 13px; font-weight: bold;">{{ $data['member']->name }} - {{ $data['member']->nrp }}</h3>
                <p style="font-size: 10px;"><strong>Kegiatan yang diikuti:</strong></p>
                @foreach ($data['monthlyPerformances'][$bulan_int-1]['activityName'] as $item)
                    <li style="font-size: 10px;">{{ $item }}</li>
                @endforeach
            </div>
        </div>
    @endforeach
    @foreach ($reportData as $index => $data)
        <div class="member-card">
            <div class="chart-container">
                <canvas id="performanceChart{{ $data['member']->nrp }}"></canvas>
            </div>
            <div class="member-info" style="flex: 1; min-width: 250px; padding-left: 50px; font-size: 14px;">
                <h3 style="font-size: 13px; font-weight: bold;">{{ $data['member']->name }}</h3>
                <p style="font-size: 10px;"><strong>NRP:</strong> {{ $data['member']->nrp }}</p>
                <p style="font-size: 10px;"><strong>Total Kehadiran:</strong> 
                    {{ $data['monthlyPerformances'][$bulan_int-1]['attendance'] ?? 0 }} 
                    dari 
                    {{ $data['monthlyPerformances'][$bulan_int-1]['must_attend'] ?? 0 }} 
                    ({{ $data['monthlyPerformances'][$bulan_int-1]['attendance_percentage'] ?? 0 }}%)
                </p>
                <p style="font-size: 10px;"><strong>Rekomendasi:</strong> {{ $data['monthlyPerformances'][$bulan_int-1]['recommendation'] }}</p>
            </div>
        </div>
    @endforeach
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var reportData = @json($reportData);

        reportData.forEach(data => {
            var ctx = document.getElementById('performanceChart' + data.member.nrp).getContext('2d');
            var monthlyPerformances = data.monthlyPerformances || [];

            var attendanceData = Array(12).fill(0);
            monthlyPerformances.forEach((performance, index) => {
                attendanceData[index] = performance?.attendance_percentage || 0;
            });

            var performanceData = {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Persentase Kehadiran',
                    data: attendanceData,
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.3)',
                    borderWidth: 2,
                    fill: true
                }]
            };

            new Chart(ctx, {
                type: 'line',
                data: performanceData,
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100,
                            ticks: {
                                stepSize: 10
                            }
                        }
                    }
                }
            });
        });
    });
</script>

</body>
</html>
