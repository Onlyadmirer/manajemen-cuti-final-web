<x-guest-layout>
  <div class="mb-6">
    <h2 class="mb-2 text-2xl font-bold text-gray-800">Daftar Akun Baru</h2>
    <p class="text-sm text-gray-600">Lengkapi form di bawah untuk membuat akun</p>
  </div>

  <form method="POST" action="{{ route('register') }}" class="space-y-5">
    @csrf

    <!-- Name -->
    <div>
      <x-input-label for="name" value="Nama Lengkap" class="font-semibold text-gray-700" />
      <x-text-input id="name"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
        placeholder="Masukkan nama lengkap" />
      <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Email Address -->
    <div>
      <x-input-label for="email" value="Email" class="font-semibold text-gray-700" />
      <x-text-input id="email"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="email" name="email" :value="old('email')" required autocomplete="username"
        placeholder="contoh@email.com" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div>
      <x-input-label for="password" value="Password" class="font-semibold text-gray-700" />
      <x-text-input id="password"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Confirm Password -->
    <div>
      <x-input-label for="password_confirmation" value="Konfirmasi Password" class="font-semibold text-gray-700" />
      <x-text-input id="password_confirmation"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="password" name="password_confirmation" required autocomplete="new-password"
        placeholder="Ulangi password" />
      <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
    </div>

    <div class="pt-2">
      <button type="submit"
        class="w-full px-4 py-3 font-semibold text-white transition duration-200 rounded-lg shadow-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl">
        Daftar Sekarang
      </button>
    </div>

    <div class="pt-4 text-center border-t border-gray-200">
      <p class="text-sm text-gray-600">
        Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-800">Login di sini</a>
      </p>
    </div>
  </form>
</x-guest-layout>
