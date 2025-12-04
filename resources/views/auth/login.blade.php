<x-guest-layout>
  <div class="mb-6">
    <h2 class="mb-2 text-2xl font-bold text-gray-800">Selamat Datang!</h2>
    <p class="text-sm text-gray-600">Silakan login untuk melanjutkan ke sistem</p>
  </div>

  <!-- Session Status -->
  <x-auth-session-status class="mb-4" :status="session('status')" />

  <form method="POST" action="{{ route('login') }}" class="space-y-5">
    @csrf

    <!-- Email Address -->
    <div>
      <x-input-label for="email" value="Email / Username" class="font-semibold text-gray-700" />
      <x-text-input id="email"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="text" name="email" :value="old('email')" required autofocus autocomplete="username"
        placeholder="Masukkan email atau username" />
      <x-input-error :messages="$errors->get('email')" class="mt-2" />
    </div>

    <!-- Password -->
    <div>
      <x-input-label for="password" value="Password" class="font-semibold text-gray-700" />
      <x-text-input id="password"
        class="block w-full px-4 py-3 mt-2 text-gray-700 border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
        type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
      <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <!-- Remember Me -->
    <div class="flex items-center justify-between">
      <label for="remember_me" class="inline-flex items-center cursor-pointer">
        <input id="remember_me" type="checkbox"
          class="text-blue-600 border-gray-300 rounded shadow-sm focus:ring-blue-500" name="remember">
        <span class="ml-2 text-sm text-gray-600">Ingat saya</span>
      </label>

      @if (Route::has('password.request'))
        <a class="text-sm font-medium text-blue-600 hover:text-blue-800" href="{{ route('password.request') }}">
          Lupa password?
        </a>
      @endif
    </div>

    <div class="pt-2">
      <button type="submit"
        class="w-full px-4 py-3 font-semibold text-white transition duration-200 rounded-lg shadow-lg bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 hover:shadow-xl">
        Masuk
      </button>
    </div>

    <div class="pt-4 text-center border-t border-gray-200">
      <p class="text-sm text-gray-600">
        Belum punya akun?
        @if (Route::has('register'))
          <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-800">Daftar sekarang</a>
        @endif
      </p>
    </div>
  </form>

  {{-- <!-- Demo Accounts Info -->
  <div class="p-4 mt-6 border border-blue-200 rounded-lg bg-blue-50">
    <p class="mb-2 text-xs font-semibold text-blue-800">Demo Akun:</p>
    <div class="space-y-1 text-xs text-blue-700">
      <p><strong>Admin:</strong> admin / password</p>
      <p><strong>HRD:</strong> hrd / password</p>
      <p><strong>Manager:</strong> manager_it / password</p>
      <p><strong>Employee:</strong> andi / password</p>
    </div>
  </div> --}}
</x-guest-layout>
