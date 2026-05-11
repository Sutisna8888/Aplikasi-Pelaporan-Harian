@extends('layouts.master')

@section('title', 'Dashboard Pegawai - ALPHA')
@section('header_title', 'Dashboard')

@section('content')

    <div class="stats-grid-top">
        <div class="stat-card">
            <h3>{{ $laporanHariIni }}</h3>
            <p>Laporan hari ini</p>
        </div>
        <div class="stat-card">
            <h3>{{ $penggunaanWaktu }}</h3>
            <p>Penggunaan waktu</p>
        </div>

    </div>

    <div class="stats-grid-bottom">
        <div class="stat-card">
            <h3>{{ $laporanMingguIni }}</h3>
            <p>Laporan minggu ini</p>
        </div>
        <div class="stat-card">
            <h3>{{ $laporanBulanIni }}</h3>
            <p>Laporan bulan ini</p>
        </div>
        <div class="stat-card">
            <h3>{{ $laporanTahunIni }}</h3>
            <p>Laporan tahun ini</p>
        </div>
    </div>

    <div class="card-panel">
        <h3 class="panel-title" style="margin-bottom: 20px;">Grafik Laporan Bulanan</h3>

        <div class="chart-container">
            <canvas id="laporanChart"></canvas>
        </div>
    </div>

    <script type="module">
        const ctx = document.getElementById('laporanChart').getContext('2d');
        const dataLaporan = @json($chartData);

        new window.Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
                datasets: [{
                    label: 'Jumlah Laporan',
                    data: dataLaporan,
                    backgroundColor: [
                        '#e11d48', '#f97316', '#fbbf24', '#4ade80', '#0d9488',
                        '#2563eb', '#4338ca', '#7e22ce', '#db2777', '#c026d3',
                        '#78350f', '#6b7280'
                    ],
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    </script>

    <div class="info-bps-section">
        <h3 class="info-bps-title">Info BPS Kota Sukabumi</h3>

        <div class="news-wrapper">
            @forelse($infos as $info)
                <div class="news-card">
                    <div class="news-meta">
                        {{ \Carbon\Carbon::parse($info->created_at)->translatedFormat('l, d F Y') }}<br>
                        {{ \Carbon\Carbon::parse($info->created_at)->format('H:i') }}
                    </div>
                    <div class="news-title">{{ $info->judul }}</div>
                    @if($info->foto)
                        <img src="{{ asset('storage/' . $info->foto) }}" alt="{{ $info->judul }}" class="news-image"
                            onclick="openImagePopup(this.src)">
                    @else
                        <div class="news-image-placeholder">BERITA</div>
                    @endif
                </div>
            @empty
                <div style="color: white; padding: 20px; text-align: center; width: 100%;">Belum ada Info BPS.</div>
            @endforelse
        </div>
    </div>

    <!-- Modal Pop-up Foto Info BPS -->
    <div id="imagePopupModal" class="image-popup-modal" onclick="closeImagePopup()">
        <span class="image-popup-close">&times;</span>
        <img class="image-popup-content" id="popupImage" onclick="event.stopPropagation()">
    </div>

    <style>
        /* --- 1. GAYA KOTAK PEMBUNGKUS --- */
        .card-panel {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .panel-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .panel-title {
            margin: 0;
            color: #444;
            font-size: 1.2rem;
            font-weight: 600;
        }

        .stats-grid-top {
            display: grid;
            grid-template-columns: repeat(1, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }

        .stats-grid-bottom {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            padding: 25px;
            border-radius: 12px;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        .stat-card h3 {
            font-size: 2.5rem;
            margin: 0 0 10px 0;
            color: #333;
            font-weight: 700;
        }

        .stat-card p {
            margin: 0;
            color: #666;
            font-size: 1rem;
        }

        /* --- 2. GAYA TABEL MINI DASHBOARD --- */
        .wrapper-tabel {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
            width: 100%;
            border-radius: 8px;
            border: 1px solid #eee;
        }

        .table-custom {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            min-width: 400px;
        }

        .table-custom thead th {
            background: #444;
            color: #fff;
            padding: 12px 15px;
            text-align: left;
            white-space: nowrap;
        }

        .table-custom thead th:first-child {
            border-top-left-radius: 8px;
        }

        .table-custom thead th:last-child {
            border-top-right-radius: 8px;
        }

        .table-custom tbody td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .table-custom tbody tr:hover {
            background-color: #fafafa;
        }

        /* --- 3. GAYA GRAFIK --- */
        .chart-container {
            position: relative;
            height: 300px;
            width: 100%;
        }

        /* --- 4. MEDIA QUERY (LAYAR HP) --- */
        @media (max-width: 768px) {
            .card-panel {
                padding: 15px;
                /* Menghemat ruang di layar HP */
                border-radius: 10px;
                margin-bottom: 20px;
            }

            .panel-title {
                font-size: 1.1rem;
            }

            .stats-grid-top {
                gap: 8px;
                margin-bottom: 10px;
            }

            .stats-grid-bottom {
                gap: 8px;
                margin-bottom: 20px;
            }

            .stat-card {
                padding: 32px 5px;
                /* Mengurangi ruang kosong di dalam kotak */
                border-radius: 8px;
            }

            .stat-card h3 {
                font-size: 1.7rem;
                /* Angka dikecilkan drastis agar '00:01' muat */
                margin-bottom: 4px;
            }

            .stat-card p {
                font-size: 0.7rem;
                /* Teks label dikecilkan */
                line-height: 1.2;
            }

            .table-custom th,
            .table-custom td {
                font-size: 0.85rem;
                padding: 10px 8px;
            }

            /* Merapatkan kolom Waktu dan Tanggal di HP */
            .table-custom th:nth-child(1),
            .table-custom td:nth-child(1),
            .table-custom th:nth-child(3),
            .table-custom td:nth-child(3) {
                width: 1px;
                white-space: nowrap;
            }

            /* --- PENYESUAIAN INFO BPS UNTUK MOBILE --- */
            .info-bps-section {
                padding: 20px 15px;
                margin-bottom: 20px;
            }

            .info-bps-title {
                font-size: 1.2rem;
                margin-bottom: 20px;
            }

            .news-card {
                min-width: 200px;
                max-width: 200px;
                padding: 12px;
            }

            .news-title {
                font-size: 0.85rem;
                margin-bottom: 10px;
            }

            .news-meta {
                font-size: 0.75rem;
                margin-bottom: 10px;
            }

            .news-image {
                height: 100px;
            }
        }

        /* --- 5. INFO BPS SECTION --- */
        .info-bps-section {
            background-color: #3f4246;
            /* Sesuai gambar, sedikit dark grey */
            padding: 30px;
            border-radius: 8px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .info-bps-title {
            color: #ffffff;
            text-align: center;
            font-size: 1.4rem;
            font-weight: 700;
            margin-bottom: 25px;
            text-decoration: underline;
            text-underline-offset: 6px;
            text-decoration-thickness: 1.5px;
        }

        .news-wrapper {
            display: flex;
            gap: 15px;
            overflow-x: auto;
            padding-bottom: 10px;
            /* Scrollbar Styling */
            scrollbar-width: thin;
            scrollbar-color: #9ca3af #3f4246;
        }

        .news-wrapper::-webkit-scrollbar {
            height: 8px;
        }

        .news-wrapper::-webkit-scrollbar-track {
            background: #3f4246;
            border-radius: 4px;
        }

        .news-wrapper::-webkit-scrollbar-thumb {
            background-color: #9ca3af;
            border-radius: 4px;
        }

        .news-card {
            background: #ffffff;
            border-radius: 8px;
            min-width: 240px;
            max-width: 240px;
            padding: 15px;
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .news-meta {
            color: #505256ff;
            font-size: 0.85rem;
            line-height: 1.3;
            margin-bottom: 15px;
        }

        .news-title {
            color: #505256ff;
            /* Disesuaikan dengan warna sidebar */
            font-size: 0.95rem;
            text-align: center;
            margin-bottom: 15px;
            font-weight: 700;
            /* Ditebalkan sedikit agar lebih jelas */
        }

        .news-image {
            width: 100%;
            height: 120px;
            object-fit: contain;
            /* Diubah dari cover ke contain */
            background-color: #f8fafc;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
            cursor: pointer;
            transition: transform 0.2s ease;
            margin-top: auto;
            /* Mendorong gambar selalu ke bagian paling bawah kartu */
        }

        .news-image:hover {
            transform: scale(1.02);
        }

        .news-image-placeholder {
            width: 100%;
            height: 120px;
            border-radius: 6px;
            border: 1px solid #f3f4f6;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.6rem;
            font-weight: 800;
            color: #9ca3af;
            letter-spacing: 1px;
            margin-top: auto;
            /* Mendorong kotak placeholder selalu ke bagian paling bawah kartu */
        }

        /* --- 6. MODAL FOTO POP-UP --- */
        .image-popup-modal {
            display: none;
            position: fixed;
            z-index: 10000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.85);
            backdrop-filter: blur(5px);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .image-popup-modal.show {
            opacity: 1;
            pointer-events: auto;
        }

        .image-popup-content {
            max-width: 90%;
            max-height: 85vh;
            border-radius: 8px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.5);
            transform: scale(0.8);
            transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .image-popup-modal.show .image-popup-content {
            transform: scale(1);
        }

        .image-popup-close {
            position: absolute;
            top: 20px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.2s;
            cursor: pointer;
            z-index: 10001;
        }

        .image-popup-close:hover {
            color: #ef4444;
        }

        /* Penyesuaian Modal untuk Mobile */
        @media (max-width: 768px) {
            .image-popup-content {
                max-width: 95%;
                max-height: 80vh;
            }

            .image-popup-close {
                top: 15px;
                right: 20px;
                font-size: 35px;
            }
        }
    </style>

    <script>
        function openImagePopup(src) {
            const modal = document.getElementById('imagePopupModal');
            const modalImg = document.getElementById('popupImage');
            modal.style.display = "flex";
            setTimeout(() => {
                modal.classList.add('show');
                modalImg.src = src;
            }, 10);
        }

        function closeImagePopup() {
            const modal = document.getElementById('imagePopupModal');
            modal.classList.remove('show');
            setTimeout(() => {
                modal.style.display = "none";
            }, 300);
        }
    </script>
@endsection