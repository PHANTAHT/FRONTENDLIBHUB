<!DOCTYPE html>
<html lang="id">
<head>@include('partials.head')</head>
<body class="min-h-screen bg-cream-50 font-sans text-ink">
<div x-data="{ open: false }" class="flex min-h-screen">

  {{-- ============ Sidebar ============ --}}
  <aside class="fixed inset-y-0 left-0 z-40 w-64 -translate-x-full transform bg-maroon text-cream/80 transition-transform duration-200 lg:static lg:translate-x-0"
         :class="open ? 'translate-x-0' : '-translate-x-full'">
    <div class="flex h-full flex-col">
      <div class="flex items-center gap-2.5 px-6 py-5">
        <span class="flex h-9 w-9 items-center justify-center rounded-lg bg-burgundy text-cream font-display text-lg font-semibold">LH</span>
        <div>
          <p class="font-display text-lg font-semibold leading-none text-cream">LibHub</p>
          <p class="text-[11px] uppercase tracking-wider text-cream/45">Panel Admin</p>
        </div>
      </div>

      <nav class="flex-1 space-y-0.5 px-3 py-2">
        @php
          $items = [
            ['admin.dashboard', 'Dashboard', 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6'],
            ['admin.loans.index', 'Transaksi', 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4'],
            ['admin.books.index', 'Buku', 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253'],
            ['admin.categories.index', 'Kategori', 'M7 7h.01M7 3h5a1.99 1.99 0 011.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.99 1.99 0 013 12V7a4 4 0 014-4z'],
            ['admin.users.index', 'Anggota', 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['admin.loans.history', 'Riwayat', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
          ];
        @endphp
        @foreach($items as [$route, $label, $icon])
          @php $active = request()->routeIs($route) || ($route==='admin.loans.index' && request()->routeIs('admin.loans.index')); @endphp
          <a href="{{ route($route) }}"
             class="flex items-center gap-3 rounded-xl px-3 py-2.5 text-sm font-medium transition {{ $active ? 'bg-cream/12 text-cream' : 'text-cream/65 hover:bg-cream/8 hover:text-cream' }}">
            <svg class="h-5 w-5 shrink-0" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="{{ $icon }}"/></svg>
            {{ $label }}
          </a>
        @endforeach
      </nav>

      <div class="border-t border-cream/10 p-3">
        <div class="mb-2 flex items-center gap-3 px-2">
          <span class="flex h-9 w-9 items-center justify-center rounded-full bg-burgundy text-sm font-semibold text-cream">{{ auth()->user()->initials() }}</span>
          <div class="min-w-0">
            <p class="truncate text-sm font-semibold text-cream">{{ auth()->user()->nama_lengkap }}</p>
            <p class="text-[11px] text-cream/45">Administrator</p>
          </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
          @csrf
          <button class="flex w-full items-center gap-2 rounded-xl px-3 py-2 text-sm font-medium text-cream/65 transition hover:bg-cream/8 hover:text-cream">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.7" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
            Keluar
          </button>
        </form>
      </div>
    </div>
  </aside>

  {{-- backdrop (mobile) --}}
  <div x-show="open" @click="open=false" x-cloak class="fixed inset-0 z-30 bg-ink/40 lg:hidden"></div>

  {{-- ============ Main ============ --}}
  <div class="flex min-w-0 flex-1 flex-col">
    <header class="sticky top-0 z-20 flex items-center gap-3 border-b border-sand/60 bg-cream-50/85 px-5 py-3.5 backdrop-blur lg:px-8">
      <button @click="open=true" class="rounded-lg p-1.5 text-maroon lg:hidden">
        <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
      </button>
      <div class="flex-1 text-center">
        <h1 class="font-display text-xl font-semibold text-maroon">@yield('page-title', 'Dashboard')</h1>
        @hasSection('page-sub')<p class="text-sm text-ink/55">@yield('page-sub')</p>@endif
      </div>
      @yield('page-actions')
    </header>

    <main class="flex-1 px-5 py-6 lg:px-8 lg:py-8">
      @include('partials.flash')
      @yield('content')
    </main>
  </div>
</div>

{{-- Global Confirmation Modal --}}
<div x-data="{ 
       show: false, 
       message: '', 
       action: null,
       openModal(msg, act) {
           this.message = msg;
           this.action = act;
           this.show = true;
       },
       confirm() {
           if (this.action) this.action();
           this.show = false;
       }
     }"
     @trigger-confirm.window="openModal($event.detail.message, $event.detail.action)"
     x-show="show"
     x-cloak
     class="relative z-50"
     aria-labelledby="modal-title" 
     role="dialog" 
     aria-modal="true">
  
  <!-- Backdrop -->
  <div x-show="show" 
       x-transition:enter="ease-out duration-300"
       x-transition:enter-start="opacity-0"
       x-transition:enter-end="opacity-100"
       x-transition:leave="ease-in duration-200"
       x-transition:leave-start="opacity-100"
       x-transition:leave-end="opacity-0"
       class="fixed inset-0 bg-ink/40 backdrop-blur-sm transition-opacity"></div>

  <!-- Modal content -->
  <div class="fixed inset-0 z-10 w-screen overflow-y-auto">
    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
      <div x-show="show"
           x-transition:enter="ease-out duration-300"
           x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave="ease-in duration-200"
           x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
           x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
           @click.outside="show = false"
           class="relative transform overflow-hidden rounded-2xl bg-white px-4 pb-4 pt-5 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg sm:p-6">
        
        <div class="sm:flex sm:items-start">
          <!-- Question Icon -->
          <div class="mx-auto flex h-12 w-12 shrink-0 items-center justify-center rounded-full bg-burgundy/10 text-burgundy sm:mx-0 sm:h-10 sm:w-10">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" stroke-width="1.8" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9 5.25h.008v.008H12v-.008z"/>
            </svg>
          </div>
          <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
            <h3 class="font-display text-lg font-semibold leading-6 text-maroon" id="modal-title">
              Konfirmasi Tindakan
            </h3>
            <div class="mt-2">
              <p class="text-sm text-ink/65" x-text="message"></p>
            </div>
          </div>
        </div>
        
        <div class="mt-6 sm:flex sm:flex-row-reverse gap-2">
          <button type="button" 
                  @click="confirm()"
                  class="inline-flex w-full justify-center rounded-xl bg-burgundy px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-burgundy-700 sm:w-auto transition-colors">
            Ya, Lanjutkan
          </button>
          <button type="button" 
                  @click="show = false"
                  class="mt-3 inline-flex w-full justify-center rounded-xl border border-sand bg-white px-4 py-2.5 text-sm font-semibold text-ink/70 hover:bg-cream-50 sm:mt-0 sm:w-auto transition-colors">
            Batal
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    // Intercept form submissions containing confirm() in their inline handler
    document.querySelectorAll('form').forEach(form => {
      const onsubmitAttr = form.getAttribute('onsubmit');
      if (onsubmitAttr && onsubmitAttr.includes('confirm(')) {
        const match = onsubmitAttr.match(/confirm\(['"](.+?)['"]\)/);
        if (match && match[1]) {
          const message = match[1];
          form.removeAttribute('onsubmit');
          form.addEventListener('submit', (e) => {
            e.preventDefault();
            window.dispatchEvent(new CustomEvent('trigger-confirm', {
              detail: {
                message: message,
                action: () => {
                  HTMLFormElement.prototype.submit.call(form);
                }
              }
            }));
          });
        }
      }
    });

    // Intercept button/link clicks containing confirm() in their inline handler
    document.querySelectorAll('[onclick*="confirm("]').forEach(el => {
      const onclickAttr = el.getAttribute('onclick');
      const match = onclickAttr.match(/confirm\(['"](.+?)['"]\)/);
      if (match && match[1]) {
        const message = match[1];
        el.removeAttribute('onclick');
        el.addEventListener('click', (e) => {
          e.preventDefault();
          window.dispatchEvent(new CustomEvent('trigger-confirm', {
            detail: {
              message: message,
              action: () => {
                if (el.tagName === 'A' && el.href) {
                  window.location.href = el.href;
                } else {
                  const parentForm = el.closest('form');
                  if (parentForm) {
                    HTMLFormElement.prototype.submit.call(parentForm);
                  }
                }
              }
            }
          }));
        });
      }
    });
  });
</script>
</body>
</html>
