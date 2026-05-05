@extends('layouts.master')

@section('title', 'Kelola Laporan - ALPHA')
@section('header_title', 'Kelola Laporan')

@section('content')
    <style>
        .page-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        /* Form Filter Styles */
        .filter-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
            margin-bottom: 20px;
        }

        .filter-row {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .filter-row label {
            width: 80px;
            font-weight: 500;
            color: #4b5563;
        }

        .input-group {
            display: flex;
            align-items: center;
            gap: 15px;
            flex: 1;
            max-width: 600px;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            outline: none;
            font-size: 0.95rem;
        }

        .date-input {
            width: 250px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
        }

        .btn-dark {
            background: #374151; /* Dark gray like in screenshot */
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-dark:hover {
            background: #1f2937;
        }

        .btn-download {
            border-radius: 6px;
            padding: 8px 15px;
        }

        /* Panel Container */
        .laporan-panel {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
        }

        /* Table Styles */
        .table-responsive {
            overflow-x: auto;
            border-radius: 8px;
        }

        .table-laporan {
            width: 100%;
            border-collapse: collapse;
            min-width: 800px;
        }

        .table-laporan th,
        .table-laporan td {
            border: 1px solid #e5e7eb;
            padding: 12px 15px;
            text-align: center;
            color: #6b7280;
            font-size: 0.95rem;
        }

        .table-laporan th {
            background-color: #374151;
            color: #ffffff;
            font-weight: 600;
            border-color: #374151;
        }

        .table-laporan td.text-left {
            text-align: left;
        }

        .bukti-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
            font-size: 1.5rem;
            color: #4b5563;
        }

        .pdf-download {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 5px;
            color: #4b5563;
            text-decoration: none;
            font-weight: 500;
        }

        .pdf-download i {
            font-size: 1.2rem;
        }

        .pdf-download:hover {
            color: #2563eb;
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 40px;
            color: #6b7280;
            background: #f9fafb;
            border-radius: 8px;
            border: 1px dashed #d1d5db;
        }
    </style>

    <div class="page-container">
        
        <!-- Bagian Filter & Pencarian -->
        <div class="filter-section">
            <form action="{{ route('admin.laporan.index') }}" method="GET" id="filterForm">
                <!-- Pencarian Pengguna -->
                <div class="filter-row" style="margin-bottom: 15px;">
                    <label style="display: none;" for="search">Cari Pengguna</label>
                    <div class="input-group">
                        <input type="text" name="search" id="search" class="search-input" placeholder="Cari nama pengguna (username) atau NIP" value="{{ request('search') }}">
                        <button type="submit" class="btn-dark">Cari</button>
                    </div>
                </div>

                <!-- Filter Bulan (Hanya untuk Download) -->
                <div class="filter-row" style="margin-bottom: 15px;">
                    <label>Bulan</label>
                    <div class="input-group">
                        <input type="month" name="bulan" id="input_bulan" class="date-input" value="{{ request('bulan') }}">
                        <button type="button" class="btn-dark btn-download" onclick="downloadRekap('bulanan')">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>

                <!-- Filter Tanggal -->
                <div class="filter-row">
                    <label>Tanggal</label>
                    <div class="input-group">
                        <input type="date" name="tanggal" id="input_tanggal" class="date-input" value="{{ request('tanggal') }}" onchange="document.getElementById('filterForm').submit()">
                        <button type="button" class="btn-dark btn-download" onclick="downloadRekap('harian')">
                            <i class="fas fa-download"></i> Download
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function downloadRekap(tipe) {
                const userId = '{{ $searchedUser ? $searchedUser->id : '' }}';
                if (!userId) {
                    alert('Silakan cari pengguna (tekan Cari) terlebih dahulu!');
                    return;
                }

                let url = '{{ route("admin.laporan.download") }}?user_id=' + userId + '&tipe=' + tipe;
                
                if (tipe === 'bulanan') {
                    const bulan = document.getElementById('input_bulan').value;
                    if (!bulan) {
                        alert('Silakan pilih Bulan terlebih dahulu!');
                        return;
                    }
                    url += '&bulan=' + bulan;
                } else if (tipe === 'harian') {
                    const tanggal = document.getElementById('input_tanggal').value;
                    if (!tanggal) {
                        alert('Silakan pilih Tanggal terlebih dahulu!');
                        return;
                    }
                    url += '&tanggal=' + tanggal;
                }

                window.location.href = url;
            }
        </script>

        <!-- Tabel Laporan -->
        <div class="laporan-panel">
            @if(request('search') && !$searchedUser)
                <div class="empty-state">
                    <i class="fas fa-user-times" style="font-size: 3rem; margin-bottom: 15px; color: #ef4444;"></i>
                    <p style="font-size: 1.1rem; font-weight: 500;">Pengguna tidak ditemukan.</p>
                    <p>Pastikan nama pengguna (username) atau NIP yang Anda cari sudah benar.</p>
                </div>
            @elseif($searchedUser)
                @if($laporans->count() > 0)
                    <div style="margin-bottom: 15px; font-weight: 500; color: #374151;">
                        Menampilkan laporan milik: <span style="color: #2563eb;">{{ $searchedUser->username }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table-laporan">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th style="width: 150px;">Waktu</th>
                                    <th class="text-left">Kegiatan</th>
                                    <th>Deskripsi</th>
                                    <th style="width: 120px;">Bukti</th>
                                    <th style="width: 150px;">bukti bentuk pdf</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $index => $laporan)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        {{ substr($laporan->jam_mulai, 0, 5) }} - 
                                        {{ substr($laporan->jam_selesai, 0, 5) }} WIB
                                        <br><small style="color: #9ca3af;">{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}</small>
                                    </td>
                                    <td class="text-left" style="color: #4b5563; font-weight: 500;">
                                        {{ $laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-' }}
                                    </td>
                                    <td style="color: #6b7280;">{{ $laporan->deskripsi }}</td>
                                    <td>
                                        <div class="bukti-icons">
                                            @if($laporan->foto_mulai)
                                                <a href="javascript:void(0)" onclick="openImageModal('{{ asset('storage/' . $laporan->foto_mulai) }}')" title="Foto Mulai" style="color: #4b5563;">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @endif
                                            @if($laporan->foto_selesai)
                                                <a href="javascript:void(0)" onclick="openImageModal('{{ asset('storage/' . $laporan->foto_selesai) }}')" title="Foto Selesai" style="color: #4b5563;">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.laporan.downloadIndividu', $laporan->id) }}" class="pdf-download" title="Unduh Bukti Foto">
                                            <i class="fas fa-file-pdf"></i> <span style="color: #2563eb; text-decoration: underline;">unduh</span>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="fas fa-folder-open" style="font-size: 3rem; margin-bottom: 15px; color: #9ca3af;"></i>
                        <p style="font-size: 1.1rem; font-weight: 500;">Tidak ada laporan.</p>
                        <p>Pengguna <b>{{ $searchedUser->username }}</b> tidak memiliki laporan pada periode yang dipilih.</p>
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <i class="fas fa-search" style="font-size: 3rem; margin-bottom: 15px; color: #d1d5db;"></i>
                    <p style="font-size: 1.1rem; font-weight: 500;">Silakan cari pengguna terlebih dahulu.</p>
                    <p>Daftar laporan akan muncul di sini setelah Anda melakukan pencarian.</p>
                </div>
            @endif
        </div>

    </div>

    <!-- Image Preview Modal -->
    <div id="imagePreviewModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 10000; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
        <div style="background: #fff; padding: 25px; border-radius: 12px; max-width: 90%; max-height: 90vh; display: flex; flex-direction: column; align-items: center; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <button onclick="document.getElementById('imagePreviewModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; transition: color 0.2s;">&times;</button>
            
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.25rem; font-weight: 600; color: #1f2937; width: 100%; text-align: left;">Preview Bukti Foto</h3>
            
            <img id="previewImage" src="" alt="Bukti Foto" style="max-width: 100%; max-height: 60vh; object-fit: contain; margin-bottom: 25px; border-radius: 6px; border: 1px solid #e5e7eb;">
            
            <div style="display: flex; gap: 15px; width: 100%; justify-content: flex-end;">
                <button onclick="document.getElementById('imagePreviewModal').style.display='none'" style="padding: 10px 20px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; cursor: pointer; font-weight: 500;">
                    Tutup
                </button>
                <a id="downloadImageBtn" href="#" download class="btn-dark" style="text-decoration: none; border-radius: 8px; padding: 10px 20px;">
                    <i class="fas fa-download"></i> Unduh
                </a>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl) {
            document.getElementById('previewImage').src = imageUrl;
            document.getElementById('downloadImageBtn').href = imageUrl;
            document.getElementById('imagePreviewModal').style.display = 'flex';
        }
    </script>
@endsection
