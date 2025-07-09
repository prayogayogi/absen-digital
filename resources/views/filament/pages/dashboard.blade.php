<x-filament::page>
    <div x-data="{ open: false }" x-init="
        $watch('open', value => {
            if (value) {
                startScanner();
            } else {
                stopScanner();
            }
        })
    ">

        {{-- Header --}}
        <div class="-mt-6 mb-4">
            <h2 class="text-xl font-bold text-gray-900 dark:text-white">
                Hallo, Selamat datang {{ auth()->user()->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-300">
                Berikut ini adalah ringkasan data terbaru.
            </p>
        </div>

        {{-- Stat cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            @foreach ([['Total Siswa', $siswaCount], ['Total Class', $classCount], ['Total Teacher', $teacherCount]] as [$label, $value])
                <div class="p-4 rounded-xl shadow bg-white dark:bg-gray-800 transition-colors duration-300">
                    <p class="text-sm text-gray-600 dark:text-gray-300">{{ $label }}</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $value }}</p>
                    <div class="mt-2"><canvas height="30"></canvas></div>
                </div>
            @endforeach
        </div>

        {{-- Tombol buka modal --}}
        <div class="text-center mb-6 mt-6">
            <button @click="open = true" class="px-4 py-2 bg-primary-600 text-white rounded-lg shadow hover:bg-primary-700 transition">
                Scan QR Code
            </button>
        </div>

        {{-- Modal --}}
        <div x-cloak x-show="open" class="fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div
                class="bg-white dark:bg-gray-900 rounded-xl p-6 w-full max-w-md shadow relative"
                {{-- HAPUS BARIS INI:
                @click.outside="open = false"
                --}}
            >
                {{-- Tombol X kanan atas --}}
                <button
                    @click="open = false"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-800 dark:hover:text-white text-xl"
                >✖</button>

                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 text-center">
                    Scan QR Code
                </h3>

                <div class="relative">
                    <video id="preview" class="w-full aspect-video rounded-lg border border-gray-300 dark:border-gray-700"></video>
                    <div class="absolute top-1/4 left-1/4 w-1/2 h-1/2 border-4 border-green-500 rounded-lg pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>
</x-filament::page>

@push('scripts')
<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
    let scanner;
    let started = false;

    function startScanner() {
        const videoElement = document.getElementById("preview");
        if (!videoElement || started) return;

        if (!scanner) {
            scanner = new Instascan.Scanner({ video: videoElement, mirror: true });

            scanner.addListener("scan", (content) => {
                fetch("/scan-absen", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": @json(csrf_token())
                    },
                    body: JSON.stringify({ nisn: content.trim() })
                })
                .then(response => response.json())
                .then(data => {
                    alert(data.message);
                })
                .catch(error => {
                    console.error("Error:", error);
                });
            });
        }

        Instascan.Camera.getCameras()
            .then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]);
                    started = true;
                } else {
                    alert("Tidak ada kamera ditemukan.");
                }
            })
            .catch(function (e) {
                console.error(e);
                alert("Gagal mengakses kamera.");
            });
    }

    function stopScanner() {
        if (scanner && started) {
            scanner.stop();
            started = false;
        }
    }
</script>
@endpush
