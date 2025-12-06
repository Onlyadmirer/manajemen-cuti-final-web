<x-app-layout>
  <x-slot name="header">
    <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
      {{ __('Persetujuan Cuti') }}
    </h2>
  </x-slot>

  <div class="py-12">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

      @if (session('success'))
      @endif

      @if ($approvals->isEmpty())
        <div
          class="flex flex-col items-center justify-center p-12 text-center bg-white shadow-sm dark:bg-gray-800 rounded-xl">
          <div class="p-4 mb-4 bg-green-100 rounded-full dark:bg-green-900/30">
            <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-lg font-bold text-gray-900 dark:text-white">Semua Bersih!</h3>
          <p class="text-gray-500 dark:text-gray-400">Tidak ada pengajuan cuti yang perlu diproses saat ini.</p>
        </div>
      @else
        <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
          @foreach ($approvals as $leave)
            <div
              class="flex flex-col overflow-hidden transition duration-300 bg-white border border-gray-100 shadow-lg dark:bg-gray-800 rounded-2xl hover:shadow-xl dark:border-gray-700"
              x-data="{ showReject: false }">

              <div class="flex items-start justify-between p-6 pb-0">
                <div class="flex items-center space-x-3">
                  <div
                    class="flex items-center justify-center w-10 h-10 text-sm font-bold text-white rounded-full shadow-md bg-gradient-to-br from-blue-500 to-indigo-600">
                    {{ substr($leave->user->name, 0, 2) }}
                  </div>
                  <div>
                    <h4 class="text-sm font-bold text-gray-900 dark:text-white line-clamp-1">{{ $leave->user->name }}
                    </h4>
                    <p class="text-xs text-gray-500 dark:text-gray-400">
                      {{ $leave->user->division->name ?? 'Tanpa Divisi' }}</p>
                  </div>
                </div>
                <span class="px-2 py-1 font-mono text-xs text-gray-400 bg-gray-100 rounded dark:bg-gray-700">
                  {{ $leave->created_at->diffForHumans(null, true) }}
                </span>
              </div>

              <div class="flex-grow p-6">
                <div class="flex items-center justify-between mb-4">
                  <div
                    class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wide {{ $leave->leave_type == 'annual' ? 'bg-blue-100 text-blue-700 dark:bg-blue-900/50 dark:text-blue-300' : 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/50 dark:text-yellow-300' }}">
                    {{ $leave->leave_type == 'annual' ? 'Tahunan' : 'Sakit' }}
                  </div>
                  <div class="text-sm font-bold text-gray-800 dark:text-gray-200">
                    {{ $leave->total_days }} Hari
                  </div>
                </div>

                <div class="mb-4 space-y-2">
                  <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                      </path>
                    </svg>
                    {{ $leave->start_date->format('d M') }} - {{ $leave->end_date->format('d M Y') }}
                  </div>
                  <div
                    class="flex items-start p-3 text-sm italic text-gray-600 border border-gray-100 rounded-lg dark:text-gray-300 bg-gray-50 dark:bg-gray-700/50 dark:border-gray-700">
                    <svg class="w-4 h-4 mr-2 text-gray-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor"
                      viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z">
                      </path>
                    </svg>
                    "{{ $leave->reason }}"
                  </div>
                </div>

                @if ($leave->attachment_path)
                  <a href="{{ route('approvals.attachment', $leave->id) }}" target="_blank"
                    class="block w-full mb-2 text-xs font-bold text-center text-indigo-600 dark:text-indigo-400 hover:underline">
                    📎 Lihat Lampiran (Dokter)
                  </a>
                @endif

                <div x-show="showReject" x-transition class="pt-4 mt-4 border-t border-gray-100 dark:border-gray-700">
                  <form action="{{ route('approvals.reject', $leave->id) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <textarea name="rejection_reason" rows="2"
                      class="w-full mb-2 text-sm border-gray-300 rounded-lg dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:ring-red-500 focus:border-red-500"
                      placeholder="Alasan penolakan..." required></textarea>
                    <div class="flex justify-end space-x-2">
                      <button type="button" @click="showReject = false"
                        class="text-xs text-gray-500 hover:text-gray-700 dark:text-gray-400">Batal</button>
                      <button type="submit"
                        class="bg-red-600 text-white text-xs px-3 py-1.5 rounded hover:bg-red-700 font-bold">Kirim
                        Tolak</button>
                    </div>
                  </form>
                </div>
              </div>

              <div
                class="flex items-center justify-between px-6 py-4 border-t border-gray-100 bg-gray-50 dark:bg-gray-700/30 dark:border-gray-700"
                x-show="!showReject">
                <button @click="showReject = true"
                  class="px-4 py-2 text-sm font-bold text-red-600 transition rounded-lg dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                  Tolak
                </button>

                <form action="{{ route('approvals.approve', $leave->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-bold px-6 py-2 rounded-lg shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5">
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
