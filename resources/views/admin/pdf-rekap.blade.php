<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Rekap Laporan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            margin: 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
        }

        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .info-table td:first-child {
            width: 150px;
        }

        .info-table td:nth-child(2) {
            width: 10px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }

        .data-table th,
        .data-table td {
            border: 1px solid #000;
            padding: 8px;
            text-align: center;
            vertical-align: middle;
        }

        .data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .data-table td.text-left {
            text-align: left;
        }

        .signature-area {
            width: 100%;
            margin-top: 40px;
        }

        .signature-box {
            float: right;
            width: 250px;
            text-align: left;
        }

        .bukti-img {
            max-width: 50px;
            max-height: 50px;
            object-fit: cover;
            margin: 2px;
        }
    </style>
</head>

<body>

    <table style="width: 100%; margin-bottom: 30px;">
        <tr>
            <td style="width: 20%; text-align: left; vertical-align: top;">
                <img src="{{ public_path('images/logo-laporan-bps.png') }}" alt="Logo" style="height: 100px;">
            </td>
            <td style="width: 40%; text-align: center; vertical-align: top; padding-top: 30px;">
                <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-transform: uppercase;">{{ $judul }}</h2>
                <p style="margin: 5px 0 0 0;">
                    @if(isset($judul) && $judul == 'LAPORAN HARIAN')
                        Tanggal :
                    @else
                        Periode :
                    @endif
                    {{ $periode }}
                </p>
            </td>
            <td style="width: 20%;"></td>
        </tr>
    </table>

    <table class="info-table">
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $user->name ?? $user->username }}</td>
        </tr>
        <tr>
            <td>Nomor Induk Pegawai</td>
            <td>:</td>
            <td>{{ $user->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $user->jabatan ?? '-' }}</td>
        </tr>
    </table>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 13%;">Tanggal</th>
                <th style="width: 12%;">Waktu</th>
                <th style="width: 20%;">Kegiatan</th>
                <th style="width: 20%;">Deskripsi</th>
                <th style="width: 15%;">Lokasi</th>
                <th style="width: 15%;">Bukti</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d M Y') }}</td>
                    <td>
                        {{ substr($laporan->jam_mulai, 0, 5) }} -
                        <br>
                        {{ substr($laporan->jam_selesai, 0, 5) }}
                    </td>
                    <td class="text-left">{{ $laporan->kegiatan->nama_kegiatan ?? '-' }}</td>
                    <td class="text-left">{{ $laporan->deskripsi }}</td>
                    <td class="text-left">{{ $laporan->lokasi_teks ?: '-' }}</td>
                    <td align="center" valign="middle">
                        <table border="0" cellpadding="0" cellspacing="0"
                            style="margin: 0 auto; width: auto; border: none;">
                            <tr>
                                @if($laporan->foto_mulai && file_exists(public_path('storage/' . $laporan->foto_mulai)))
                                    <td align="center" valign="middle" style="border: none; padding: 0 2px;">
                                        <img src="{{ public_path('storage/' . $laporan->foto_mulai) }}" class="bukti-img">
                                    </td>
                                @endif
                                @if($laporan->foto_selesai && file_exists(public_path('storage/' . $laporan->foto_selesai)))
                                    <td align="center" valign="middle" style="border: none; padding: 0 2px;">
                                        <img src="{{ public_path('storage/' . $laporan->foto_selesai) }}" class="bukti-img">
                                    </td>
                                @endif
                            </tr>
                        </table>
                    </td>
                </tr>
            @endforeach
            @if($laporans->count() == 0)
                <tr>
                    <td colspan="7">Tidak ada laporan pada periode ini.</td>
                </tr>
            @endif
        </tbody>
    </table>

    <div class="signature-area">
        <div class="signature-box">
            <p>Sukabumi, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
            <br>
            @if($user->ttd && file_exists(public_path('storage/' . $user->ttd)))
                <img src="{{ public_path('storage/' . $user->ttd) }}"
                    style="max-width: 100px; max-height: 100px; margin-bottom: 5px;">
            @else
                <br><br><br><br>
            @endif
            <p style="margin:0; font-weight:bold;">{{ $user->name ?? $user->username }}</p>
            <p style="margin:0;">NIP: {{ $user->nip ?? '-' }}</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>

</html>