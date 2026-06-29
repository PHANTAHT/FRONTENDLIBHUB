<!DOCTYPE html>
<html lang="id">
<head>@include('partials.head')</head>
<body class="min-h-screen bg-cream-50 font-sans text-ink">

<header class="sticky top-0 z-30 border-b border-sand/60 bg-cream-50/85 backdrop-blur">
  <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-3.5">
    <a href="{{ route('member.home') }}" class="flex items-center gap-3">

        <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-gradient-to-br from-burgundy to-maroon text-white font-bold">
            LH
        </div>

        <div>
            <h1 class="font-display text-xl font-bold text-maroon">
                LibHub
            </h1>

            <p class="text-[11px] text-ink/40">
                Smart Library Platform
            </p>
        </div>

    </a>

    <nav class="hidden items-center gap-1 md:flex">
      @php $nav = ['member.home'=>'Home','member.books'=>'Buku','member.loans'=>'Pinjaman','member.bookings'=>'Booking']; @endphp
      @foreach($nav as $route => $label)
        <a href="{{ route($route) }}"
           class="rounded-full px-4 py-2 text-sm font-medium transition {{ request()->routeIs($route) ? 'bg-burgundy text-cream' : 'text-maroon/70 hover:bg-cream-100 hover:text-burgundy' }}">
          {{ $label }}
        </a>
      @endforeach
    </nav>

    <div class="flex items-center gap-3">
      <div class="hidden text-right sm:block">
        <p class="text-sm font-semibold leading-tight text-maroon">{{ auth()->user()->nama_lengkap }}</p>
        <p class="text-xs text-ink/50">Anggota</p>
      </div>
      <span class="flex h-9 w-9 items-center justify-center rounded-full bg-sand text-sm font-semibold text-maroon">{{ auth()->user()->initials() }}</span>
      <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button class="rounded-full border border-maroon/15 bg-white p-2 text-maroon/60 hover:text-burgundy transition" title="Keluar">
          <svg class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9"/></svg>
        </button>
      </form>
    </div>
  </div>

  {{-- mobile nav --}}
  <nav class="flex items-center gap-1 overflow-x-auto border-t border-sand/60 px-4 py-2 md:hidden">
    @foreach($nav as $route => $label)
      <a href="{{ route($route) }}"
         class="whitespace-nowrap rounded-full px-3.5 py-1.5 text-sm font-medium {{ request()->routeIs($route) ? 'bg-burgundy text-cream' : 'text-maroon/70' }}">{{ $label }}</a>
    @endforeach
  </nav>
</header>

<main class="mx-auto max-w-6xl px-6 py-8">
  @include('partials.flash')
  @yield('content')
</main>
@stack('scripts')
</body>
</html>
