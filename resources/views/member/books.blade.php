@extends('layouts.member')
@section('title','Katalog Buku — Pustaka')
@section('content')

<div class="mb-6">
  <h1 class="font-display text-3xl font-semibold text-maroon">Jelajahi koleksi</h1>
  <p class="mt-1 text-sm text-ink/50">Cari berdasarkan judul, pengarang, atau ISBN.</p>
</div>

<form method="GET" class="space-y-4">
  {{-- search --}}
  <div class="relative">
    <svg class="pointer-events-none absolute left-4 top-1/2 h-5 w-5 -translate-y-1/2 text-ink/40" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m21 21-4.3-4.3m1.8-4.7a6.5 6.5 0 1 1-13 0 6.5 6.5 0 0 1 13 0Z"/></svg>
    <input type="search" name="q" value="{{ request('q') }}" placeholder="Cari judul, pengarang, atau ISBN…"
           class="w-full rounded-2xl border border-sand bg-white py-3.5 pl-12 pr-4 text-sm shadow-card focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
  </div>

  {{-- category chips --}}
  <div class="flex flex-wrap items-center gap-2">
    <button name="category" value="" class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ !request('category') ? 'bg-burgundy text-cream' : 'border border-sand bg-white text-maroon/70 hover:border-burgundy' }}">Semua</button>
    @foreach($categories as $cat)
      <button name="category" value="{{ $cat->id }}"
              class="rounded-full px-4 py-1.5 text-sm font-medium transition {{ request('category') == $cat->id ? 'bg-burgundy text-cream' : 'border border-sand bg-white text-maroon/70 hover:border-burgundy' }}">
        {{ $cat->nama_kategori }}
      </button>
    @endforeach
    <label class="ml-auto flex cursor-pointer items-center gap-2 rounded-full border border-sand bg-white px-4 py-1.5 text-sm font-medium text-maroon/70">
      <input type="checkbox" name="available" value="1" onchange="this.form.submit()" {{ request('available') ? 'checked' : '' }}
             class="rounded border-maroon/20 text-burgundy focus:ring-burgundy/30"> Tersedia saja
    </label>
  </div>
</form>

{{-- grid --}}
<div class="mt-8 grid grid-cols-2 gap-5 sm:grid-cols-3 lg:grid-cols-4">
  @forelse($books as $book)
    <a href="{{ route('member.books.show', $book) }}" class="group flex flex-col">
      <div class="relative aspect-[2/3] overflow-hidden rounded-xl bg-sand/40 shadow-card ring-1 ring-black/5">
        <img src="{{ $book->coverUrl() }}" alt="{{ $book->judul }}" class="h-full w-full object-cover transition group-hover:scale-105">
      </div>
      <h3 class="mt-3 line-clamp-2 text-sm font-semibold text-maroon">{{ $book->judul }}</h3>
      <p class="text-xs text-ink/50">{{ $book->pengarang ?? '—' }}</p>
      <span class="mt-2 inline-flex w-fit items-center gap-1.5 rounded-full px-2.5 py-1 text-xs font-semibold {{ $book->availableStock() > 0 ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-600' }}">
        <span class="h-1.5 w-1.5 rounded-full {{ $book->availableStock() > 0 ? 'bg-green-600' : 'bg-red-500' }}"></span>
        {{ $book->availableStock() > 0 ? $book->availableStock() . ' tersedia' : 'Habis' }}
      </span>
    </a>
  @empty
    <p class="col-span-full rounded-2xl border border-dashed border-sand bg-cream-100 p-10 text-center text-sm text-ink/50">
      Tidak ada buku yang cocok dengan pencarianmu.
    </p>
  @endforelse
</div>

<div class="mt-8">{{ $books->links() }}</div>

@endsection
