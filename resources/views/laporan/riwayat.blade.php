@extends('layouts.master')

@section('title', 'Riwayat Laporan Hari Ini - ALPHA')
@section('header_title', 'Riwayat Laporan')

@section('content')

<div class="kotak-pembungkus section-panel">
    
    <div class="filter-area">
        <h4 class="teks-tanggal-hari-ini">
            {{ \Carbon\Carbon::today()->locale('id')->translatedFormat('l, d F Y') }}
        </h4>
    </div>

    <div class="wrapper-tabel">
        <table class="table-custom">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Waktu</th>
                    <th>Kegiatan</th>
                    <th>Lokasi</th>
                    <th>Bukti</th>
                </tr>
            </thead>
            <tbody>
                @forelse($laporans as $index => $laporan)
                <tr>
                    <td style="text-align: center; color: #888;">{{ $index + 1 }}</td>
                    <td style="white-space: nowrap;">
                        {{ \Carbon\Carbon::parse($laporan->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($laporan->jam_selesai)->format('H:i') }}
                    </td>
                    <td>{{ $laporan->kegiatan->nama_kegiatan }}</td>
                    <td>{{ $laporan->lokasi_teks }}</td>
                    <td style="text-align: center;">
                        <div style="display: flex; gap: 8px; justify-content: center;">
                            <button onclick="showImage('{{ asset('storage/' . $laporan->foto_mulai) }}', 'Bukti Mulai')" class="btn-bukti">
                                <i class="fas fa-image"></i>
                            </button>
                            <button onclick="showImage('{{ asset('storage/' . $laporan->foto_selesai) }}', 'Bukti Selesai')" class="btn-bukti">
                                <i class="fas fa-image"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align: center; padding: 40px 15px; color: #999;">
                        <i class="fas fa-info-circle" style="display: block; font-size: 2.5rem; margin-bottom: 15px; color: #cbd5e1;"></i>
                        Belum ada laporan yang dibuat hari ini.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div id="imageModal" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); align-items: center; justify-content: center; padding: 20px; box-sizing: border-box;">
    <div style="background: #fff; padding: 20px; border-radius: 12px; width: 100%; max-width: 500px; position: relative; text-align: center;">
        <span onclick="closeModal()" style="position: absolute; top: 10px; right: 20px; font-size: 2rem; cursor: pointer; color: #333; line-height: 1;">&times;</span>
        <h4 id="modalTitle" style="margin-top: 0; margin-bottom: 15px; color: #333; text-align: left;"></h4>
        <img id="modalImg" src="" style="max-width: 100%; max-height: 70vh; object-fit: contain; border-radius: 8px; box-shadow: 0 4px 12px rgba(0,0,0,0.1);">
    </div>
</div>

<script>
    function showImage(src, title) {
        document.getElementById('modalImg').src = src;
        document.getElementById('modalTitle').innerText = title;
        document.getElementById('imageModal').style.display = 'flex';
    }

    function closeModal() {
        document.getElementById('imageModal').style.display = 'none';
    }

    window.onclick = function(event) {
        let modal = document.getElementById('imageModal');
        if (event.target == modal) {
            closeModal();
        }
    }
</script>

<style>
    .kotak-pembungkus {
        padding: 30px;
        border-radius: 15px;
        background: #fff;
        box-shadow: 0 4px 6px rgba(0,0,0,0.05);
    }
    
    .filter-area {
        margin-bottom: 20px; display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
    }

    .teks-label-tanggal {
        font-size: 1rem; color: #444; font-weight: 500; white-space: nowrap;
    }

    .teks-tanggal-hari-ini {
        margin: 0;
        padding: 0 0 10px 0; /* Memberi sedikit jarak bawah sebelum tabel */
        color: #1f2937; /* Warna gelap yang elegan */
        font-weight: bold;
        font-size: 1.25rem; /* Ukuran yang pas untuk judul (sekitar 20px) */
    }

    .wrapper-tabel {
        overflow-x: auto; -webkit-overflow-scrolling: touch; width: 100%; 
        border-radius: 5px; border: 1px solid #eee;
    }
    .table-custom {
        width: 100%; border-collapse: separate; border-spacing: 0; min-width: 500px;
    }
    .table-custom thead th {
        background: #2b3035; color: #fff; padding: 12px 15px; text-align: left; white-space: nowrap;
    }
    .table-custom thead th:first-child { border-top-left-radius: 5px; text-align: center; }
    .table-custom thead th:last-child { border-top-right-radius: 5px; text-align: center; }
    .table-custom tbody td {
        padding: 12px 15px; border-bottom: 1px solid #eee; color: #444;
    }
    .table-custom tbody tr:hover {
        background-color: #fafafa; 
    }

    /* Mengatur Lebar Spesifik Setiap Kolom */
    .table-custom th:nth-child(1) { width: 5%; }  /* Kolom No  */
    .table-custom th:nth-child(2) { width: 15%; } /* Kolom Waktu  */
    .table-custom th:nth-child(5) { width: 15%; } /* Kolom Bukti */

    .btn-bukti {
        background: #eff6ff; border: 1px solid #bfdbfe; border-radius: 6px; 
        padding: 6px 10px; cursor: pointer; color: #3b82f6; transition: 0.2s;
    }
    .btn-bukti:hover {
        background: #dbeafe; 
    }
    /* --- 4. MEDIA QUERY (LAYAR HP) --- */
    @media (max-width: 768px) {
        .kotak-pembungkus { padding: 15px; border-radius: 12px; }

        .teks-tanggal-hari-ini {
        margin: 0;
        color: #1f2937; 
        font-weight: bold;
        font-size: 1rem; 
        margin-bottom: -15px; /* Memberi jarak bawah sebelum tabel */
        }

        /* Mengubah kelengkungan tabel */
        .wrapper-tabel {
            border-radius: 4px; 
        }
        .table-custom thead th:first-child { 
            border-top-left-radius: 4px; 
        }
        .table-custom thead th:last-child { 
            border-top-right-radius: 4px; 
        }

        /*Mengecilkan teks di dalam tabel */
        .table-custom th, .table-custom td {
            font-size: 0.77rem; 
            padding: 10px 8px;  
        }
       
        .table-custem 
        /* 2. Mengecilkan ukuran icon/tombol bukti */
        .btn-bukti {
            font-size: 0.90rem;
            padding: 5px 8px;
        }
    }
</style>
@endsection