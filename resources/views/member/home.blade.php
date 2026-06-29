@extends('layouts.member')
@section('title','Beranda — Pustaka')
@section('content')

<div class="mb-8">
  <p class="text-sm text-ink/50">Selamat datang kembali,</p>
  <h1 class="font-display text-3xl font-semibold text-maroon">{{ auth()->user()->nama_lengkap }}</h1>
</div>

{{-- stat cards --}}
<div class="grid gap-4 sm:grid-cols-3">
  <div class="rounded-2xl border border-sand/70 bg-white p-5 shadow-card">
    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40">Sedang dipinjam</p>
    <p class="mt-2 font-display text-4xl font-semibold text-burgundy">{{ $activeLoans->count() }}</p>
    <p class="mt-1 text-xs text-ink/50">dari {{ config('perpustakaan.maks_pinjam') }} maksimal</p>
  </div>
  <div class="rounded-2xl border border-sand/70 bg-white p-5 shadow-card">
    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40">Tenggat terdekat</p>
    <p class="mt-2 font-display text-3xl font-semibold text-burgundy">
      {{ $nextDue?->tanggal_tenggat?->translatedFormat('d M') ?? '—' }}
    </p>
    @if($nextDue)
      <p class="mt-1 text-xs {{ $nextDue->isOverdue() ? 'text-red-600' : 'text-ink/50' }}">
        {{ $nextDue->isOverdue() ? 'Terlambat' : 'dalam ' . (int) now()->diffInDays($nextDue->tanggal_tenggat, false) . ' hari' }}
      </p>
    @endif
  </div>
  <div class="rounded-2xl border border-sand/70 bg-white p-5 shadow-card">
    <p class="text-xs font-semibold uppercase tracking-wide text-ink/40">Denda belum lunas</p>
    <p class="mt-2 font-display text-3xl font-semibold {{ $outstandingFines > 0 ? 'text-red-600' : 'text-burgundy' }}">
      Rp {{ number_format($outstandingFines, 0, ',', '.') }}
    </p>
  </div>
</div>

<section class="mt-12">

    <div class="max-w-6xl mx-auto px-6">

        <div class="flex items-center justify-between mb-8">

            <h2 class="font-display text-2xl font-semibold text-maroon">
                Buku Populer
            </h2>

            <a href="{{ route('member.books') }}"
               class="text-sm font-semibold text-burgundy hover:underline">
                Jelajahi katalog →
            </a>

        </div>

    </div>

    @if($popularBooks->isNotEmpty())

      <link rel="stylesheet"
              href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

        <div class="max-w-6xl mx-auto">
          <div class="swiper bukuSwiper">
            <div class="swiper-wrapper">

                @foreach($popularBooks as $book)

                    <div class="swiper-slide">

                        <a href="{{ route('member.books.show', $book) }}"
                           class="block">

                            <div class="book-card">

                                <img
                                    src="{{ $book->coverUrl() }}"
                                    alt="{{ $book->judul }}"
                                    class="book-image">

                            </div>

                            <div class="mt-4 text-center">
                                <p class="font-semibold text-maroon line-clamp-1">
                                    {{ $book->judul }}
                                </p>

                                <p class="text-sm text-ink/50 line-clamp-1">
                                    {{ $book->pengarang }}
                                </p>
                            </div>

                        </a>

                    </div>

                @endforeach

              </div>
            <div>
        </div>

        <style>

            .bukuSwiper{
                overflow:hidden;
                padding:25px 0 60px;
            }

            .bukuSwiper .swiper-wrapper{
                transition-timing-function: linear !important;
            }

            .bukuSwiper .swiper-slide{
                width:260px !important;
                transition:all .35s ease;
            }

            .bukuSwiper .swiper-slide:hover{
                transform:translateY(-8px) scale(1.03);
            }

            .book-card{
                height:380px;
                overflow:hidden;
                border-radius:24px;
                background:#f3ece4;
                box-shadow:
                    0 10px 30px rgba(0,0,0,.08);
                transition:all .3s ease;
            }

            .book-card:hover{
                transform:translateY(-10px);
                box-shadow:
                    0 20px 50px rgba(0,0,0,.14);
            }

            .book-image{
                width:100%;
                height:100%;
                object-fit:cover;
                display:block;
            }

            @media(max-width:768px){

                .bukuSwiper .swiper-slide{
                    width:220px !important;
                }

                .book-card{
                    height:320px;
                }

            }

        </style>

        <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

        <script>

        document.addEventListener('DOMContentLoaded', function () {

            new Swiper('.bukuSwiper', {

                slidesPerView: 'auto',
                spaceBetween: 24,

                loop: true,

                speed: 5000,

                freeMode: true,

                allowTouchMove: true,
                grabCursor: true,

                autoplay: {
                    delay: 0,
                    disableOnInteraction: false,
                    pauseOnMouseEnter: true,
                },

            });

        });

        </script>

    @else

        <p class="text-sm text-ink/50">
            Belum ada buku.
        </p>

    @endif

</section>

{{-- borrowing history --}}
<section class="mt-10">
  <h2 class="font-display text-xl font-semibold text-maroon">Riwayat peminjaman</h2>
  <div class="mt-4 overflow-hidden rounded-2xl border border-sand/70 bg-white shadow-card">
    <table class="w-full text-sm">
      <thead class="bg-cream-100 text-left text-xs uppercase tracking-wide text-ink/50">
        <tr>
          <th class="px-5 py-3 font-medium">Buku</th>
          <th class="px-5 py-3 font-medium">Dipinjam</th>
          <th class="px-5 py-3 font-medium">Tenggat</th>
          <th class="px-5 py-3 font-medium">Kembali</th>
          <th class="px-5 py-3 font-medium">Status</th>
        </tr>
      </thead>
      <tbody class="divide-y divide-sand/50">
        @forelse($history as $loan)
          <tr>
            <td class="px-5 py-3 font-medium text-maroon">{{ $loan->items->pluck('book.judul')->filter()->join(', ') ?: '—' }}</td>
            <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_pinjam?->translatedFormat('d M') ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_tenggat?->translatedFormat('d M') ?? '—' }}</td>
            <td class="px-5 py-3 text-ink/60">{{ $loan->tanggal_kembali?->translatedFormat('d M') ?? '—' }}</td>
            <td class="px-5 py-3">@include('partials.loan-status', ['status' => $loan->status])</td>
          </tr>
        @empty
          <tr><td colspan="5" class="px-5 py-8 text-center text-ink/40">Belum ada riwayat peminjaman.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</section>

@endsection
