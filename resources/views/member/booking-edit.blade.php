@extends('layouts.member')
@section('title', 'Edit Booking — Pustaka')
@section('content')

<a href="{{ route('member.bookings') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-burgundy hover:underline">
  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
  Kembali ke booking saya
</a>

<div class="max-w-2xl mx-auto rounded-2xl border border-sand bg-white p-6 shadow-card">
  <h2 class="font-display text-2xl font-semibold text-maroon mb-6 text-center">Edit Tanggal Booking</h2>

  {{-- Book Details Card --}}
  <div class="flex gap-4 rounded-xl border border-sand/40 bg-cream-50 p-4 mb-6">
    <div class="h-20 w-14 shrink-0 overflow-hidden rounded-lg bg-sand/30 shadow-sm">
      <img src="{{ $booking->book->coverUrl() }}" alt="{{ $booking->book->judul }}" class="h-full w-full object-cover">
    </div>
    <div>
      <p class="font-semibold text-maroon">{{ $booking->book->judul }}</p>
      <p class="text-sm text-ink/50">{{ $booking->book->pengarang ?? 'Pengarang tidak diketahui' }}</p>
      <p class="mt-1 text-xs text-ink/40">Kode Booking: <span class="font-mono font-semibold text-burgundy">{{ $booking->kode_booking }}</span></p>
    </div>
  </div>

  @php
    $hargaSewa = (int) config('perpustakaan.harga_sewa_per_hari');
    $depositPerBuku = (int) config('perpustakaan.deposit_per_buku');
    $maksHari = (int) config('perpustakaan.maks_hari_pinjam');
    $today = now()->toDateString();
    $besok = now()->addDay()->toDateString();
  @endphp

  <form method="POST" action="{{ route('member.booking.update', $booking->id) }}" class="space-y-4">
    @csrf
    @method('PUT')

    <div class="grid gap-4 sm:grid-cols-2">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Tanggal pengambilan</label>
        <input type="date" name="tanggal_booking" id="tglPinjam" required min="{{ $today }}" 
               value="{{ old('tanggal_booking', $booking->tanggal_booking->toDateString()) }}"
               class="w-full rounded-xl border border-maroon/15 bg-cream-50 px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        @error('tanggal_booking')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
      </div>
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Tanggal pengembalian</label>
        <input type="date" name="tanggal_kembali" id="tglKembali" required min="{{ $besok }}" 
               value="{{ old('tanggal_kembali', $booking->tanggal_rencana_kembali->toDateString()) }}"
               class="w-full rounded-xl border border-maroon/15 bg-cream-50 px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
        @error('tanggal_kembali')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
      </div>
    </div>

    <div class="rounded-xl border border-sand bg-cream-50 px-4 py-3 text-sm">
      <div class="flex justify-between text-ink/60"><span>Lama pinjam</span><span id="estDurasi">– hari</span></div>
      <div class="flex justify-between text-ink/60"><span>Sewa (Rp {{ number_format($hargaSewa,0,',','.') }}/hari)</span><span id="estSewa">–</span></div>
      <div class="flex justify-between text-ink/60"><span>Deposit (jaminan)</span><span>Rp {{ number_format($depositPerBuku,0,',','.') }}</span></div>
      <div class="mt-2 flex justify-between border-t border-sand pt-2 font-semibold text-maroon"><span>Total bayar di awal</span><span id="estTotal">–</span></div>
      <p class="mt-1 text-xs text-ink/40">Deposit dikembalikan saat buku dibalikin (dipotong denda bila telat). Maks. pinjam {{ $maksHari }} hari.</p>
    </div>

    <div class="flex gap-3 pt-2">
      <button class="flex-1 sm:flex-initial rounded-xl bg-burgundy px-6 py-2.5 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
        Simpan Perubahan
      </button>
      <a href="{{ route('member.bookings') }}" class="flex-1 sm:flex-initial text-center rounded-xl border border-sand bg-white px-6 py-2.5 text-sm font-semibold text-ink/75 hover:bg-cream-50 transition">
        Batal
      </a>
    </div>
  </form>
</div>

<script>
  (function () {
    const harga = {{ $hargaSewa }}, deposit = {{ $depositPerBuku }};
    const pinjam = document.getElementById('tglPinjam');
    const kembali = document.getElementById('tglKembali');
    const fmt = n => 'Rp ' + n.toLocaleString('id-ID');
    function hitung() {
      const a = new Date(pinjam.value), b = new Date(kembali.value);
      let hari = Math.round((b - a) / 86400000);
      if (!hari || hari < 1) hari = 0;
      const sewa = harga * hari, total = sewa + deposit;
      document.getElementById('estDurasi').textContent = hari ? hari + ' hari' : '– hari';
      document.getElementById('estSewa').textContent = hari ? fmt(sewa) : '–';
      document.getElementById('estTotal').textContent = hari ? fmt(total) : '–';
    }
    pinjam.addEventListener('change', () => { kembali.min = pinjam.value; hitung(); });
    kembali.addEventListener('change', hitung);
    hitung();
  })();
</script>

@endsection
