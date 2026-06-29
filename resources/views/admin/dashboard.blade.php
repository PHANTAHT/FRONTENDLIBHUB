@extends('layouts.admin')
@section('title', 'Dashboard — Admin Pustaka')
@section('page-title', 'Dashboard')
@section('page-sub', 'Ringkasan aktivitas perpustakaan')

@section('content')
@php
  $cards = [
    ['Total Buku', $stats['buku'], 'bg-burgundy', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13'],
    ['Anggota', $stats['anggota'], 'bg-gold', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197'],
    ['Sedang Dipinjam', $stats['dipinjam'], 'bg-maroon', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2'],
    ['Terlambat', $stats['terlambat'], 'bg-red-600', 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z'],
    ['Booking Aktif', $stats['booking_aktif'], 'bg-sand-300', 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
  ];
@endphp

<div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-5">
  @foreach($cards as [$label, $value, $bg, $icon])
    <div class="rounded-2xl border border-sand/50 bg-white p-4 shadow-card">
      <span class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl {{ $bg }} text-cream">
        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
      </span>
      <p class="font-display text-3xl font-semibold text-maroon">{{ $value }}</p>
      <p class="text-sm text-ink/55">{{ $label }}</p>
    </div>
  @endforeach
</div>

<div class="mt-5 rounded-2xl border border-sand/50 bg-white p-5 shadow-card">
  <div class="flex items-center justify-between">
    <div>
      <p class="text-sm text-ink/55">Denda belum dibayar</p>
      <p class="font-display text-2xl font-semibold text-burgundy">Rp {{ number_format($stats['denda_belum'], 0, ',', '.') }}</p>
    </div>
    <a href="{{ route('admin.loans.index') }}" class="rounded-full bg-cream-100 px-4 py-2 text-sm font-medium text-maroon hover:bg-sand/40">Kelola transaksi →</a>
  </div>
</div>

<div class="mt-6 grid gap-6 lg:grid-cols-2">
  {{-- Booking menunggu konfirmasi --}}
  <div class="rounded-2xl border border-sand/50 bg-white shadow-card">
    <div class="flex items-center justify-between border-b border-sand/40 px-5 py-4">
      <h2 class="font-display text-lg font-semibold text-maroon">Booking Menunggu</h2>
      <a href="{{ route('admin.loans.index') }}" class="text-sm font-medium text-burgundy hover:underline">Lihat semua</a>
    </div>
    <div class="divide-y divide-sand/30">
      @forelse($recentBookings as $b)
        <div class="flex items-center gap-3 px-5 py-3">
          <span class="rounded-lg bg-burgundy/10 px-2 py-1 font-mono text-xs font-semibold text-burgundy">{{ $b->kode_booking }}</span>
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium text-ink">{{ $b->book->judul ?? '—' }}</p>
            <p class="truncate text-xs text-ink/50">{{ $b->user->nama_lengkap ?? '—' }}</p>
          </div>
          <span class="text-xs text-ink/40">{{ $b->tanggal_booking?->format('d M') }}</span>
        </div>
      @empty
        <p class="px-5 py-8 text-center text-sm text-ink/45">Belum ada booking aktif.</p>
      @endforelse
    </div>
  </div>

  {{-- Peminjaman terbaru --}}
  <div class="rounded-2xl border border-sand/50 bg-white shadow-card">
    <div class="flex items-center justify-between border-b border-sand/40 px-5 py-4">
      <h2 class="font-display text-lg font-semibold text-maroon">Peminjaman Terbaru</h2>
      <a href="{{ route('admin.loans.history') }}" class="text-sm font-medium text-burgundy hover:underline">Riwayat</a>
    </div>
    <div class="divide-y divide-sand/30">
      @forelse($recentLoans as $loan)
        <div class="flex items-center gap-3 px-5 py-3">
          <div class="min-w-0 flex-1">
            <p class="truncate text-sm font-medium text-ink">{{ $loan->user->nama_lengkap ?? '—' }}</p>
            <p class="truncate text-xs text-ink/50">{{ $loan->items->pluck('book.judul')->filter()->join(', ') ?: '—' }}</p>
          </div>
          @include('partials.loan-status', ['status' => $loan->status])
        </div>
      @empty
        <p class="px-5 py-8 text-center text-sm text-ink/45">Belum ada peminjaman.</p>
      @endforelse
    </div>
  </div>
</div>
@endsection
