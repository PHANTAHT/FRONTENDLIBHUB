@extends('layouts.member')
@section('title','Pinjaman Aktif — Pustaka')
@section('content')

<h1 class="mb-6 font-display text-3xl font-semibold text-maroon">Pinjaman saya</h1>

<div class="overflow-hidden rounded-2xl border border-sand/70 bg-white shadow-card">
  <table class="w-full text-sm">
    <thead class="bg-cream-100 text-left text-xs uppercase tracking-wide text-ink/50">
      <tr>
        <th class="px-5 py-3 font-medium">Buku</th>
        <th class="px-5 py-3 font-medium">Dipinjam</th>
        <th class="px-5 py-3 font-medium">Tenggat</th>
        <th class="px-5 py-3 font-medium">Kembali</th>
        <th class="px-5 py-3 font-medium">Denda</th>
        <th class="px-5 py-3 font-medium">Status</th>
      </tr>
    </thead>
    <tbody class="divide-y divide-sand/50">
      @forelse($loans as $loan)
        <tr>
          <td class="px-5 py-3 font-medium text-maroon">{{ $loan->items->pluck('book.judul')->filter()->join(', ') ?: '—' }}</td>
          <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_pinjam?->translatedFormat('d M Y') ?? '—' }}</td>
          <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_tenggat?->translatedFormat('d M Y') ?? '—' }}</td>
          <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_kembali?->translatedFormat('d M Y') ?? '—' }}</td>
          <td class="px-5 py-3 {{ $loan->denda > 0 ? 'font-semibold text-red-600' : 'text-ink/60' }}">
            {{ $loan->denda > 0 ? 'Rp ' . number_format($loan->denda, 0, ',', '.') : '—' }}
          </td>
          <td class="px-5 py-3">@include('partials.loan-status', ['status' => $loan->status])</td>
        </tr>
      @empty
        <tr><td colspan="6" class="px-5 py-10 text-center text-ink/40">Belum ada pinjaman.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

@endsection
