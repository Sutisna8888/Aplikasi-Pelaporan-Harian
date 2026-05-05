<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Bukti Foto Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .header {
            margin-bottom: 20px;
        }
        .header h3 {
            margin: 0;
            text-transform: uppercase;
        }
        .header p {
            margin: 5px 0;
            color: #555;
        }
        .photo-container {
            margin-bottom: 30px;
        }
        .photo-title {
            font-weight: bold;
            margin-bottom: 10px;
        }
        .photo-img {
            max-width: 100%;
            max-height: 400px;
            border: 2px solid #ccc;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>
<body>

    <div class="header">
        <h3>Bukti Pelaporan</h3>
        <p>Nama: {{ $laporan->user->name ?? $laporan->user->username }} | Tanggal: {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</p>
        <p>Kegiatan: {{ $laporan->kegiatan->nama_kegiatan ?? '-' }}</p>
    </div>

    @if($laporan->foto_mulai && file_exists(public_path('storage/' . $laporan->foto_mulai)))
    <div class="photo-container">
        <div class="photo-title">Foto Mulai</div>
        <img src="{{ public_path('storage/' . $laporan->foto_mulai) }}" class="photo-img">
    </div>
    @endif

    @if($laporan->foto_selesai && file_exists(public_path('storage/' . $laporan->foto_selesai)))
    <div class="photo-container">
        <div class="photo-title">Foto Selesai</div>
        <img src="{{ public_path('storage/' . $laporan->foto_selesai) }}" class="photo-img">
    </div>
    @endif

    @if(!$laporan->foto_mulai && !$laporan->foto_selesai)
    <div style="margin-top: 50px; color: #888;">
        <i>Tidak ada bukti foto yang dilampirkan pada laporan ini.</i>
    </div>
    @endif

</body>
</html>
