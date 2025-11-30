<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Edit Hari Libur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('holidays.update', $holiday->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Tanggal Libur</label>
                            <input type="date" name="holiday_date" value="{{ $holiday->holiday_date->format('Y-m-d') }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Keterangan</label>
                            <input type="text" name="description" value="{{ $holiday->description }}" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('holidays.index') }}" class="text-gray-400 hover:text-gray-200 underline mr-4">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Update</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>