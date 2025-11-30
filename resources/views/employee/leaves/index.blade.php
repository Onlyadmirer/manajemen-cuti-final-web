<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Riwayat Pengajuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="flex justify-between items-center mb-6">
                <div class="bg-white dark:bg-gray-800 border-l-4 border-blue-500 p-4 rounded shadow-sm">
                    <p class="font-bold text-gray-800 dark:text-white">
                        Sisa Kuota Cuti Tahunan: <span class="text-blue-600 text-xl">{{ Auth::user()->annual_leave_quota }}</span> Hari
                    </p>
                </div>
                
                <a href="{{ route('leaves.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded shadow hover:bg-indigo-700 transition">
                    + Ajukan Cuti Baru
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100 border-b border-gray-200 dark:border-gray-700 overflow-x-auto">
                    
                    @if($requests->isEmpty())
                        <div class="text-center py-10 text-gray-500">
                            Belum ada riwayat pengajuan cuti.
                        </div>
                    @else
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-700">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Jenis</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Periode</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Durasi</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($requests as $leave)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-500 dark:text-gray-300 align-top">
                                        {{ $leave->created_at->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 align-top">
                                        @if($leave->leave_type == 'annual')
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Tahunan</span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Sakit</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-100 align-top">
                                        {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm font-bold text-gray-700 dark:text-gray-200 align-top">
                                        {{ $leave->total_days }} Hari Kerja
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap align-top">
                                        @php
                                            $statusClass = match($leave->status) {
                                                'pending' => 'bg-gray-200 text-gray-800',
                                                'approved_by_leader' => 'bg-indigo-100 text-indigo-800',
                                                'approved' => 'bg-green-100 text-green-800',
                                                'rejected' => 'bg-red-100 text-red-800',
                                                'cancelled' => 'bg-black text-white',
                                                default => 'bg-gray-100 text-gray-800'
                                            };
                                            $statusText = match($leave->status) {
                                                'pending' => 'Menunggu Verifikasi',
                                                'approved_by_leader' => 'Disetujui Manager',
                                                'approved' => 'Disetujui HRD (Final)',
                                                'rejected' => 'Ditolak',
                                                'cancelled' => 'Dibatalkan',
                                                default => $leave->status
                                            };
                                        @endphp
                                        
                                        <div class="mb-2">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                                {{ $statusText }}
                                            </span>
                                        </div>

                                        @if($leave->status == 'rejected' && $leave->rejection_reason)
                                            <div class="p-2 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded text-xs text-red-600 dark:text-red-300 italic max-w-xs whitespace-normal">
                                                <strong>Catatan:</strong> "{{ $leave->rejection_reason }}"
                                            </div>
                                        @endif
                                    </td>
                                    
                                    <td class="px-6 py-4 text-sm font-medium align-top">
                                        @if($leave->status == 'pending')
                                            <form action="{{ route('leaves.destroy', $leave->id) }}" method="POST" onsubmit="return confirm('Yakin ingin membatalkan cuti ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:underline font-bold">Batalkan</button>
                                            </form>

                                        @elseif($leave->status == 'approved')
                                            <a href="{{ route('leaves.download_pdf', $leave->id) }}" target="_blank" class="inline-flex items-center px-3 py-1 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                                PDF
                                            </a>
                                        @else
                                            <span class="text-gray-400 text-xs">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="mt-4">
                            {{ $requests->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>