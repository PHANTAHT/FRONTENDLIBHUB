@extends('layouts.admin')
@section('title', 'Riwayat Peminjaman — Admin')
@section('page-title', 'Riwayat Peminjaman')
@section('page-sub', 'Seluruh transaksi peminjaman & denda')
@section('page-actions')
  <a href="{{ route('admin.loans.index') }}" class="rounded-full bg-cream-100 px-4 py-2 text-sm font-medium text-maroon hover:bg-sand/40">← Transaksi</a>
@endsection

@section('content')
<div class="rounded-2xl border border-sand/50 bg-white shadow-card">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream-50 text-xs uppercase tracking-wide text-ink/45">
        <tr>
          <th class="px-5 py-3 font-semibold text-center">Anggota</th>
          <th class="px-5 py-3 font-semibold text-center">Buku</th>
          <th class="px-5 py-3 font-semibold text-center">Pinjam</th>
          <th class="px-5 py-3 font-semibold text-center">Tenggat</th>
          <th class="px-5 py-3 font-semibold text-center">Kembali</th>
          <th class="px-5 py-3 font-semibold text-center">Status</th>
          <th class="px-5 py-3 font-semibold text-center">Denda</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/30">
        @forelse($loans as $loan)
          <tr class="hover:bg-cream-50/60">
            <td class="px-5 py-3 font-medium text-ink text-center">{{ $loan->user->nama_lengkap ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/70 text-center">{{ $loan->items->pluck('book.judul')->filter()->join(', ') ?: '—' }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $loan->tanggal_pinjam?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $loan->tanggal_tenggat?->format('d M Y') }}</td>
            <td class="px-5 py-3 text-ink/60 text-center">{{ $loan->tanggal_kembali?->format('d M Y') ?? '—' }}</td>
            <td class="px-5 py-3 text-center">@include('partials.loan-status', ['status' => $loan->status])</td>
            <td class="px-5 py-3 text-center">
              @if($loan->denda > 0)
                <span class="font-semibold text-burgundy">Rp {{ number_format($loan->denda, 0, ',', '.') }}</span>
                @if($loan->fine)
                  <span class="ml-1 inline-flex rounded-full px-2 py-0.5 text-[11px] font-semibold {{ $loan->fine->status_bayar==='lunas' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">{{ $loan->fine->status_bayar }}</span>
                @endif
              @else
                <span class="text-ink/30">—</span>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="px-5 py-10 text-center text-ink/45">Belum ada riwayat.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($loans->hasPages())
    <div class="border-t border-sand/40 px-5 py-3">{{ $loans->links() }}</div>
  @endif
</div>
@endsection
