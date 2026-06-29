@extends('layouts.member')
@section('title', $book->judul . ' — Pustaka')
@section('content')

<a href="{{ route('member.books') }}" class="mb-6 inline-flex items-center gap-1.5 text-sm font-medium text-burgundy hover:underline">
  <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
  Kembali ke katalog
</a>

<div class="grid gap-8 lg:grid-cols-12">
  {{-- cover --}}
  <div class="lg:col-span-4">
    <div class="overflow-hidden rounded-2xl bg-sand/40 shadow-card ring-1 ring-black/5">
      <img src="{{ $book->coverUrl() }}" alt="{{ $book->judul }}" class="aspect-[2/3] w-full object-cover">
    </div>
  </div>

  {{-- info --}}
  <div class="lg:col-span-8">
    @if($book->category)
      <span class="text-xs font-semibold uppercase tracking-[0.18em] text-gold">{{ $book->category->nama_kategori }}</span>
    @endif
    <h1 class="mt-2 font-display text-4xl font-semibold leading-tight text-maroon">{{ $book->judul }}</h1>
    <p class="mt-2 text-lg text-ink/60">{{ $book->pengarang ?? 'Pengarang tidak diketahui' }}</p>

    <dl class="mt-6 grid grid-cols-2 gap-4 sm:grid-cols-4">
      @foreach([
        'Penerbit' => $book->penerbit ?? '—',
        'Tahun' => $book->tahun_terbit ?? '—',
        'Halaman' => $book->jumlah_halaman ?? '—',
        'ISBN' => $book->isbn ?? '—',
      ] as $label => $val)
        <div class="rounded-xl border border-sand/70 bg-white p-3.5">
          <dt class="text-xs text-ink/40">{{ $label }}</dt>
          <dd class="mt-0.5 text-sm font-semibold text-maroon">{{ $val }}</dd>
        </div>
      @endforeach
    </dl>

    @if($book->sinopsis)
      <div class="mt-6">
        <h2 class="font-display text-lg font-semibold text-maroon">Sinopsis</h2>
        <p class="mt-2 leading-relaxed text-ink/70">{{ $book->sinopsis }}</p>
      </div>
    @endif

    {{-- booking form --}}
    <div x-data="{ showNoStockModal: false }" class="mt-8 rounded-2xl border border-sand bg-cream-100 p-6 shadow-card">
      <div class="flex items-center justify-between">
        <div>
          <p class="text-sm font-semibold text-maroon">Booking buku ini</p>
          <p class="text-xs text-ink/50">{{ $book->availableStock() }} dari {{ $book->stok }} eksemplar tersedia</p>
        </div>
        @if($book->availableStock() > 0)
          <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">Bisa dibooking</span>
        @else
          <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-600">Stok habis</span>
        @endif
      </div>

      @php
        $hargaSewa = (int) config('perpustakaan.harga_sewa_per_hari');
        $depositPerBuku = (int) config('perpustakaan.deposit_per_buku');
        $maksHari = (int) config('perpustakaan.maks_hari_pinjam');
        $today = now()->toDateString();
        $besok = now()->addDay()->toDateString();
      @endphp
      <form method="POST" action="{{ route('member.booking.store', $book) }}" class="mt-4 space-y-4" @submit.prevent="{{ $book->availableStock() <= 0 ? 'showNoStockModal = true' : 'HTMLFormElement.prototype.submit.call($el)' }}">
        @csrf
        <div class="grid gap-3 sm:grid-cols-2">
          <div>
            <label class="mb-1.5 block text-sm font-medium text-maroon">Tanggal pengambilan</label>
            <input type="date" name="tanggal_booking" id="tglPinjam" required min="{{ $today }}" value="{{ old('tanggal_booking', $today) }}"
                   class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
            @error('tanggal_booking')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-maroon">Tanggal pengembalian</label>
            <input type="date" name="tanggal_kembali" id="tglKembali" required min="{{ $besok }}" value="{{ old('tanggal_kembali', $besok) }}"
                   class="w-full rounded-xl border border-maroon/15 bg-white px-4 py-2.5 text-sm focus:border-burgundy focus:ring-2 focus:ring-burgundy/20 focus:outline-none">
            @error('tanggal_kembali')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
          </div>
        </div>

        <div class="rounded-xl border border-sand bg-white px-4 py-3 text-sm">
          <div class="flex justify-between text-ink/60"><span>Lama pinjam</span><span id="estDurasi">– hari</span></div>
          <div class="flex justify-between text-ink/60"><span>Sewa (Rp {{ number_format($hargaSewa,0,',','.') }}/hari)</span><span id="estSewa">–</span></div>
          <div class="flex justify-between text-ink/60"><span>Deposit (jaminan)</span><span>Rp {{ number_format($depositPerBuku,0,',','.') }}</span></div>
          <div class="mt-2 flex justify-between border-t border-sand pt-2 font-semibold text-maroon"><span>Total bayar di awal</span><span id="estTotal">–</span></div>
          <p class="mt-1 text-xs text-ink/40">Deposit dikembalikan saat buku dibalikin (dipotong denda bila telat). Maks. pinjam {{ $maksHari }} hari.</p>
        </div>

        @if($book->availableStock() > 0)
          <button class="w-full rounded-xl bg-burgundy px-6 py-2.5 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition sm:w-auto">
            Booking sekarang
          </button>
        @else
          <button type="button" @click="showNoStockModal = true" class="w-full rounded-xl bg-sand/60 text-ink/40 px-6 py-2.5 text-sm font-semibold transition sm:w-auto">
            Booking sekarang (Stok Habis)
          </button>
        @endif
      </form>

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

      <!-- No Stock Modal -->
      <div x-show="showNoStockModal" x-cloak class="relative z-50" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div x-show="showNoStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-ink/40 backdrop-blur-sm transition-opacity"></div>
        <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
          <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div x-show="showNoStockModal" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" @click.outside="showNoStockModal = false"
                 class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-md sm:p-6">
              <div class="sm:flex sm:items-start">
                <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-red-100 text-red-600 sm:mx-0 sm:h-10 sm:w-10">
                  <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                  </svg>
                </div>
                <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                  <h3 class="font-display text-lg font-semibold leading-6 text-red-700" id="modal-title">Stok Buku Habis</h3>
                  <p class="mt-2 text-sm text-ink/65">Maaf, buku ini tidak dapat dibooking saat ini karena semua eksemplar sedang dipinjam atau dibooking oleh anggota lain.</p>
                </div>
              </div>
              <div class="mt-5 sm:mt-6">
                <button type="button" @click="showNoStockModal = false" class="inline-flex w-full justify-center rounded-xl bg-burgundy px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-burgundy-700 focus:outline-none transition">Mengerti</button>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>

@endsection
