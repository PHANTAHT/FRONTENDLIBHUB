@extends('layouts.admin')
@section('title', 'Kategori — Admin')
@section('page-title', 'Kategori / Genre')
@section('page-sub', 'Kelompokkan buku berdasarkan genre')

@section('content')
<div class="grid gap-6 lg:grid-cols-3">
  {{-- Form tambah --}}
  <div class="lg:col-span-1">
    <div class="rounded-2xl border border-sand/50 bg-white p-5 shadow-card" x-data="{ }">
      <h2 class="mb-4 font-display text-lg font-semibold text-maroon">Tambah Kategori</h2>
      <form method="POST" action="{{ route('admin.categories.store') }}" class="space-y-3">
        @csrf
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Nama Kategori</label>
          <input type="text" name="nama_kategori" required value="{{ old('nama_kategori') }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
          @error('nama_kategori')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Deskripsi</label>
          <input type="text" name="deskripsi" value="{{ old('deskripsi') }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
        <button class="w-full rounded-xl bg-burgundy py-2.5 text-sm font-semibold text-cream hover:bg-burgundy-700">Tambah</button>
      </form>
    </div>
  </div>

  {{-- Daftar --}}
  <div class="lg:col-span-2">
    <div class="rounded-2xl border border-sand/50 bg-white shadow-card">
      <div class="overflow-x-auto">
        <table class="w-full text-sm">
          <thead class="bg-cream-50 text-left text-xs uppercase tracking-wide text-ink/45">
            <tr>
              <th class="px-5 py-3 font-semibold">Nama</th>
              <th class="px-5 py-3 font-semibold">Deskripsi</th>
              <th class="px-5 py-3 font-semibold">Buku</th>
              <th class="px-5 py-3 text-right font-semibold">Aksi</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-sand/30">
            @forelse($categories as $cat)
              <tr x-data="{ edit: false }" class="hover:bg-cream-50/60">
                <template x-if="!edit">
                  <td class="px-5 py-3 font-medium text-ink">{{ $cat->nama_kategori }}</td>
                </template>
                <template x-if="!edit">
                  <td class="px-5 py-3 text-ink/60">{{ $cat->deskripsi ?: '—' }}</td>
                </template>
                <template x-if="!edit">
                  <td class="px-5 py-3"><span class="rounded-full bg-cream-100 px-2.5 py-1 text-xs font-semibold text-maroon">{{ $cat->books_count }}</span></td>
                </template>
                <template x-if="!edit">
                  <td class="px-5 py-3">
                    <div class="flex items-center justify-end gap-2">
                      <button @click="edit=true" class="rounded-full border border-maroon/15 px-3 py-1.5 text-xs font-semibold text-maroon hover:bg-cream-100">Edit</button>
                      <form method="POST" action="{{ route('admin.categories.destroy', $cat) }}" onsubmit="return confirm('Hapus kategori ini?')">
                        @csrf @method('DELETE')
                        <button class="rounded-full border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50">Hapus</button>
                      </form>
                    </div>
                  </td>
                </template>

                {{-- Inline edit row --}}
                <template x-if="edit">
                  <td colspan="4" class="px-5 py-3">
                    <form method="POST" action="{{ route('admin.categories.update', $cat) }}" class="flex flex-wrap items-center gap-2">
                      @csrf @method('PUT')
                      <input type="text" name="nama_kategori" value="{{ $cat->nama_kategori }}" required
                             class="flex-1 rounded-lg border border-sand bg-cream-50 px-3 py-2 text-sm outline-none focus:border-burgundy">
                      <input type="text" name="deskripsi" value="{{ $cat->deskripsi }}" placeholder="Deskripsi"
                             class="flex-1 rounded-lg border border-sand bg-cream-50 px-3 py-2 text-sm outline-none focus:border-burgundy">
                      <button class="rounded-full bg-burgundy px-3.5 py-2 text-xs font-semibold text-cream hover:bg-burgundy-700">Simpan</button>
                      <button type="button" @click="edit=false" class="rounded-full border border-maroon/15 px-3.5 py-2 text-xs font-semibold text-maroon">Batal</button>
                    </form>
                  </td>
                </template>
              </tr>
            @empty
              <tr><td colspan="4" class="px-5 py-12 text-center text-ink/45">Belum ada kategori.</td></tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection
