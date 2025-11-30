<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Tambah User Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form action="{{ route('users.store') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Nama Lengkap</label>
                            <input type="text" name="name" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Username</label>
                                <input type="text" name="username" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Email</label>
                                <input type="email" name="email" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Password</label>
                                <input type="password" name="password" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-bold mb-2">Role</label>
                                <select name="role" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                                    <option value="employee">Employee (Karyawan)</option>
                                    <option value="division_manager">Division Manager (Ketua Divisi)</option>
                                    <option value="hr">HRD</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-bold mb-2">Divisi</label>
                                <select name="division_id" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                    <option value="">-- Tidak Ada / Admin --</option>
                                    @foreach($divisions as $div)
                                        <option value="{{ $div->id }}">{{ $div->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-bold mb-2">Kuota Cuti Tahunan</label>
                            <input type="number" name="annual_leave_quota" value="12" class="w-full rounded border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('users.index') }}" class="text-gray-400 hover:text-gray-200 underline mr-4">Batal</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Simpan User</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>