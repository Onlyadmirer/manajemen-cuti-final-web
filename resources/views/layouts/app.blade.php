<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ isset($title) ? $title . ' - ' : '' }}Manajemen Cuti</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/svg+xml" href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect x='15' y='25' width='70' height='60' rx='5' fill='%233b82f6' opacity='0.2'/%3E%3Crect x='15' y='20' width='70' height='65' rx='5' fill='none' stroke='%233b82f6' stroke-width='3'/%3E%3Cline x1='35' y1='15' x2='35' y2='28' stroke='%233b82f6' stroke-width='3' stroke-linecap='round'/%3E%3Cline x1='65' y1='15' x2='65' y2='28' stroke='%233b82f6' stroke-width='3' stroke-linecap='round'/%3E%3Cline x1='20' y1='38' x2='80' y2='38' stroke='%233b82f6' stroke-width='2'/%3E%3Cpolyline points='35,55 45,65 65,45' fill='none' stroke='%233b82f6' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Poppins', sans-serif !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
            @include('layouts.navigation')

            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <main>
                {{ $slot }}
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script>
            const sessionSuccess = @json(session('success'));
            const sessionError   = @json(session('error'));
            if (sessionSuccess) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: sessionSuccess,
                    timer: 3000,
                    showConfirmButton: false,
                    background: '#fff',
                    iconColor: '#10B981'
                });
            }

            if (sessionError) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: sessionError,
                    confirmButtonColor: '#EF4444'
                });
            }

            // Fungsi Global Konfirmasi Hapus
            window.confirmDelete = function(formId) {
                Swal.fire({
                    title: 'Apakah Anda Yakin?',
                    text: "Data yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, Hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        if (typeof formId === 'string') {
                            document.getElementById(formId).submit();
                        } else {
                            formId.closest('form').submit();
                        }
                    }
                });
            };
        </script>
    </body>
</html>