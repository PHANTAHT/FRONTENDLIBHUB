<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
  @include('partials.head')
</head>
<body class="bg-cream-50 text-ink font-sans antialiased">

  {{-- ===================== NAV ===================== --}}
  <header class="absolute inset-x-0 top-0 z-30">
    <nav class="mx-auto flex max-w-7xl items-center justify-between px-6 py-5 lg:px-8">
      <a href="{{ route('landing') }}" class="flex items-center gap-2.5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-burgundy text-cream font-display text-xl font-semibold leading-none">LH</span>
        <span class="font-display text-2xl font-semibold tracking-tight text-maroon">LibHub</span>
      </a>

      <div class="hidden items-center gap-8 text-sm font-medium text-maroon/80 md:flex">
        <a href="#katalog" class="hover:text-burgundy transition">Katalog</a>
        <a href="#cara" class="hover:text-burgundy transition">Cara Pinjam</a>
        <a href="#fitur" class="hover:text-burgundy transition">Fitur</a>
      </div>

      <div class="flex items-center gap-3">
        @auth
          <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.home') }}"
             class="rounded-full bg-burgundy px-5 py-2.5 text-sm font-semibold text-cream shadow-sm hover:bg-burgundy-700 transition">
            Dashboard
          </a>
        @else
          <a href="{{ route('login') }}" class="hidden text-sm font-semibold text-maroon hover:text-burgundy sm:block">Masuk</a>
          <a href="{{ route('register') }}"
             class="rounded-full bg-burgundy px-5 py-2.5 text-sm font-semibold text-cream shadow-sm hover:bg-burgundy-700 transition">
            Daftar Anggota
          </a>
        @endauth
      </div>
    </nav>
  </header>

  {{-- ===================== HERO ===================== --}}
  <section class="relative overflow-hidden"style="
  background: radial-gradient(circle at top right,
  rgba(128,0,32,.08),
  transparent 40%);
  ">
    {{-- soft background wash --}}
    <div class="pointer-events-none absolute inset-0 -z-10">
      <div class="absolute -right-32 -top-24 h-[34rem] w-[34rem] rounded-full bg-sand/40 blur-3xl"></div>
      <div class="absolute -left-40 top-40 h-96 w-96 rounded-full bg-cream blur-3xl"></div>
    </div>

    <div class="mx-auto grid max-w-7xl items-center gap-12 px-6 pb-20 pt-32 lg:grid-cols-12 lg:gap-8 lg:px-8 lg:pb-28 lg:pt-40">
      {{-- copy --}}
      <div class="lg:col-span-6">
        <span class="inline-flex items-center gap-2 rounded-full border border-sand bg-cream-100 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-burgundy">
          <span class="h-1.5 w-1.5 rounded-full bg-burgundy"></span> Perpustakaan Digital
        </span>

        <h1 class="mt-6 font-display text-5xl font-semibold leading-[1.05] tracking-tight text-maroon sm:text-6xl">
          Temukan, Booking,<br>dan Pinjam Buku Lebih Mudah.
        </h1>

        <p class="mt-6 max-w-md text-lg leading-relaxed text-ink/70">
          LibHub membantu mahasiswa menemukan, memesan,
          dan mengelola peminjaman buku secara digital
          melalui satu platform yang cepat, modern,
          dan mudah digunakan.
        </p>

        <div class="mt-9 flex flex-wrap items-center gap-3">
          <a href="{{ route('register') }}"
             class="rounded-full bg-burgundy px-7 py-3.5 text-sm font-semibold text-cream shadow-card hover:bg-burgundy-700 transition">
            Jelajahi Katalog
          </a>
          <a href="{{ route('login') }}"
             class="rounded-full border border-maroon/20 bg-white px-7 py-3.5 text-sm font-semibold text-maroon hover:border-burgundy hover:text-burgundy transition">
            Masuk
          </a>
        </div>

        {{-- stats --}}
        <dl class="mt-12 grid max-w-md grid-cols-3 gap-6 border-t border-sand/70 pt-7">
          <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-ink/50">Koleksi</dt>
            <dd class="mt-1 font-display text-3xl font-semibold text-burgundy">{{ number_format($stats['buku']) }}+</dd>
          </div>
          <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-ink/50">Judul</dt>
            <dd class="mt-1 font-display text-3xl font-semibold text-burgundy">{{ number_format($stats['judul']) }}</dd>
          </div>
          <div>
            <dt class="text-xs font-medium uppercase tracking-wide text-ink/50">Kategori</dt>
            <dd class="mt-1 font-display text-3xl font-semibold text-burgundy">{{ number_format($stats['kategori']) }}</dd>
          </div>
        </dl>
      </div>

      {{-- signature visual: fanned book covers + booking chip --}}
      <div class="relative lg:col-span-6">
        <div class="relative mx-auto flex h-[26rem] max-w-md items-center justify-center">
          @php $fan = $popularBooks->take(3)->values(); @endphp
          <div class="absolute h-96 w-96 rounded-full bg-burgundy/20 blur-[120px]"></div>
          {{-- back card --}}
          <div class="absolute h-72 w-52 -rotate-12 rounded-2xl bg-maroon shadow-2xl"
               style="transform: rotate(-13deg) translateX(-72px) translateY(18px);">
            @if(isset($fan[2]))<img src="{{ $fan[2]->coverUrl() }}" alt="" class="h-full w-full rounded-2xl object-cover opacity-95">@endif
          </div>
          {{-- mid card --}}
          <div class="absolute h-80 w-56 rounded-2xl bg-burgundy-700 shadow-2xl"
               style="transform: rotate(7deg) translateX(70px) translateY(-6px);">
            @if(isset($fan[1]))<img src="{{ $fan[1]->coverUrl() }}" alt="" class="h-full w-full rounded-2xl object-cover">@endif
          </div>
          {{-- front card --}}
          <div class="absolute h-[22rem] w-60 rounded-2xl bg-burgundy shadow-2xl ring-1 ring-black/5">
            @if(isset($fan[0]))
              <img src="{{ $fan[0]->coverUrl() }}" alt="{{ $fan[0]->judul }}" class="h-full w-full rounded-2xl object-cover">
            @else
              <div class="flex h-full flex-col justify-end rounded-2xl bg-gradient-to-br from-burgundy to-maroon p-6 text-cream">
                <span class="font-display text-2xl font-semibold">Koleksi Pilihan</span>
                <span class="mt-1 text-sm text-cream/70">Fiksi · Sains · Sejarah</span>
              </div>
            @endif
          </div>

          {{-- floating booking code chip (the signature) --}}
          <div class="absolute -bottom-2 right-2 w-56 rotate-3 rounded-2xl border border-sand bg-white p-4 shadow-card">
            <div class="flex items-center justify-between">
              <span class="text-[10px] font-semibold uppercase tracking-widest text-ink/40">Kode Booking</span>
              <span class="rounded-full bg-green-100 px-2 py-0.5 text-[10px] font-semibold text-green-700">Siap diambil</span>
            </div>
            <div class="mt-2 font-display text-2xl font-semibold tracking-wider text-burgundy">BK-7K2QX</div>
            <div class="mt-1 text-xs text-ink/50">Tunjukkan ke petugas perpustakaan</div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <section class="mx-auto max-w-7xl px-6 pb-20 lg:px-8">
    <div class="grid gap-6 md:grid-cols-4">

        <div class="rounded-2xl bg-white p-6 shadow-card">
            <p class="text-sm text-ink/50">Total Buku</p>
            <h3 class="mt-2 text-3xl font-bold text-maroon">10.000+</h3>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-card">
            <p class="text-sm text-ink/50">Anggota Aktif</p>
            <h3 class="mt-2 text-3xl font-bold text-maroon">3.200+</h3>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-card">
            <p class="text-sm text-ink/50">Peminjaman</p>
            <h3 class="mt-2 text-3xl font-bold text-maroon">24.000+</h3>
        </div>

        <div class="rounded-2xl bg-white p-6 shadow-card">
            <p class="text-sm text-ink/50">Kepuasan</p>
            <h3 class="mt-2 text-3xl font-bold text-maroon">98%</h3>
        </div>

    </div>
