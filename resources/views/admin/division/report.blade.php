@extends('admin.admin')

@section('title', 'Laporan Performa Divisi')

@section('content')

@if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @elseif (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

<div class="container mx-auto p-6 bg-white shadow-lg rounded-lg">
    <h1 class="text-2xl font-bold mb-4 text-center">Laporan Performa Divisi: {{ $division->name }}</h1>

    <form action="{{ route('division.report.print', ['id' => $division->id, 'month' => $selectedMonth]) }}" method="GET" class="mb-4">
            <label for="month" class="mr-2">Bulan & Tahun:</label>
            <input type="month" name="month" id="month" value="{{ $selectedMonth }}" class="border rounded px-3 py-2">
            <button type="submit" class="ml-2 px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Print</button>
            <input type="textarea" name="pesan" id="editor" rows="100" placeholder="Tulis pesan Anda di sini...">
    </form>


    {{-- <a href="{{ route('division.report.print', ['id' => $division->id, 'month' => $selectedMonth]) }}" class="mt-4 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600">Generate PDF</a> --}}

    @foreach ($reportData as $data)
    <div class="mb-8 p-4 border rounded-lg shadow-md bg-gray-50">
        <h2 class="text-xl font-semibold mb-2">{{ $data['member']->name }}</h2>
        <p class="mb-2"><strong>NRP:</strong> {{ $data['member']->nrp }}</p>

        <div class="mb-6">
            <canvas id="performanceChart{{ $data['member']->nrp }}" class="w-full h-64"></canvas>
        </div>
    </div>
    @endforeach

    <a href="{{ route('index') }}" class="mt-4 px-4 py-2 bg-gray-500 text-white rounded hover:bg-gray-600">Kembali</a>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.tiny.cloud/1/c138l29i8n5ghxpkxfb8pu9zb2emhwrdj1rapv0zpxb2trsw/tinymce/7/tinymce.min.js" referrerpolicy="origin"></script>
<script>
    tinymce.init({
        selector: '#editor,#editor2,#editor3,#editor4,#editor5',
        height: 300,
        plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons accordion',
        editimage_cors_hosts: ['picsum.photos'],
        menubar: 'file edit view insert format tools table help',
        toolbar: "undo redo | accordion accordionremove | blocks fontfamily fontsize | bold italic underline strikethrough | align numlist bullist | link image | table media | lineheight outdent indent| forecolor backcolor removeformat | charmap emoticons | code fullscreen preview | save print | pagebreak anchor codesample | ltr rtl",
        autosave_ask_before_unload: true,
        autosave_interval: '30s',
        autosave_prefix: '{path}{query}-{id}-',
        autosave_restore_when_empty: false,
        autosave_retention: '2m',
        image_advtab: true,
        link_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_list: [
            { title: 'My page 1', value: 'https://www.tiny.cloud' },
            { title: 'My page 2', value: 'http://www.moxiecode.com' }
        ],
        image_class_list: [
            { title: 'None', value: '' },
            { title: 'Some class', value: 'class-name' }
        ],
        importcss_append: true,
        file_picker_callback: (callback, value, meta) => {
            /* Provide file and text for the link dialog */
            if (meta.filetype === 'file') {
            callback('https://www.google.com/logos/google.jpg', { text: 'My text' });
            }

            /* Provide image and alt text for the image dialog */
            if (meta.filetype === 'image') {
            callback('https://www.google.com/logos/google.jpg', { alt: 'My alt text' });
            }

            /* Provide alternative source and posted for the media dialog */
            if (meta.filetype === 'media') {
            callback('movie.mp4', { source2: 'alt.ogg', poster: 'https://www.google.com/logos/google.jpg' });
            }
        },
        image_caption: true,
        quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
        noneditable_class: 'mceNonEditable',
        toolbar_mode: 'sliding',
        contextmenu: 'link image table',
        skin: 'oxide',
        content_css: 'default',
        content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:16px }'
    });

    document.addEventListener('DOMContentLoaded', function() {
        var reportData = @json($reportData);

        reportData.forEach(data => {
            var ctx = document.getElementById('performanceChart' + data.member.nrp).getContext('2d');
            var monthlyPerformances = data.monthlyPerformances || [];

            var attendanceData = monthlyPerformances.map(performance => {
                return performance && performance.attendance_percentage ? performance.attendance_percentage : 0;
            });

            var performanceData = {
                labels: ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'],
                datasets: [
                    {
                        label: 'Persentase Kehadiran',
                        data: attendanceData,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.5)',
                        borderWidth: 2,
                        fill: true
                    }
                ]
            };

            var performanceChart = new Chart(ctx, {
                type: 'line',
                data: performanceData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            max: 100
                        }
                    }
                }
            });
        });
    });
</script>
@endpush

@endsection
