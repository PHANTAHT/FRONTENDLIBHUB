@extends('layouts.admin')
@section('title', 'Kelola Buku — Admin')
@section('page-title', 'Data Buku')
@section('page-sub', 'Kelola koleksi buku perpustakaan')
@section('page-actions')
  <a href="{{ route('admin.books.create') }}" class="flex items-center gap-1.5 rounded-full bg-burgundy px-4 py-2 text-sm font-semibold text-cream hover:bg-burgundy-700">
    <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
    Tambah Buku
  </a>
@endsection

@section('content')
<form method="GET" class="mb-5 flex items-center gap-2 rounded-xl border border-sand bg-white px-3 shadow-card focus-within:border-burgundy sm:max-w-md">
  <svg class="h-5 w-5 text-maroon/40" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
  <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari judul, pengarang, ISBN…" class="w-full bg-transparent py-2.5 text-sm outline-none placeholder:text-ink/30">
  <button class="text-sm font-medium text-burgundy">Cari</button>
</form>

<div class="rounded-2xl border border-sand/50 bg-white shadow-card">
  <div class="overflow-x-auto">
    <table class="w-full text-sm">
      <thead class="bg-cream-50 text-left text-xs uppercase tracking-wide text-ink/45">
        <tr>
          <th class="px-5 py-3 font-semibold">Buku</th>
          <th class="px-5 py-3 font-semibold">Kategori</th>
          <th class="px-5 py-3 font-semibold">ISBN</th>
          <th class="px-5 py-3 font-semibold">Stok</th>
          <th class="px-5 py-3 text-right font-semibold">Aksi</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/30">
        @forelse($books as $book)
          <tr class="hover:bg-cream-50/60">
            <td class="px-5 py-3">
              <div class="flex items-center gap-3">
                <img src="{{ $book->coverUrl() }}" alt="" class="h-12 w-9 shrink-0 rounded object-cover ring-1 ring-sand/60">
                <div class="min-w-0">
                  <p class="truncate font-medium text-ink">{{ $book->judul }}</p>
                  <p class="truncate text-xs text-ink/50">{{ $book->pengarang ?: 'Tanpa pengarang' }}</p>
                </div>
              </div>
            </td>
            <td class="px-5 py-3 text-ink/60">{{ $book->category->nama_kategori ?? '—' }}</td>
            <td class="px-5 py-3 font-mono text-xs text-ink/55">{{ $book->isbn ?: '—' }}</td>
            <td class="px-5 py-3">
              <span class="inline-flex rounded-full px-2.5 py-1 text-xs font-semibold {{ $book->stok > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">{{ $book->stok }}</span>
            </td>
            <td class="px-5 py-3">
              <div class="flex items-center justify-end gap-2">
                <a href="{{ route('admin.books.edit', $book) }}" class="rounded-full border border-maroon/15 px-3 py-1.5 text-xs font-semibold text-maroon hover:bg-cream-100">Edit</a>
                <form method="POST" action="{{ route('admin.books.destroy', $book) }}" onsubmit="return confirm('Hapus buku ini?')">
                  @csrf @method('DELETE')
                  <button class="rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                </form>
              </div>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-5 py-12 text-center text-ink/45">Belum ada buku. <a href="{{ route('admin.books.create') }}" class="font-medium text-burgundy hover:underline">Tambah buku pertama</a>.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($books->hasPages())
    <div class="border-t border-sand/40 px-5 py-3">{{ $books->withQueryString()->links() }}</div>
  @endif
</div>
@endsection
