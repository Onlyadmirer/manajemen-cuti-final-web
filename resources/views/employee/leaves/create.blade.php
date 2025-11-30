<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Buat Pengajuan Cuti Baru') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <form action="{{ route('leaves.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Jenis Cuti</label>
                            <select name="leave_type" id="leave_type" class="shadow border rounded w-full py-2 px-3 text-gray-700" required onchange="toggleAttachment()">
                                <option value="annual">Cuti Tahunan (Min H-3)</option>
                                <option value="sick">Cuti Sakit (Wajib Surat Dokter)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-1">Kuota Tahunan Anda: <b>{{ Auth::user()->annual_leave_quota }} Hari</b></p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Mulai</label>
                                <input type="date" name="start_date" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @error('start_date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Tanggal Selesai</label>
                                <input type="date" name="end_date" class="shadow border rounded w-full py-2 px-3 text-gray-700" required>
                                @error('end_date') <span class="text-red-500 text-xs font-bold">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Alasan Cuti</label>
                            <textarea name="reason" class="shadow border rounded w-full py-2 px-3 text-gray-700" rows="3" required placeholder="Contoh: Acara keluarga / Demam tinggi"></textarea>
                        </div>

                        <div class="mb-4 hidden" id="attachment_container">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Surat Keterangan Dokter (PDF/JPG)</label>
                            <input type="file" name="attachment" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            <p class="text-xs text-red-500 mt-1">*Wajib diisi jika memilih Cuti Sakit.</p>
                            @error('attachment') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Alamat Selama Cuti</label>
                                <input type="text" name="address_during_leave" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                            <div>
                                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Darurat (HP)</label>
                                <input type="text" name="emergency_contact" class="shadow border rounded w-full py-2 px-3 text-gray-700">
                            </div>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('leaves.index') }}" class="text-gray-600 underline mr-4">Kembali</a>
                            <button type="submit" class="bg-blue-600 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleAttachment() {
            var type = document.getElementById("leave_type").value;
            var container = document.getElementById("attachment_container");
            if (type === "sick") {
                container.classList.remove("hidden");
            } else {
                container.classList.add("hidden");
            }
        }
    </script>
</x-app-layout>