<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Persetujuan Cuti') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('success'))
                @endif

            @if($approvals->isEmpty())
                <div class="flex flex-col items-center justify-center bg-white dark:bg-gray-800 rounded-xl shadow-sm p-12 text-center">
                    <div class="bg-green-100 dark:bg-green-900/30 p-4 rounded-full mb-4">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Semua Bersih!</h3>
                    <p class="text-gray-500 dark:text-gray-400">Tidak ada pengajuan cuti yang perlu diproses saat ini.</p>
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($approvals as $leave)
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 border border-gray-100 dark:border-gray-700 overflow-hidden flex flex-col" x-data="{ showReject: false }">
                        
                        <div class="p-6 pb-0 flex items-start justify-between">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-500 to-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-md">
                                    {{ substr($leave->user->name, 0, 2) }}
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ $leave->user->name }}</h4>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $leave->user->division->name ?? 'Tanpa Divisi' }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-mono text-gray-400 bg-gray-100 dark:bg-gray-700 px-2 py-1 rounded">
                                {{ $leave->created_at->diffForHumans(null, true) }}
                            </span>
                        </div>

                        <div class="p-6 flex-grow">
                            <div class="flex items-center justify-between mb-4">
                                <div class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $leave->leave_type == 'annual' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                                    {{ $leave->leave_type == 'annual' ? 'Tahunan' : 'Sakit' }}
                                </div>
                                <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                                    {{ $leave->total_days }} Hari
                                </div>
                            </div>
                            
                            <div class="space-y-2 mb-4">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                                </div>
                                <div class="flex items-start text-sm text-gray-600 dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 p-3 rounded-lg italic border border-gray-100 dark:border-gray-700">
                                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                    "{{ $leave->reason }}"
                                </div>
                            </div>

                            @if($leave->attachment_path)
                                <a href="{{ asset('storage/' . $leave->attachment_path) }}" target="_blank" class="block w-full text-center text-xs font-bold text-indigo-600 dark:text-indigo-400 hover:underline mb-2">
                                    📎 Lihat Lampiran (Dokter)
                                </a>
                            @endif

                            <div x-show="showReject" x-transition class="mt-4 pt-4 border-t border-gray-100 dark:border-gray-700">
                                <form action="{{ route('approvals.reject', $leave->id) }}" method="POST">
                                    @csrf
                                    @method('PATCH')
                                    <textarea name="rejection_reason" rows="2" class="w-full text-sm rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500 mb-2" placeholder="Alasan penolakan..." required></textarea>
                                    <div class="flex justify-end space-x-2">
                                        <button type="button" @click="showReject = false" class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400">Batal</button>
                                        <button type="submit" class="bg-red-600 text-white text-xs px-3 py-1.5 rounded hover:bg-red-700 font-bold">Kirim Tolak</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="bg-gray-50 dark:bg-gray-700/30 px-6 py-4 border-t border-gray-100 dark:border-gray-700 flex justify-between items-center" x-show="!showReject">
                            <button @click="showReject = true" class="text-red-600 dark:text-red-400 text-sm font-bold hover:bg-red-50 dark:hover:bg-red-900/20 px-4 py-2 rounded-lg transition">
                                Tolak
                            </button>
                            
                            <form action="{{ route('approvals.approve', $leave->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
                                    Setujui
                                </button>
                            </form>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</x-app-layout>