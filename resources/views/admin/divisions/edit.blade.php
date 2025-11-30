<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Divisi') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('divisions.update', $division->id) }}" method="POST">
                        @csrf
                        @method('PUT') <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Nama Divisi</label>
                            <input type="text" name="name" value="{{ old('name', $division->name) }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Deskripsi</label>
                            <textarea name="description" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3">{{ old('description', $division->description) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Ketua Divisi (Manager)</label>
                            <select name="manager_id" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                                <option value="">-- Pilih Manager --</option>
                                @foreach($availableManagers as $manager)
                                    <option value="{{ $manager->id }}" {{ $division->manager_id == $manager->id ? 'selected' : '' }}>
                                        {{ $manager->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('divisions.index') }}" class="text-gray-600 underline mr-4">Batal</a>
                            <button type="submit" class="bg-blue-600 text-gray-600 font-bold py-2 px-4 rounded hover:bg-blue-700 focus:outline-none focus:shadow-outline">
                                Perbarui Data
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>