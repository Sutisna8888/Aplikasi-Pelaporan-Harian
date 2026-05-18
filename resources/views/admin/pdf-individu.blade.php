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
            margin-bottom: 10px;
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
            margin-bottom: 15px;
        }

        .photo-title {
            font-weight: bold;
            margin-bottom: 5px;
        }

        .photo-img {
            height: 310px;
            width: auto;
            max-width: 100%;
            border: 2px solid #ccc;
            display: block;
            margin: 0 auto;
        }
    </style>
</head>

<body>

    <table style="width: 100%; margin-bottom: 20px;">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: middle;">
                @if(file_exists(public_path('images/logo-bps.png')))
                    <img src="{{ public_path('images/logo-bps.png') }}" alt="Logo" style="height: 60px;">
                @endif
            </td>
            <td style="width: 60%; text-align: center; vertical-align: middle;">
                <h3 style="margin: 0; text-transform: uppercase;">Bukti Laporan</h3>
            </td>
            <td style="width: 20%;"></td>
        </tr>
    </table>

    <div class="header">
        <table
            style="margin: 0 auto; text-align: left; width: 80%; max-width: 500px; font-size: 14px; line-height: 1.6;">
            <tr>
                <td style="width: 80px;"><strong>Nama</strong></td>
                <td style="width: 10px;">:</td>
                <td>{{ $laporan->user->name ?? $laporan->user->username }}</td>
            </tr>
            <tr>
                <td><strong>Tanggal</strong></td>
                <td>:</td>
                <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}</td>
            </tr>
            <tr>
                <td><strong>Waktu</strong></td>
                <td>:</td>
                <td>{{ substr($laporan->jam_mulai, 0, 5) }} - {{ substr($laporan->jam_selesai, 0, 5) }} WIB</td>
            </tr>
            <tr>
                <td><strong>Kegiatan</strong></td>
                <td>:</td>
                <td>{{ $laporan->kegiatan->nama_kegiatan ?? '-' }}</td>
            </tr>
        </table>
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