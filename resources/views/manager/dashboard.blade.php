<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800">
      {{ __('Dashboard Manager') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

      <div class="p-6 mb-6 text-white bg-indigo-600 rounded-lg shadow-lg">
        <h3 class="text-lg font-bold">Divisi: {{ $division_name }}</h3>
        <p class="text-sm text-indigo-200">Kelola persetujuan cuti anggota tim Anda di sini.</p>
      </div>

      <div class="grid grid-cols-1 gap-6 mb-8 md:grid-cols-2">
        <div class="p-6 bg-white rounded-lg shadow-sm">
          <div class="text-xs font-bold text-gray-500 uppercase">Total Pengajuan Masuk</div>
          <div class="pt-4 text-2xl font-bold text-gray-800">{{ $total_incoming_requests }}</div>
        </div>

        <div class="p-6 bg-white border-l-4 border-orange-500 rounded-lg shadow-sm">
          <div class="text-xs font-bold text-gray-500 uppercase">Menunggu Verifikasi Anda</div>
          <div class="text-2xl font-bold text-orange-600">{{ $pending_verification }}</div>
          <p class="mt-1 text-xs text-gray-400">Segera proses agar bisa lanjut ke HRD</p>
        </div>
      </div>

      <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
        <div class="p-6">
          <h3 class="mb-4 text-lg font-bold text-gray-800">Anggota Tim Saya</h3>
          @if ($division_members->isEmpty())
            <p class="italic text-gray-500">Belum ada anggota di divisi ini.</p>
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
                  @foreach ($division_members as $member)
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
