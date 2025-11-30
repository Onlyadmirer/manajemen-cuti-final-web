<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Manager') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-indigo-600 rounded-lg shadow-lg p-6 mb-6 text-white">
                <h3 class="text-lg font-bold">Divisi: {{ $division_name }}</h3>
                <p class="text-indigo-200 text-sm">Kelola persetujuan cuti anggota tim Anda di sini.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-gray-500 text-xs font-bold uppercase">Total Pengajuan Masuk</div>
                    <div class="text-2xl font-bold">{{ $total_incoming_requests }}</div>
                </div>
                
                <div class="bg-white p-6 rounded-lg shadow-sm border-l-4 border-orange-500">
                    <div class="text-gray-500 text-xs font-bold uppercase">Menunggu Verifikasi Anda</div>
                    <div class="text-2xl font-bold text-orange-600">{{ $pending_verification }}</div>
                    <p class="text-xs text-gray-400 mt-1">Segera proses agar bisa lanjut ke HRD</p>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-bold mb-4 text-gray-800">Anggota Tim Saya</h3>
                    @if($division_members->isEmpty())
                        <p class="text-gray-500 italic">Belum ada anggota di divisi ini.</p>
                    @else
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm text-left text-gray-500">
                                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3">Nama</th>
                                        <th class="px-6 py-3">Email</th>
                                        <th class="px-6 py-3">Bergabung Sejak</th>
                                        <th class="px-6 py-3">Sisa Kuota</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($division_members as $member)
                                    <tr class="bg-white border-b hover:bg-gray-50">
                                        <td class="px-6 py-4 font-medium text-gray-900">{{ $member->name }}</td>
                                        <td class="px-6 py-4">{{ $member->email }}</td>
                                        <td class="px-6 py-4">{{ \Carbon\Carbon::parse($member->join_date)->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                                {{ $member->annual_leave_quota }} Hari
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