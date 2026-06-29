<!DOCTYPE html>
<html lang="id">
<head><meta charset="UTF-8"></head>
<body style="margin:0;padding:0;background:#FBF7F0;font-family:Arial,sans-serif;">
<div style="max-width:520px;margin:32px auto;background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(0,0,0,.08);">

  <div style="background:#800B38;padding:28px 32px;">
    <h1 style="margin:0;color:#fff;font-size:22px;letter-spacing:1px;">📚 LibHub</h1>
  </div>

  <div style="padding:32px;">
    <p style="margin:0 0 16px;color:#2A1316;font-size:16px;">
      Halo <strong>{{ $loan->user->nama_lengkap }}</strong>,
    </p>

    @php $hariLagi = (int) now()->startOfDay()->diffInDays($loan->tanggal_tenggat->startOfDay(), false); @endphp

    @if($hariLagi <= 0)
      <p style="margin:0 0 20px;color:#800B38;font-weight:bold;font-size:15px;">
        ⚠️ Buku yang kamu pinjam harus dikembalikan <u>hari ini</u>!
      </p>
    @elseif($hariLagi === 1)
      <p style="margin:0 0 20px;color:#800B38;font-weight:bold;font-size:15px;">
        ⚠️ Buku yang kamu pinjam harus dikembalikan <u>besok</u>!
      </p>
    @else
      <p style="margin:0 0 20px;color:#541A19;font-size:15px;">
        📅 Buku pinjaman kamu akan jatuh tempo dalam <strong>{{ $hariLagi }} hari lagi</strong>.
      </p>
    @endif

    <div style="background:#F2E2D3;border-radius:12px;padding:20px 24px;margin-bottom:24px;">
      <table style="width:100%;border-collapse:collapse;font-size:14px;color:#2A1316;">
        @foreach($loan->items as $item)
        <tr>
          <td style="padding:6px 0;color:#6b4c42;width:40%;">Judul Buku</td>
          <td style="padding:6px 0;font-weight:600;">{{ $item->book->judul }}</td>
        </tr>
        @endforeach
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Tanggal Pinjam</td>
          <td style="padding:6px 0;">{{ $loan->tanggal_pinjam->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Jatuh Tempo</td>
          <td style="padding:6px 0;font-weight:bold;color:#800B38;">
            {{ $loan->tanggal_tenggat->translatedFormat('d F Y') }}
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Denda Keterlambatan</td>
          <td style="padding:6px 0;">Rp {{ number_format(config('perpustakaan.denda_per_hari'), 0, ',', '.') }} / hari / buku</td>
        </tr>
      </table>
    </div>

    <p style="margin:0;color:#6b5b53;font-size:13px;">
      Kembalikan buku tepat waktu untuk menghindari denda.
    </p>
  </div>

  <div style="background:#541A19;padding:16px 32px;text-align:center;">
    <p style="margin:0;color:#DDC3AA;font-size:12px;">© {{ date('Y') }} LibHub</p>
  </div>

</div>
</body>
</html>