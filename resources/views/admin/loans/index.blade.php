@extends('layouts.admin')
@section('title', 'Transaksi Peminjaman — Admin')
@section('page-title', 'Transaksi Peminjaman')
@section('page-sub', 'Scan / masukkan kode booking untuk konfirmasi')
@section('page-actions')
  <a href="{{ route('admin.loans.history') }}" class="rounded-full bg-cream-100 px-4 py-2 text-sm font-medium text-maroon hover:bg-sand/40">Riwayat</a>
@endsection

@section('content')

{{-- ===== Kotak konfirmasi via kode booking ===== --}}
<div class="rounded-2xl border border-sand/50 bg-white p-5 shadow-card">
  <form method="POST" action="{{ route('admin.loans.confirm') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
    @csrf
    <div class="flex-1">
      <label class="mb-1.5 block text-sm font-medium text-maroon">Kode Booking</label>
      <div class="flex items-center gap-2 rounded-xl border border-sand bg-cream-50 px-3 focus-within:border-burgundy">
        <svg class="h-5 w-5 text-maroon/40" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
        <input type="text" name="kode_booking" value="{{ old('kode_booking') }}" placeholder="BK-XXXXXX" required
               class="w-full bg-transparent py-2.5 font-mono text-sm uppercase tracking-wider text-ink outline-none placeholder:text-ink/30">
      </div>
      @error('kode_booking')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>
    <button class="rounded-xl bg-burgundy px-6 py-2.5 text-sm font-semibold text-cream transition hover:bg-burgundy-700">
      Konfirmasi Peminjaman
    </button>
  </form>
  <p class="mt-3 text-xs text-ink/45">Anggota menunjukkan kode booking, admin menyerahkan buku fisik, lalu konfirmasi di sini untuk mencatat tanggal pinjam &amp; tenggat.</p>
</div>

{{-- ===== Booking menunggu (reserved) ===== --}}
<div class="mt-6 rounded-2xl border border-sand/50 bg-white shadow-card">
  <div class="border-b border-sand/40 px-5 py-4">
    <h2 class="font-display text-lg font-semibold text-maroon">Booking Menunggu Konfirmasi</h2>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream-50 text-xs uppercase tracking-wide text-ink/45">
        <tr>
          <th class="px-5 py-3 font-semibold text-center">Kode</th>
          <th class="px-5 py-3 font-semibold text-center">Anggota</th>
          <th class="px-5 py-3 font-semibold text-center">Buku</th>
          <th class="px-5 py-3 font-semibold text-center">Booking</th>
          <th class="px-5 py-3 font-semibold text-center">Expired</th>
          <th class="px-5 py-3 font-semibold text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/30">
        @forelse($reservedBookings as $b)
          <tr class="hover:bg-cream-50/60">
            <td class="px-5 py-3 text-center"><span class="rounded-lg bg-burgundy/10 px-2 py-1 font-mono text-xs font-semibold text-burgundy">{{ $b->kode_booking }}</span></td>
            <td class="px-5 py-3 font-medium text-ink text-center">{{ $b->user->nama_lengkap ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/70 text-center">{{ $b->book->judul ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $b->tanggal_booking?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $b->tanggal_expired?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-center">
              <div class="flex items-center justify-center gap-2">
                <form method="POST" action="{{ route('admin.loans.confirm') }}" class="inline-block">
                  @csrf
                  <input type="hidden" name="kode_booking" value="{{ $b->kode_booking }}">
                  <button class="rounded-full bg-burgundy px-3.5 py-1.5 text-xs font-semibold text-cream hover:bg-burgundy-700">Confirm loan</button>
                </form>
                <form method="POST" action="{{ route('admin.bookings.reject', $b) }}" onsubmit="return confirm('Batalkan booking ini?')" class="inline-block">
                  @csrf @method('PATCH')
                  <button class="rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Tolak</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="px-5 py-10 text-center text-ink/45">Tidak ada booking menunggu.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

{{-- ===== Peminjaman berjalan (open) ===== --}}
<div class="mt-6 rounded-2xl border border-sand/50 bg-white shadow-card">
  <div class="border-b border-sand/40 px-5 py-4">
    <h2 class="font-display text-lg font-semibold text-maroon">Peminjaman Berjalan</h2>
  </div>
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream-50 text-xs uppercase tracking-wide text-ink/45">
        <tr>
          <th class="px-5 py-3 font-semibold text-center">Anggota</th>
          <th class="px-5 py-3 font-semibold text-center">Buku</th>
          <th class="px-5 py-3 font-semibold text-center">Pinjam</th>
          <th class="px-5 py-3 font-semibold text-center">Tenggat</th>
          <th class="px-5 py-3 font-semibold text-center">Denda</th>
          <th class="px-5 py-3 font-semibold text-center">Status</th>
          <th class="px-5 py-3 font-semibold text-center">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/30">
        @forelse($openLoans as $loan)
          <tr class="hover:bg-cream-50/60">
            <td class="px-5 py-3 font-medium text-ink text-center">{{ $loan->user->nama_lengkap ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/70 text-center">{{ $loan->items->pluck('book.judul')->filter()->join(', ') ?: '—' }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $loan->tanggal_pinjam?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-center {{ $loan->isOverdue() ? 'font-semibold text-red-600' : 'text-ink/60' }}">
              {{ $loan->tanggal_tenggat?->format('d M Y') }}
              @if($loan->isOverdue())<span class="block text-[11px]">+{{ $loan->lateDays() }} hari</span>@endif
            </td>
            <td class="px-5 py-3 text-center">
              @if($loan->isOverdue())
                <span class="font-semibold text-red-600">Rp {{ number_format($loan->dendaEstimasi(), 0, ',', '.') }}</span>
              @else
                <span class="text-ink/40">—</span>
              @endif
            </td>
            <td class="px-5 py-3 text-center">@include('partials.loan-status', ['status' => $loan->status])</td>
            <td class="px-5 py-3 text-center">
              <form method="POST" action="{{ route('admin.loans.return', $loan) }}" onsubmit="return confirm('Konfirmasi pengembalian buku ini?')" class="inline-block">
                @csrf @method('PATCH')
                <button class="rounded-full border border-maroon/20 bg-cream-50 px-3.5 py-1.5 text-xs font-semibold text-maroon hover:bg-sand/40">Confirm return</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-5 py-10 text-center text-ink/45">Tidak ada peminjaman berjalan.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
