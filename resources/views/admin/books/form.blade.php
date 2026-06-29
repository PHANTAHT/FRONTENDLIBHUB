@extends('layouts.admin')
@section('title', ($book->exists ? 'Edit' : 'Tambah') . ' Buku — Admin')
@section('page-title', $book->exists ? 'Edit Buku' : 'Tambah Buku')
@section('page-sub', 'Lengkapi data buku, atau isi ISBN untuk ambil otomatis dari Google Books')
@section('page-actions')
  <a href="{{ route('admin.books.index') }}" class="rounded-full bg-cream-100 px-4 py-2 text-sm font-medium text-maroon hover:bg-sand/40">← Kembali</a>
@endsection

@section('content')
<form method="POST" action="{{ $book->exists ? route('admin.books.update', $book) : route('admin.books.store') }}"
      enctype="multipart/form-data" class="grid gap-6 lg:grid-cols-3">
  @csrf
  @if($book->exists) @method('PUT') @endif

  {{-- Kolom kiri: data utama --}}
  <div class="space-y-5 lg:col-span-2">

    {{-- ISBN autofill --}}
    <div class="rounded-2xl border border-sand/50 bg-white p-5 shadow-card">
      <label class="mb-1.5 block text-sm font-medium text-maroon">ISBN</label>
      <div class="flex gap-2">
        <input type="text" name="isbn" id="isbn" value="{{ old('isbn', $book->isbn) }}" placeholder="978…"
               class="flex-1 rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        <button type="button" id="lookupBtn"
                class="flex items-center gap-1.5 rounded-xl bg-gold px-4 py-2.5 text-sm font-semibold text-white hover:opacity-90">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
          <span id="lookupLabel">Ambil data</span>
        </button>
      </div>
      <p id="lookupMsg" class="mt-2 hidden text-xs"></p>
      @error('isbn')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
    </div>

    <div class="rounded-2xl border border-sand/50 bg-white p-5 shadow-card space-y-4">
      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Judul <span class="text-red-500">*</span></label>
        <input type="text" name="judul" id="judul" value="{{ old('judul', $book->judul) }}" required
               class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        @error('judul')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
      </div>

      <div class="grid gap-4 sm:grid-cols-2">
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Pengarang</label>
          <input type="text" name="pengarang" id="pengarang" value="{{ old('pengarang', $book->pengarang) }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Penerbit</label>
          <input type="text" name="penerbit" id="penerbit" value="{{ old('penerbit', $book->penerbit) }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
      </div>

      <div class="grid gap-4 sm:grid-cols-3">
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Tahun Terbit</label>
          <input type="number" name="tahun_terbit" id="tahun_terbit" value="{{ old('tahun_terbit', $book->tahun_terbit) }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Jml Halaman</label>
          <input type="number" name="jumlah_halaman" id="jumlah_halaman" value="{{ old('jumlah_halaman', $book->jumlah_halaman) }}"
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
        <div>
          <label class="mb-1.5 block text-sm font-medium text-maroon">Stok <span class="text-red-500">*</span></label>
          <input type="number" name="stok" value="{{ old('stok', $book->stok ?? 1) }}" min="0" required
                 class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
        </div>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Kategori</label>
        <select name="category_id" class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">
          <option value="">— Tanpa kategori —</option>
          @foreach($categories as $cat)
            <option value="{{ $cat->id }}" @selected(old('category_id', $book->category_id) == $cat->id)>{{ $cat->nama_kategori }}</option>
          @endforeach
        </select>
      </div>

      <div>
        <label class="mb-1.5 block text-sm font-medium text-maroon">Sinopsis</label>
        <textarea name="sinopsis" id="sinopsis" rows="4"
                  class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2.5 text-sm outline-none focus:border-burgundy">{{ old('sinopsis', $book->sinopsis) }}</textarea>
      </div>
    </div>
  </div>

  {{-- Kolom kanan: cover --}}
  <div class="space-y-5">
    <div class="rounded-2xl border border-sand/50 bg-white p-5 shadow-card">
      <p class="mb-3 text-sm font-medium text-maroon">Foto Sampul</p>
      <img id="coverPreview" src="{{ $book->coverUrl() }}" alt="" class="mb-3 aspect-[3/4] w-full rounded-xl object-cover ring-1 ring-sand/60">
      <label class="mb-1.5 block text-xs font-medium text-ink/60">Unggah file</label>
      <input type="file" name="cover_file" accept="image/*"
             class="w-full text-xs file:mr-3 file:rounded-full file:border-0 file:bg-burgundy file:px-3 file:py-1.5 file:text-cream">
      @error('cover_file')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
      <label class="mb-1.5 mt-3 block text-xs font-medium text-ink/60">atau URL gambar</label>
      <input type="text" name="foto_sampul" id="foto_sampul" value="{{ old('foto_sampul', is_string($book->foto_sampul) && str_starts_with($book->foto_sampul,'http') ? $book->foto_sampul : '') }}"
             placeholder="https://…" class="w-full rounded-xl border border-sand bg-cream-50 px-3 py-2 text-xs outline-none focus:border-burgundy">
    </div>

    <button class="w-full rounded-xl bg-burgundy py-3 text-sm font-semibold text-cream transition hover:bg-burgundy-700">
      {{ $book->exists ? 'Simpan Perubahan' : 'Simpan Buku' }}
    </button>
  </div>
</form>

<script>
  const btn = document.getElementById('lookupBtn');
  const label = document.getElementById('lookupLabel');
  const msg = document.getElementById('lookupMsg');

  function showMsg(text, ok) {
    msg.textContent = text;
    msg.className = 'mt-2 text-xs ' + (ok ? 'text-green-600' : 'text-red-600');
    msg.classList.remove('hidden');
  }

  btn.addEventListener('click', async () => {
    const isbn = document.getElementById('isbn').value.trim();
    if (!isbn) { showMsg('Isi ISBN dulu.', false); return; }
    label.textContent = 'Mengambil…';
    btn.disabled = true;
    try {
      const url = "{{ route('admin.books.lookup') }}?isbn=" + encodeURIComponent(isbn);
      const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
      if (!res.ok) { showMsg('Buku tidak ditemukan untuk ISBN tersebut.', false); return; }
      const json = await res.json();
      const d = json.data || {};
      const setIf = (id, val) => { const el = document.getElementById(id); if (el && val) el.value = val; };
      setIf('judul', d.judul);
      setIf('pengarang', d.pengarang);
      setIf('penerbit', d.penerbit);
      setIf('tahun_terbit', d.tahun_terbit);
      setIf('jumlah_halaman', d.jumlah_halaman);
      setIf('sinopsis', d.sinopsis);
      if (d.foto_sampul) {
        document.getElementById('foto_sampul').value = d.foto_sampul;
        document.getElementById('coverPreview').src = d.foto_sampul;
      }
      showMsg('Data berhasil diisi dari Google Books ✓', true);
    } catch (e) {
      showMsg('Gagal menghubungi server.', false);
    } finally {
      label.textContent = 'Ambil data';
      btn.disabled = false;
    }
  });

  // preview URL cover saat diketik
  document.getElementById('foto_sampul').addEventListener('change', (e) => {
    if (e.target.value) document.getElementById('coverPreview').src = e.target.value;
  });
</script>
@endsection
