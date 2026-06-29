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
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-cream text-maroon font-display text-xl font-semibold">P</span>
        <span class="font-display text-2xl font-semibold text-cream">Pustaka</span>
      </a>
      <div>
        <h2 class="font-display text-4xl font-semibold leading-tight text-cream">Satu langkah lagi.</h2>
        <p class="mt-4 max-w-sm text-cream/70">Kami mengirim kode verifikasi ke emailmu untuk memastikan akun ini benar milikmu.</p>
      </div>
      <p class="text-sm text-cream/40">Sistem Informasi Peminjaman Buku Perpustakaan</p>
    </div>
  </div>

  {{-- Form --}}
  <div class="flex items-center justify-center px-6 py-12">
    <div class="w-full max-w-sm">
      <a href="{{ route('landing') }}" class="mb-8 flex items-center gap-2 lg:hidden">
        <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-burgundy text-cream font-display font-semibold">LH</span>
        <span class="font-display text-xl font-semibold text-maroon">LibHub</span>
      </a>

      <h1 class="font-display text-3xl font-semibold text-maroon">Verifikasi Email</h1>
      <p class="mt-2 text-sm text-ink/60">Masukkan 6 digit kode yang dikirim ke<br>
        <span class="font-semibold text-maroon">{{ $email }}</span>
      </p>

      @include('partials.flash')

      <form method="POST" action="{{ route('otp.verify') }}" class="mt-6 space-y-4">
        @csrf
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Kode verifikasi</label>
          <input type="text" name="code" inputmode="numeric" maxlength="6" autofocus autocomplete="one-time-code"
                 placeholder="------" value="{{ old('code') }}"
                 class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-3 text-center text-2xl font-semibold tracking-[0.5em] text-maroon focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
          @error('code')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <button class="w-full rounded-xl bg-burgundy px-4 py-3 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
          Verifikasi
        </button>
      </form>

      <div class="my-6 flex items-center gap-3 text-xs text-ink/40">
        <span class="h-px flex-1 bg-sand"></span> tidak menerima kode? <span class="h-px flex-1 bg-sand"></span>
      </div>

      <form method="POST" action="{{ route('otp.resend') }}">
        @csrf
        <button id="resendBtn" type="submit"
                class="flex w-full items-center justify-center gap-2 rounded-xl border border-maroon/15 bg-white px-4 py-3 text-sm font-semibold text-maroon shadow-sm transition hover:bg-cream-100 disabled:cursor-not-allowed disabled:text-ink/30">
          <span>Kirim ulang kode</span>
          <span id="timer" class="text-ink/40"></span>
        </button>
      </form>
    </div>
  </div>
</div>

<script>
  (function () {
    let remaining = {{ $cooldown }};
    const btn = document.getElementById('resendBtn');
    const timer = document.getElementById('timer');
    (function tick() {
      if (remaining > 0) {
        btn.disabled = true;
        timer.textContent = '(' + remaining + ' dtk)';
        remaining--;
        setTimeout(tick, 1000);
      } else {
        btn.disabled = false;
        timer.textContent = '';
      }
    })();
  })();
</script>
</body>
</html>