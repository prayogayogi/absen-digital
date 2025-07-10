<x-filament::page>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        {{-- Informasi Siswa --}}
        <div class="rounded-xl p-6 bg-white dark:bg-gray-900 shadow">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Informasi Student</h2>

            <div class="flex flex-col md:flex-row gap-6">
                {{-- Kolom Informasi --}}
                <div class="flex-1 space-y-4 text-sm">
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
                        <p class="text-gray-500 dark:text-gray-400">Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->birth_date }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Alamat Tempat Tanggal Lahir</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->birth_place }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Alamatr</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->address }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">No Handphone</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->phone }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Email</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->email }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Jenis Kelamin</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">
                            {{ $record->gender === 'L' ? 'Laki-Laki' : 'Perempuan' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">Nama Orang Tua</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->parent_name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-500 dark:text-gray-400">No Handphone Orang Tua</p>
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $record->parent_phone }}</p>
                    </div>
                </div>

                {{-- Kolom Foto --}}
                <div class="flex-shrink-0">
                    <div class="w-40 h-40 border-2 border-red-500 rounded overflow-hidden">
                        @if ($record->photo)
                            <img src="{{ asset('storage/' . $record->photo) }}" alt="Foto Siswa" width="100px" class="">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-gray-500 text-sm">
                                Tidak ada foto
                            </div>
                        @endif
                    </div>
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
