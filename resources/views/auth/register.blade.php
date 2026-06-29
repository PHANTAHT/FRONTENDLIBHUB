<!DOCTYPE html>
<html lang="id">
<head>@include('partials.head')</head>
<body class="min-h-screen bg-cream-50 font-sans text-ink">
<div class="grid min-h-screen lg:grid-cols-2">

  <div class="relative hidden overflow-hidden bg-maroon lg:block">
    <div class="absolute -right-24 -top-24 h-96 w-96 rounded-full bg-burgundy/40 blur-3xl"></div>
    <div class="absolute -bottom-32 -left-24 h-96 w-96 rounded-full bg-burgundy-700/40 blur-3xl"></div>
    <div class="relative flex h-full flex-col justify-between p-12">
      <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-maroon font-display text-xl font-semibold">LH</span>
        <span class="font-display text-2xl font-semibold text-cream">LibHub</span>
      </a>
      <div>
        <h2 class="font-display text-4xl font-semibold leading-tight text-cream">Jadi anggota,<br>mulai meminjam.</h2>
        <p class="mt-4 max-w-sm text-cream/70">Gratis. Cukup isi data diri dan kamu langsung bisa booking buku dari katalog.</p>
      </div>
      <p class="text-sm text-cream/40">Sistem Informasi Peminjaman Buku Perpustakaan</p>
    </div>
  </div>

  <div class="flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">
      <a href="{{ route('landing') }}" class="mb-8 flex items-center gap-2 lg:hidden">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-burgundy text-cream font-display font-semibold">P</span>
        <span class="font-display text-xl font-semibold text-maroon">Pustaka</span>
      </a>

      <h1 class="font-display text-3xl font-semibold text-maroon">Daftar Anggota</h1>
      <p class="mt-2 text-sm text-ink/60">Sudah punya akun?
        <a href="{{ route('login') }}" class="font-semibold text-burgundy hover:underline">Masuk</a>
      </p>

      @include('partials.flash')

      <a href="{{ route('google.redirect') }}"
         class="mt-6 flex w-full items-center justify-center gap-3 rounded-xl border border-maroon/15 bg-white px-4 py-3 text-sm font-semibold text-maroon shadow-sm hover:bg-cream-100 transition">
        <svg class="h-5 w-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M22.56 12.25c0-.78-.07-1.53-.2-2.25H12v4.26h5.92a5.06 5.06 0 0 1-2.2 3.32v2.77h3.57c2.08-1.92 3.27-4.74 3.27-8.1Z"/><path fill="#34A853" d="M12 23c2.97 0 5.46-.98 7.28-2.66l-3.57-2.77c-.98.66-2.23 1.06-3.71 1.06-2.86 0-5.29-1.93-6.16-4.53H2.18v2.84A11 11 0 0 0 12 23Z"/><path fill="#FBBC05" d="M5.84 14.1a6.6 6.6 0 0 1 0-4.2V7.06H2.18a11 11 0 0 0 0 9.88l3.66-2.84Z"/><path fill="#EA4335" d="M12 5.38c1.62 0 3.06.56 4.21 1.64l3.15-3.15C17.45 2.09 14.97 1 12 1 7.7 1 3.99 3.47 2.18 7.06l3.66 2.84C6.71 7.3 9.14 5.38 12 5.38Z"/></svg>
        Daftar dengan Google
      </a>

      <div class="my-6 flex items-center gap-3 text-xs text-ink/40">
        <span class="h-px flex-1 bg-sand"></span> atau isi data <span class="h-px flex-1 bg-sand"></span>
      </div>

      <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Nama lengkap</label>
          <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('nama_lengkap')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Email</label>
          <input type="email" name="email" value="{{ old('email') }}" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('email')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Nomor telepon</label>
          <input type="text" name="no_telp" value="{{ old('no_telp') }}"
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Kata sandi</label>
          <input type="password" name="password" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Ulangi kata sandi</label>
          <input type="password" name="password_confirmation" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        </div>
        <button class="w-full rounded-xl bg-burgundy px-4 py-3 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
          Buat Akun
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>
