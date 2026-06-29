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
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-maroon font-display text-xl font-semibold">P</span>
        <span class="font-display text-2xl font-semibold text-cream">Pustaka</span>
      </a>
      <div>
        <h2 class="font-display text-4xl font-semibold leading-tight text-cream">Buat kata sandi baru.</h2>
        <p class="mt-4 max-w-sm text-cream/70">Pilih kata sandi yang kuat dan mudah kamu ingat.</p>
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

      <h1 class="font-display text-3xl font-semibold text-maroon">Kata Sandi Baru</h1>
      <p class="mt-2 text-sm text-ink/60">Minimal 8 karakter.</p>

      @include('partials.flash')

      <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-4">
        @csrf
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Kata sandi baru</label>
          <input type="password" name="password" required autofocus
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('password')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Konfirmasi kata sandi</label>
          <input type="password" name="password_confirmation" required
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        </div>
        <button class="w-full rounded-xl bg-burgundy px-4 py-3 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
          Ganti kata sandi
        </button>
      </form>
    </div>
  </div>
</div>
</body>
</html>