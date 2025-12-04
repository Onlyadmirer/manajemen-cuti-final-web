<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ isset($title) ? $title . ' - ' : '' }}Manajemen Cuti</title>

  <!-- Favicon -->
  <link rel="icon" type="image/svg+xml"
    href="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'%3E%3Crect x='15' y='25' width='70' height='60' rx='5' fill='%233b82f6' opacity='0.2'/%3E%3Crect x='15' y='20' width='70' height='65' rx='5' fill='none' stroke='%233b82f6' stroke-width='3'/%3E%3Cline x1='35' y1='15' x2='35' y2='28' stroke='%233b82f6' stroke-width='3' stroke-linecap='round'/%3E%3Cline x1='65' y1='15' x2='65' y2='28' stroke='%233b82f6' stroke-width='3' stroke-linecap='round'/%3E%3Cline x1='20' y1='38' x2='80' y2='38' stroke='%233b82f6' stroke-width='2'/%3E%3Cpolyline points='35,55 45,65 65,45' fill='none' stroke='%233b82f6' stroke-width='4' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E">

  <!-- Fonts -->
  <link rel="preconnect" href="https://fonts.bunny.net">
  <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

  <!-- Scripts -->
  @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased">
  <div class="flex min-h-screen">
    <!-- Left Side - Image/Background -->
    <div
      class="relative items-center justify-center hidden p-12 overflow-hidden lg:flex lg:w-1/2 bg-gradient-to-br from-blue-600 via-blue-700 to-indigo-800">
      <div class="absolute inset-0 opacity-10">
        <svg class="w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
          <defs>
            <pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse">
              <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" />
            </pattern>
          </defs>
          <rect width="100" height="100" fill="url(#grid)" />
        </svg>
      </div>
      <div class="relative z-10 text-center text-white">
        <div class="flex justify-center mb-8">
          <div class="w-32 h-32 p-6 shadow-2xl bg-white/10 backdrop-blur-sm rounded-3xl">
            <x-application-logo class="w-full h-full text-white" />
          </div>
        </div>
        <h1 class="mb-4 text-5xl font-bold">Sistem Manajemen Cuti</h1>
        <p class="mb-8 text-xl text-blue-100">Kelola pengajuan cuti karyawan dengan mudah dan efisien</p>
        <div class="flex justify-center gap-8 text-left">
          <div class="p-4 bg-white/10 backdrop-blur-sm rounded-xl">
            <div class="flex items-center gap-3 mb-2">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="font-semibold">Pengajuan Mudah</span>
            </div>
          </div>
          <div class="p-4 bg-white/10 backdrop-blur-sm rounded-xl">
            <div class="flex items-center gap-3 mb-2">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
              <span class="font-semibold">Real-time Status</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Right Side - Form -->
    <div class="flex items-center justify-center w-full p-8 lg:w-1/2 bg-gray-50">
      <div class="w-full max-w-md">
        <!-- Logo for Mobile -->
        <div class="mb-8 text-center lg:hidden">
          <div class="inline-flex items-center gap-3 mb-4">
            <div class="w-16 h-16 p-3 bg-blue-600 shadow-lg rounded-2xl">
              <x-application-logo class="w-full h-full text-white" />
            </div>
            <div class="text-left">
              <h2 class="text-2xl font-bold text-gray-800">Manajemen Cuti</h2>
              <p class="text-sm text-gray-600">Sistem Informasi</p>
            </div>
          </div>
        </div>

        <div class="p-8 bg-white shadow-xl rounded-2xl">
          {{ $slot }}
        </div>
      </div>
    </div>
  </div>
</body>

</html>
