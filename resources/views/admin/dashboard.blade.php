<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-gradient-to-r from-blue-600 to-indigo-700 rounded-2xl shadow-lg p-8 text-white mb-8 relative overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-3xl font-bold mb-2">Selamat Datang, Administrator! 👋</h3>
                    <p class="opacity-90 text-blue-100">Berikut adalah ringkasan aktivitas dan statistik kepegawaian hari ini.</p>
                </div>
                <div class="absolute top-0 right-0 -mt-10 -mr-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
                <div class="absolute bottom-0 left-0 -mb-10 -ml-10 w-40 h-40 bg-white opacity-10 rounded-full blur-2xl"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-b-4 border-blue-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-blue-100 dark:bg-blue-900/50 p-3 rounded-lg text-blue-600 dark:text-blue-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Total User</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $total_employees }}</div>
                    <p class="text-sm text-gray-500 mt-1">Karyawan Aktif</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-b-4 border-indigo-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-indigo-100 dark:bg-indigo-900/50 p-3 rounded-lg text-indigo-600 dark:text-indigo-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Divisi</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $total_divisions }}</div>
                    <p class="text-sm text-gray-500 mt-1">Departemen Terdaftar</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-b-4 border-green-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-green-100 dark:bg-green-900/50 p-3 rounded-lg text-green-600 dark:text-green-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Bulan Ini</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $total_leave_requests_month }}</div>
                    <p class="text-sm text-gray-500 mt-1">Pengajuan Masuk</p>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-md p-6 border-b-4 border-yellow-500 hover:shadow-lg transition duration-300">
                    <div class="flex items-center justify-between mb-4">
                        <div class="bg-yellow-100 dark:bg-yellow-900/50 p-3 rounded-lg text-yellow-600 dark:text-yellow-400">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                        </div>
                        <span class="text-xs font-semibold uppercase tracking-wider text-gray-400">Pending</span>
                    </div>
                    <div class="text-3xl font-bold text-gray-800 dark:text-white">{{ $pending_approvals }}</div>
                    <p class="text-sm text-gray-500 mt-1">Perlu Tindakan</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg rounded-xl border border-gray-100 dark:border-gray-700">
                <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center bg-gray-50 dark:bg-gray-800">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 dark:text-white">Karyawan Baru</h3>
                        <p class="text-xs text-gray-500">Masa kerja kurang dari 1 tahun (Belum eligible cuti tahunan)</p>
                    </div>
                    <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold">Auto-Detected</span>
                </div>
                
                <div class="p-0">
                    @if($new_employees->isEmpty())
                        <div class="flex flex-col items-center justify-center py-10">
                            <svg class="w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-gray-500 dark:text-gray-400 font-medium">Semua karyawan sudah eligible cuti.</p>
                        </div>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-left">
                                <thead class="bg-gray-50 dark:bg-gray-700 text-gray-500 dark:text-gray-300 uppercase text-xs tracking-wider">
                                    <tr>
                                        <th class="px-6 py-4 font-medium">Nama Karyawan</th>
                                        <th class="px-6 py-4 font-medium">Tanggal Bergabung</th>
                                        <th class="px-6 py-4 font-medium">Status</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                                    @foreach($new_employees as $emp)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition duration-150">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="h-9 w-9 rounded-full bg-gradient-to-tr from-purple-500 to-pink-500 flex items-center justify-center text-white font-bold text-xs mr-3 shadow-sm">
                                                    {{ substr($emp->name, 0, 2) }}
                                                </div>
                                                <div>
                                                    <div class="font-bold text-gray-800 dark:text-white">{{ $emp->name }}</div>
                                                    <div class="text-xs text-gray-500">{{ $emp->email }}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-300">
                                            {{ \Carbon\Carbon::parse($emp->join_date)->translatedFormat('d F Y') }}
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                                Belum Eligible
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>