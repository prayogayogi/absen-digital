<x-filament::page>
    <div class="-mt-6 mb-4">
        <h2 class="text-xl font-bold text-gray-900 dark:text-white">
            Hallo, Selamat datang {{ auth()->user()->name }}
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-300">
            Berikut ini adalah ringkasan data terbaru.
        </p>
    </div>

    <div class="-mt-6 grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="p-4 rounded-xl shadow bg-white dark:bg-gray-800 transition-colors duration-300">
            <p class="text-sm text-gray-600 dark:text-gray-300">Total Siswa</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $siswaCount }}</p>
            <div class="mt-2">
                <canvas id="sparkline-berita" height="30"></canvas>
            </div>
        </div>

        <div class="p-4 rounded-xl shadow bg-white dark:bg-gray-800 transition-colors duration-300">
            <p class="text-sm text-gray-600 dark:text-gray-300">Total Class</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $classCount }}</p>
            <div class="mt-2">
                <canvas id="sparkline-pengumuman" height="30"></canvas>
            </div>
        </div>

        <div class="p-4 rounded-xl shadow bg-white dark:bg-gray-800 transition-colors duration-300">
            <p class="text-sm text-gray-600 dark:text-gray-300">Total Teacher</p>
            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $teacherCount }}</p>
            <div class="mt-2">
                <canvas id="sparkline-artikel" height="30"></canvas>
            </div>
        </div>
    </div>

    <div class="rounded-xl bg-white dark:bg-gray-800 p-4 shadow mb-6 max-w-sm mx-auto">
        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 text-center">Scan QR Code</h3>
        <div class="relative">
            {{-- <video id="preview" class="w-full aspect-video rounded-lg border border-gray-300 dark:border-gray-700"></video> --}}

            {{-- Garis kotak penanda --}}
            <div class="absolute top-1/4 left-1/4 w-1/2 h-1/2 border-4 border-green-500 rounded-lg pointer-events-none"></div>
        </div>
    </div>
</x-filament::page>

@push('scripts')
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const videoElement = document.getElementById('preview');
            if (!videoElement) return;

            const scanner = new Instascan.Scanner({ video: videoElement, mirror: true });

            scanner.addListener('scan', function (content) {
                fetch('/scan-absen', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': @json(csrf_token())
                    },
                    body: JSON.stringify({ nisn: content.trim() })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'success') {
                        alert('✅ ' + data.message);
                    } else if (data.status === 'exists') {
                        alert('⚠️ ' + data.message);
                    } else {
                        alert('❌ ' + data.message);
                    }
                })
                .catch(error => {
                    console.table('Error:', error);
                    // alert('Terjadi kesalahan saat menyimpan absensi.');
                });
            });

            Instascan.Camera.getCameras().then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                } else {
                    alert('Tidak ada kamera ditemukan.');
                }
            }).catch(function (e) {
                console.error(e);
                alert('Gagal mengakses kamera. Periksa izin browser.');
            });
        });
    </script>
@endpush
