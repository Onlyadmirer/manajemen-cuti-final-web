<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Manajemen Hari Libur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="flex justify-end mb-4">
                <a href="{{ route('holidays.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded shadow hover:bg-blue-700">
                    + Tambah Hari Libur
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Keterangan</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($holidays as $holiday)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-bold">{{ $holiday->holiday_date->format('d M Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $holiday->holiday_date->format('l') }}</div>
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    {{ $holiday->description }}
                                </td>
                                <td class="px-6 py-4 text-sm font-medium">
                                    <a href="{{ route('holidays.edit', $holiday->id) }}" class="text-indigo-600 dark:text-indigo-400 hover:underline mr-3">Edit</a>
                                    <form action="{{ route('holidays.destroy', $holiday->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Hapus tanggal ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:underline">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-4">{{ $holidays->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>