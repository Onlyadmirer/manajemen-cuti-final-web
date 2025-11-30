<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

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