</section>

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

  {{-- ===================== CARA PINJAM ===================== --}}
  <section id="cara" class="mx-auto max-w-7xl px-6 py-20 lg:px-8 border-t border-sand/30">
    <div class="text-center max-w-3xl mx-auto">
      <span class="inline-flex items-center gap-1.5 rounded-full bg-burgundy/10 px-3.5 py-1.5 text-xs font-semibold uppercase tracking-wider text-burgundy">
        Alur Layanan
      </span>
      <h2 class="mt-4 font-display text-3xl font-semibold tracking-tight text-maroon sm:text-4xl">
        Cara Mudah Pinjam Buku di LibHub
      </h2>
      <p class="mt-4 text-ink/60 leading-relaxed">
        Ikuti 4 langkah praktis berikut untuk mulai meminjam buku pilihanmu tanpa perlu mengantre lama.
      </p>
    </div>

    <div class="mt-16 grid gap-8 sm:grid-cols-2 lg:grid-cols-4">
      @php
        $steps = [
          [
            '01', 
            'Pilih Buku', 
            'Cari buku favoritmu melalui halaman katalog pencarian yang lengkap dan ter-update.',
            'M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.602 10.602z'
          ],
          [
            '02', 
            'Booking Online', 
            'Lakukan booking secara instan untuk mengamankan kuota stok buku secara otomatis.',
            'M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z'
          ],
          [
            '03', 
            'Ambil Buku', 
            'Tunjukkan kode booking unik Anda ke petugas perpustakaan untuk menukarnya dengan buku fisik.',
            'M12 6.042A8.967 8.967 0 006 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 016 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 016-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0018 18a8.967 8.967 0 00-6 2.292m0-14.25v14.25'
          ],
          [
            '04', 
            'Kembalikan Tepat Waktu', 
            'Nikmati bacaanmu dan kembalikan buku tepat waktu untuk menjaga sirkulasi buku tetap lancar.',
            'M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z'
          ]
        ];
      @endphp

      @foreach($steps as [$num, $title, $desc, $svgPath])
        <div class="relative group rounded-2xl border border-sand/70 bg-white p-7 shadow-card transition-all duration-300 hover:-translate-y-1 hover:shadow-lg">
          <div class="absolute top-6 right-6 text-4xl font-display font-bold text-burgundy/30 group-hover:text-burgundy/50 transition-colors">
            {{ $num }}
          </div>
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-burgundy/10 text-burgundy">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="{{ $svgPath }}"/>
            </svg>
          </div>
          <h3 class="mt-6 font-display text-xl font-semibold text-maroon">{{ $title }}</h3>
          <p class="mt-2.5 text-sm leading-relaxed text-ink/60">{{ $desc }}</p>
        </div>
      @endforeach
    </div>
  </section>

  {{-- ===================== FITUR ===================== --}}
  <section id="fitur" class="mx-auto max-w-7xl px-6 py-20 lg:px-8">
    <div class="grid gap-8 lg:grid-cols-3">
      @php
        $features = [
          ['Smart Reservation', 'Reservasi buku dari mana saja. Stok ditahan otomatis sampai kamu datang mengambil.'],
          ['Real-Time Loan Tracking', 'Tenggat dan denda keterlambatan dihitung otomatis dan terlihat di riwayatmu.'],
          ['Cross Platform Access', 'Tersedia REST API internal (/api/books, /api/loans) untuk aplikasi mobile ke depan.'],
        ];
      @endphp
      @foreach($features as [$t, $d])
        <div class="rounded-2xl border border-sand/70 bg-white p-7 shadow-card">
          <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-burgundy/10 text-burgundy">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.25v11.5m5.75-5.75H6.25"/></svg>
          </div>
          <h3 class="mt-5 font-display text-xl font-semibold text-maroon">{{ $t }}</h3>
          <p class="mt-2 text-sm leading-relaxed text-ink/60">{{ $d }}</p>
        </div>
      @endforeach
    </div>

    {{-- CTA band --}}
    <div class="mt-16 overflow-hidden rounded-3xl bg-maroon px-8 py-12 text-center shadow-card sm:px-16">
      <h2 class="font-display text-3xl font-semibold text-cream sm:text-4xl">Mulai Eksplorasi Koleksi LibHub</h2>
      <p class="mx-auto mt-3 max-w-lg text-cream/70">Gratis untuk seluruh anggota. Daftar dalam satu menit dan langsung jelajahi koleksi.</p>
      <a href="{{ route('register') }}" class="mt-7 inline-block rounded-full bg-cream px-8 py-3.5 text-sm font-semibold text-maroon hover:bg-white transition">
        Gabung Sekarang
      </a>
    </div>
  </section>
  <section class="mx-auto max-w-7xl px-6 mt-24 mb-28 lg:px-8">

    <div class="text-center">
        <p class="text-sm font-semibold text-burgundy uppercase tracking-widest">
            Testimoni
        </p>

        <h2 class="mt-2 font-display text-3xl font-semibold text-maroon">
            Apa Kata Pengguna?
        </h2>
    </div>

    <div class="mt-12 grid gap-6 md:grid-cols-3">

        <div class="rounded-3xl bg-white p-7 shadow-card border border-sand/50">
            <p class="text-ink/75 leading-relaxed italic">
                "Booking buku jadi jauh lebih mudah dan tidak perlu antre di perpustakaan."
            </p>

            <div class="mt-6 flex items-center gap-3.5">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-burgundy/10 text-burgundy font-semibold text-sm">
                    DB
                </div>
                <div>
                    <p class="font-semibold text-maroon text-sm leading-none">
                        Daniel B.
                    </p>
                    <p class="text-xs text-ink/50 mt-1">
                        Informatics Student
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-7 shadow-card border border-sand/50">
            <p class="text-ink/75 leading-relaxed italic">
                "Saya bisa melihat status peminjaman dan denda secara langsung."
            </p>

            <div class="mt-6 flex items-center gap-3.5">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-burgundy/10 text-burgundy font-semibold text-sm">
                    MT
                </div>
                <div>
                    <p class="font-semibold text-maroon text-sm leading-none">
                        Michelle T.
                    </p>
                    <p class="text-xs text-ink/50 mt-1">
                        Business Student
                    </p>
                </div>
            </div>
        </div>

        <div class="rounded-3xl bg-white p-7 shadow-card border border-sand/50">
            <p class="text-ink/75 leading-relaxed italic">
                "Interface-nya bersih dan sangat mudah digunakan."
            </p>

            <div class="mt-6 flex items-center gap-3.5">
                <div class="h-10 w-10 flex items-center justify-center rounded-full bg-burgundy/10 text-burgundy font-semibold text-sm">
                    KA
                </div>
                <div>
                    <p class="font-semibold text-maroon text-sm leading-none">
                        Kevin A.
                    </p>
                    <p class="text-xs text-ink/50 mt-1">
                        Engineering Student
                    </p>
                </div>
            </div>
        </div>

    </div>

  </section>

  {{-- ===================== FOOTER ===================== --}}
  <footer class="bg-burgundy-800 text-cream/70">
    <div class="mx-auto max-w-7xl px-6 py-12 lg:px-8">
      <div class="flex flex-col items-start justify-between gap-6 sm:flex-row sm:items-center">
        <div class="flex items-center gap-2.5">
          <span class="flex h-8 w-8 items-center justify-center rounded-lg bg-cream text-maroon font-display text-lg font-semibold">LH</span>
          <span class="font-display text-xl font-semibold text-cream">LibHub</span>
        </div>
        <p class="text-sm">Sistem Informasi Peminjaman Buku Perpustakaan</p>
      </div>
      <div class="mt-8 border-t border-cream/10 pt-6 text-xs">
        © {{ date('Y') }} LibHub · Dibangun dengan Laravel & Tailwind CSS · Kelompok 3
      </div>
    </div>
  </footer>

</body>
</html>
