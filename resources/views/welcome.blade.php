<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <style>
            body { font-family: 'Poppins', sans-serif !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="antialiased bg-gray-50 text-gray-800">
        
        <div class="relative flex items-top justify-center min-h-screen bg-gray-50 sm:items-center py-4 sm:pt-0">
            @if (Route::has('login'))
                <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block z-50">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-sm text-gray-700 font-bold hover:text-blue-600 underline decoration-2 underline-offset-4">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm text-gray-700 font-bold hover:text-blue-600 underline decoration-2 underline-offset-4 mr-4">Log in</a>
                    @endauth
                </div>
            @endif

            <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 w-full">
                
                <div class="flex flex-col items-center justify-center text-center mt-10 md:mt-20">
                    
                    <div class="w-full max-w-3xl">
                        <div class="flex justify-center mb-6">
                            <div class="p-4 bg-blue-600 rounded-2xl shadow-xl transform rotate-3 hover:rotate-0 transition duration-300">
                                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            </div>
                        </div>
                        
                        <h1 class="text-4xl md:text-6xl font-extrabold text-gray-900 leading-tight mb-6">
                            Sistem Manajemen <br>
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Cuti Pegawai</span>
                        </h1>
                        
                        <p class="text-xl text-gray-600 mb-10 leading-relaxed">
                            Platform terintegrasi untuk pengelolaan cuti, persetujuan berjenjang, dan administrasi kepegawaian yang efisien, transparan, dan tanpa kertas.
                        </p>
                        
                        <div class="flex flex-col md:flex-row gap-4 justify-center">
                            @auth
                                <a href="{{ url('/dashboard') }}" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-1 text-lg">
                                    Masuk ke Dashboard
                                </a>
                            @else
                                <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 text-white font-bold rounded-full shadow-lg hover:bg-blue-700 transition transform hover:-translate-y-1 text-lg">
                                    Login / Masuk
                                </a>
                            @endauth
                        </div>
                    </div>

                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mt-24 mb-16">
                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-blue-100 rounded-full flex items-center justify-center mb-6 text-blue-600 mx-auto">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Real-time Approval</h3>
                        <p class="text-gray-500">Proses pengajuan dan persetujuan cuti dilakukan secara instan dan transparan.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-indigo-100 rounded-full flex items-center justify-center mb-6 text-indigo-600 mx-auto">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Manajemen Terpusat</h3>
                        <p class="text-gray-500">Kontrol penuh bagi Admin & HRD untuk mengelola data karyawan, divisi, dan kuota.</p>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm hover:shadow-xl transition border border-gray-100 text-center">
                        <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mb-6 text-green-600 mx-auto">
                            <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold mb-3">Digital Document</h3>
                        <p class="text-gray-500">Generate surat izin cuti resmi dalam format PDF secara otomatis.</p>
                    </div>
                </div>

                <div class="text-center text-gray-400 text-sm pb-8 border-t border-gray-200 pt-8">
                    &copy; {{ date('Y') }} PT. Maju Mundur Sejahtera. All rights reserved. <br>
                    Developed for Individual Project 8.
                </div>

            </div>
        </div>
    </body>
</html>