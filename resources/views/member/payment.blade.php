@extends('layouts.member')

@section('content')
<div class="max-w-lg mx-auto py-10 px-4">
  <h2 class="text-2xl font-bold text-[#800B38] mb-6">Pembayaran Booking</h2>

  {{-- Detail --}}
  <div class="bg-[#F2E2D3] rounded-2xl p-6 mb-6">
    <p class="font-semibold text-[#2A1316] mb-4">{{ $booking->book->judul }}</p>
    <table class="w-full text-sm text-[#541A19]">
      <tr>
        <td class="py-1">Tanggal Ambil</td>
        <td class="py-1 font-medium">{{ $booking->tanggal_booking->translatedFormat('d F Y') }}</td>
      </tr>
      <tr>
        <td class="py-1">Rencana Kembali</td>
        <td class="py-1 font-medium">{{ $booking->tanggal_rencana_kembali->translatedFormat('d F Y') }}</td>
      </tr>
      <tr class="border-t border-[#DDC3AA]">
        <td class="pt-3 py-1">Biaya Sewa</td>
        <td class="pt-3 py-1">Rp {{ number_format($biayaSewa, 0, ',', '.') }}</td>
      </tr>
      <tr>
        <td class="py-1">Deposit</td>
        <td class="py-1">Rp {{ number_format($deposit, 0, ',', '.') }}</td>
      </tr>
      <tr class="border-t border-[#DDC3AA] font-bold text-[#800B38]">
        <td class="pt-3 py-1">Total</td>
        <td class="pt-3 py-1">Rp {{ number_format($total, 0, ',', '.') }}</td>
      </tr>
    </table>
  </div>

  <p class="text-xs text-gray-500 mb-4">
    ⏱ Selesaikan pembayaran dalam <strong>30 menit</strong> sebelum transaksi expired.
  </p>

  <button id="pay-button"
    class="w-full bg-[#800B38] hover:bg-[#541A19] text-white font-semibold py-3 rounded-xl transition">
    Bayar Sekarang
  </button>
</div>

@push('scripts')
<script src="{{ config('services.midtrans.snap_url') }}" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
<script>
  document.getElementById('pay-button').addEventListener('click', function () {
    snap.pay('{{ $snapToken }}', {
      onSuccess: function () {
        window.location.href = '{{ route('payment.finish', $booking) }}';
      },
      onPending: function () {
        window.location.href = '{{ route('payment.finish', $booking) }}';
      },
      onError: function () {
        window.location.href = '{{ route('payment.finish', $booking) }}';
      },
      onClose: function () {
        // user tutup popup tanpa bayar — tidak redirect
      },
    });
  });
</script>
@endpush
@endsection