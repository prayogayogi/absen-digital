<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Informasi Siswa --}}
        <div class="rounded-xl p-6 bg-white dark:bg-gray-900 shadow">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Student</h2>

            <div class="space-y-4 text-sm">
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Kelas</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->class->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">NIS</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->nisn }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Nama Lengkap</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Nama Orang Tua</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->parent_name }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">No Hanphone Orang Tua</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->parent_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                    <p class="font-semibold text-gray-800 dark:text-gray-100">{{ ucfirst($record->gender) }}</p>
                </div>
            </div>
        </div>

        {{-- QR Code --}}
        <div class="rounded-xl p-6 bg-white dark:bg-gray-900 shadow flex items-center justify-center">
            <div class="text-center">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">QR Code Student</h2>
                <img src="{{ $qrCodeDataUri }}" alt="QR Code"
                    class="w-40 h-40 mx-auto border border-gray-300 dark:border-gray-700 rounded-lg shadow-sm" />
            </div>
        </div>

    </div>
</x-filament::page>
