@extends('layouts.member')
@section('title','Booking Saya — Pustaka')
@section('content')

<h1 class="mb-6 font-display text-3xl font-semibold text-maroon">Booking saya</h1>

<div class="space-y-3">
  @forelse($bookings as $booking)
    <div class="flex flex-col gap-4 rounded-2xl border border-sand/70 bg-white p-5 shadow-card sm:flex-row sm:items-center">
      <div class="h-20 w-14 shrink-0 overflow-hidden rounded-lg bg-sand/40">
        <img src="{{ $booking->book->coverUrl() }}" alt="" class="h-full w-full object-cover">
      </div>
      <div class="flex-1">
        <p class="font-semibold text-maroon">{{ $booking->book->judul }}</p>
        <p class="text-sm text-ink/50">{{ $booking->book->pengarang }}</p>
        <p class="mt-1 text-xs text-ink/40">
          Pinjam: <span class="font-medium text-maroon">{{ $booking->tanggal_booking->translatedFormat('d M Y') }}</span> 
          s/d <span class="font-medium text-maroon">{{ $booking->tanggal_rencana_kembali->translatedFormat('d M Y') }}</span>
          · Batas Ambil: <span class="text-ink/60 font-medium">{{ $booking->tanggal_expired->translatedFormat('d M Y') }}</span>
        </p>
        
        @if($booking->status === 'pending_payment')
          <div x-data="{
                 timeLeft: '',
                 calculateTimeLeft() {
                     const expireTime = new Date('{{ $booking->created_at->toIso8601String() }}').getTime() + (30 * 60 * 1000);
                     const now = new Date().getTime();
                     const diff = expireTime - now;
                     if (diff <= 0) {
                         this.timeLeft = 'Expired';
                         return;
                     }
                     const minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                     const seconds = Math.floor((diff % (1000 * 60)) / 1000);
                     this.timeLeft = minutes + 'm ' + seconds + 's';
                 }
               }"
               x-init="calculateTimeLeft(); setInterval(() => calculateTimeLeft(), 1000)"
               class="mt-1.5 flex items-center gap-1.5 text-xs text-amber-600 font-medium bg-amber-50 border border-amber-200/50 rounded-lg px-2.5 py-1 w-max">
            <svg class="h-3.5 w-3.5 text-amber-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Sisa waktu bayar: <span x-text="timeLeft" class="font-mono"></span>
          </div>
        @endif
      </div>
      <div class="text-center">
        <p class="text-xs text-ink/40">Kode booking</p>
        <p class="font-display text-2xl font-semibold tracking-wider text-burgundy">{{ $booking->kode_booking }}</p>
      </div>
      <div class="flex items-center gap-3">
        @php
          $bmap = [
            'pending_payment' => ['Menunggu Pembayaran', 'bg-yellow-100 text-yellow-700'],
            'reserved'=>['Aktif','bg-amber-100 text-amber-700'],
            'completed'=>['Selesai','bg-green-100 text-green-700'],
            'cancelled'=>['Dibatalkan','bg-gray-100 text-gray-600'],
            'expired'=>['Hangus','bg-red-100 text-red-600'],
          ];
          [$lbl,$cls] = $bmap[$booking->status];
        @endphp
        <span class="rounded-full px-3 py-1 text-xs font-semibold {{ $cls }}">{{ $lbl }}</span>
        
        @if($booking->status === 'pending_payment')
          <a href="{{ route('member.booking.edit', $booking->id) }}"
             class="rounded-lg border border-sand bg-white px-3 py-1.5 text-xs font-semibold text-ink/75 hover:bg-cream-50 transition">
              Edit
          </a>
        @endif

        @if($booking->status === 'pending_payment')
            <a href="{{ route('member.booking.pay', $booking) }}"
              class="rounded-lg border border-burgundy bg-burgundy px-3 py-1.5 text-xs font-semibold text-white hover:bg-maroon transition">
                Bayar Sekarang
            </a>
        @endif
        @if(in_array($booking->status, ['pending_payment', 'reserved']))
          <form method="POST" action="{{ route('member.booking.cancel', $booking) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan booking ini?')">
            @csrf @method('PATCH')
            <button class="rounded-lg border border-red-200 px-3 py-1.5 text-xs font-semibold text-red-600 hover:bg-red-50 transition">Batalkan</button>
          </form>
        @endif
      </div>
    </div>
  @empty
    <p class="rounded-2xl border border-dashed border-sand bg-cream-100 p-10 text-center text-sm text-ink/50">
      Belum ada booking. <a href="{{ route('member.books') }}" class="font-semibold text-burgundy hover:underline">Jelajahi katalog</a> untuk mulai booking.
    </p>
  @endforelse
</div>

@endsection
