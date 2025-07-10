<x-filament-panels::page>
    <div class="space-y-4">
        <h2 class="text-xl font-bold text-gray-800 dark:text-gray-100">Rekapitulasi Kehadiran</h2>

        <div class="overflow-x-auto rounded-xl shadow-sm">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-100 dark:bg-gray-800">
                    <tr>
                        <th class="px-4 py-3 text-left text-sm font-medium text-gray-700 dark:text-gray-300">Tanggal</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Total Siswa</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Total Hadir</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Total Sakit</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Total Izin</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Total Alpha</th>
                        <th class="px-4 py-3 text-center text-sm font-medium text-gray-700 dark:text-gray-300">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                    @foreach ($attendances as $attendance)
                        <tr class="bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-800">
                            <td class="px-4 py-3 text-sm text-gray-800 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($attendance->date)->translatedFormat('d F Y') }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 dark:text-gray-100">
                                {{ $attendance->total_students }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 dark:text-gray-100">
                                {{ $attendance->present }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-red-600 dark:text-red-400">
                                {{ $attendance->sick }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-yellow-600 dark:text-yellow-400">
                                {{ $attendance->permission }}
                            </td>
                            <td class="px-4 py-3 text-center text-sm text-gray-800 dark:text-gray-100">
                                {{ $attendance->alpha }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href=""
                                    class="inline-flex items-center px-4 py-2 text-sm font-semibold text-white bg-emerald-700 hover:bg-emerald-800 rounded-md transition">
                                    <x-heroicon-o-document-text class="w-4 h-4 mr-2" />
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</x-filament-panels::page>
