<!DOCTYPE html>
<html lang="id">
<head>@include('partials.head')</head>
<body class="min-h-screen bg-cream-50 font-sans text-ink">
<div class="grid min-h-screen lg:grid-cols-2">

  {{-- Brand panel --}}
  <div class="relative hidden overflow-hidden bg-maroon lg:block">
    <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-burgundy/40 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-burgundy-700/40 blur-3xl"></div>
    <div class="relative flex h-full flex-col justify-between p-12">
      <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-maroon font-display text-xl font-semibold">LH</span>
        <span class="font-display text-2xl font-semibold text-cream">LibHub</span>
      </a>
      <div>
        <h2 class="font-display text-4xl font-semibold leading-tight text-cream">Selamat datang kembali.</h2>
        <p class="mt-4 max-w-sm text-cream/70">Masuk untuk melihat katalog, mengelola booking, dan memantau riwayat peminjamanmu.</p>
      </div>
      <p class="text-sm text-cream/40">Sistem Informasi Peminjaman Buku Perpustakaan</p>
    </div>
  </div>

  {{-- Form --}}
  <div class="flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">
      <a href="{{ route('landing') }}" class="mb-8 flex items-center gap-2 lg:hidden">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-burgundy text-cream font-display font-semibold">P</span>
        <span class="font-display text-xl font-semibold text-maroon">Pustaka</span>
      </a>

      <h1 class="font-display text-3xl font-semibold text-maroon">Masuk</h1>
      <p class="mt-2 text-sm text-ink/60">Belum punya akun?
        <a href="{{ route('register') }}" class="font-semibold text-burgundy hover:underline">Daftar di sini</a>
      </p>

      @include('partials.flash')

      <a href="{{ route('google.redirect') }}"
         class="mt-6 flex w-full items-center justify-center gap-3 rounded-xl border border-maroon/15 bg-white px-4 py-3 text-sm font-semibold text-maroon shadow-sm hover:bg-cream-100 transition">
        <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1Z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23Z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.06H2.18a11 11 0 0 0 0 9.88l3.66-2.84Z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84C6.71 7.3 9.14 5.38 12 5.38Z"/></svg>
        Masuk dengan Google
      </a>

      <div class="my-6 flex items-center gap-3 text-xs text-ink/40">
        <span class="h-px flex-1 bg-sand"></span> atau email <span class="h-px flex-1 bg-sand"></span>
      </div>

      <form method="POST" action="{{ route('login') }}" class="space-y-4">
        @csrf
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required autofocus
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <div class="mb-1.5 flex items-center justify-between">
            <label class="block text-sm font-medium text-maroon">Kata sandi</label>
            <a href="{{ route('password.request') }}" class="text-xs font-semibold text-burgundy hover:underline">Lupa kata sandi?</a>
          </div>
          <input type="password" name="password" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        </div>
        <label class="flex items-center gap-2 text-sm text-ink/60">
          <input type="checkbox" name="remember" class="rounded border-maroon/20 text-burgundy focus:ring-burgundy/30"> Ingat saya
        </label>
        <button class="w-full rounded-xl bg-burgundy px-4 py-3 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
          Masuk
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
