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
      Halo <strong>{{ $booking->user->nama_lengkap }}</strong>,
    </p>
    <p style="margin:0 0 20px;color:#541A19;">
      Booking kamu berhasil! Berikut detail pemesanan buku:
    </p>

    <div style="background:#F2E2D3;border-radius:12px;padding:20px 24px;margin-bottom:24px;">
      <table style="width:100%;border-collapse:collapse;font-size:14px;color:#2A1316;">
        <tr>
          <td style="padding:6px 0;color:#6b4c42;width:45%;">Kode Booking</td>
          <td style="padding:6px 0;font-weight:bold;font-size:16px;letter-spacing:2px;color:#800B38;">
            {{ $booking->kode_booking }}
          </td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Judul Buku</td>
          <td style="padding:6px 0;font-weight:600;">{{ $booking->book->judul }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Tanggal Ambil</td>
          <td style="padding:6px 0;">{{ $booking->tanggal_booking->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Rencana Kembali</td>
          <td style="padding:6px 0;">{{ $booking->tanggal_rencana_kembali->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
          <td style="padding:6px 0;color:#6b4c42;">Booking Hangus</td>
          <td style="padding:6px 0;">{{ $booking->tanggal_expired->translatedFormat('d F Y') }}</td>
        </tr>
      </table>
    </div>

    <p style="margin:0 0 8px;color:#541A19;font-size:14px;">
      📌 <strong>Tunjukkan kode booking di atas</strong> ke admin saat mengambil buku.
    </p>
    <p style="margin:0;color:#6b5b53;font-size:13px;">
      Kode berlaku hingga <strong>{{ $booking->tanggal_expired->translatedFormat('d F Y') }}</strong>.
    </p>
  </div>

  <div style="background:#541A19;padding:16px 32px;text-align:center;">
    <p style="margin:0;color:#DDC3AA;font-size:12px;">© {{ date('Y') }} LibHub</p>
  </div>

</div>
</body>
</html>