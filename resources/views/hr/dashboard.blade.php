<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard HRD') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="text-gray-500 text-sm font-bold uppercase">Total Pengajuan (Bulan Ini)</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $total_requests_month }}</div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <div class="text-gray-500 text-sm font-bold uppercase">Menunggu Approval Final</div>
                    <div class="text-3xl font-bold text-purple-700">{{ $pending_final_approval }}</div>
                    <p class="text-xs text-gray-400 mt-1">Pengajuan dari Manager atau yang sudah disetujui Manager</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4 text-gray-700">Sedang Cuti Hari Ini</h3>
                        @if($employees_on_leave->isEmpty())
                            <p class="text-gray-400 italic text-sm">Tidak ada karyawan yang sedang cuti.</p>
                        @else
                            <ul class="divide-y divide-gray-200">
                                @foreach($employees_on_leave as $leave)
                                <li class="py-3 flex justify-between">
                                    <span class="text-gray-800 font-medium">{{ $leave->user->name }}</span>
                                    <span class="text-xs bg-green-100 text-green-800 py-1 px-2 rounded-full">
                                        Sampai {{ $leave->end_date->format('d M') }}
                                    </span>
                                </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 border-b border-gray-200">
                        <h3 class="text-lg font-bold mb-4 text-gray-700">Daftar Divisi</h3>
                        <ul class="divide-y divide-gray-200">
                            @foreach($divisions as $div)
                            <li class="py-3">
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-800">{{ $div->name }}</span>
                                    <span class="text-xs text-gray-500">Manager: {{ $div->manager ? $div->manager->name : '-' }}</span>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>