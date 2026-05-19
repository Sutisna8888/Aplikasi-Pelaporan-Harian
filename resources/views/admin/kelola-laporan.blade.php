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
    .filter-section {
            display: flex;
            flex-direction: column;
            gap: 15px;
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
            position: relative;
        }

        .search-input {
            flex: 1;
            padding: 10px 15px;
            border: 1px solid #d1d5db;
            border-radius: 20px;
            outline: none;
            font-size: 0.95rem;
        }

        .autocomplete-dropdown {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            max-height: 250px;
            overflow-y: auto;
            z-index: 1000;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
            display: none;
        }

        .autocomplete-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #f3f4f6;
            transition: background 0.2s;
        }

        .autocomplete-item:last-child {
            border-bottom: none;
        }

        .autocomplete-item:hover {
            background: #f3f4f6;
        }

        .autocomplete-item-name {
            font-weight: 500;
            color: #374151;
        }

        .autocomplete-item-nip {
            font-size: 0.85rem;
            color: #6b7280;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 110%;
            background-color: #ffffff;
            min-width: 200px;
            box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.1);
            z-index: 100;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            overflow: hidden;
        }

        .dropdown-content a {
            color: #374151;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
            font-size: 0.9rem;
            font-weight: 500;
            transition: background-color 0.2s;
        }

        .dropdown-content a:hover {
            background-color: #f3f4f6;
        }

        .dropdown-content.show {
            display: block;
        }

        .date-input {
            width: 250px;
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            outline: none;
        }

        .btn-dark {
            background: #374151;
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.12s ease, transform 0.12s ease, box-shadow 0.12s ease;
            text-decoration: none;
        }

        .btn-dark:hover {
            background: #1f2937;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .btn-cancel {
            background: #f3f4f6;
            color: #4b5563;
            border: none;
            padding: 10px 25px;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 600;
            transition: background 0.12s ease, transform 0.12s ease, box-shadow 0.12s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-cancel:hover {
            background: #e6e9ec;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.06);
        }

        .btn-download {
            border-radius: 6px;
            padding: 8px 15px;
    }
    .laporan-panel {
            background: #fff;
            border-radius: 12px;
            padding: 25px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05);
    }
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
        <div class="filter-section">
            <form action="{{ route('admin.laporan.index') }}" method="GET" id="filterForm">

                <div class="filter-row">
                    <label style="display: none;" for="search">Cari Pengguna</label>
                    <div class="input-group" style="max-width: 100%;">
                        <div style="position: relative; flex: 1; min-width: 250px; max-width: 400px;">
                            <input type="text" name="search" id="search" class="search-input"
                                style="width: 100%; padding-right: 35px; box-sizing: border-box;"
                                placeholder="Cari nama pengguna (username) atau NIP" value="{{ request('search') }}"
                                autocomplete="off">
                            <button type="button" id="clearSearchBtn" title="Reset Pencarian"
                                style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #9ca3af; cursor: pointer; display: {{ request('search') ? 'block' : 'none' }}; padding: 0; outline: none;">
                                <i class="fas fa-times"></i>
                            </button>
                            <div id="autocomplete-results" class="autocomplete-dropdown"></div>
                        </div>
                        
                        <input type="date" name="tanggal" id="input_tanggal" class="date-input"
                            value="{{ request('tanggal') }}" style="width: 160px; padding: 10px 15px; border-radius: 20px;">
                            
                        <button type="submit" class="btn-dark">Cari</button>
                        
                        <div style="position: relative; display: inline-block;">
                            <button type="button" class="btn-dark btn-download" onclick="document.getElementById('downloadOptions').classList.toggle('show')">
                                <i class="fas fa-download"></i> Download
                            </button>
                            <div id="downloadOptions" class="dropdown-content">
                                <a href="javascript:void(0)" onclick="downloadRekap('harian')"><i class="fas fa-calendar-day" style="margin-right: 8px; width: 16px; text-align: center;"></i> Tanggal ini</a>
                                <a href="javascript:void(0)" onclick="downloadRekap('bulanan')"><i class="fas fa-calendar-alt" style="margin-right: 8px; width: 16px; text-align: center;"></i> Bulan Ini</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <script>
            function downloadRekap(tipe) {
                const userId = '{{ $searchedUser ? $searchedUser->id : '' }}';
                if (!userId) {
                    panggilModalKonfirmasi('Pencarian Diperlukan', 'Silakan cari pengguna (tekan Cari) terlebih dahulu!', 'fas fa-search', '#374151', 'Mengerti', null, null);
                    return;
                }

                let url = '{{ route("admin.laporan.download") }}?user_id=' + userId + '&tipe=' + tipe;
                const tanggal = document.getElementById('input_tanggal').value;

                if (tipe === 'harian') {
                    if (!tanggal) {
                        panggilModalKonfirmasi('Pilih Tanggal', 'Silakan pilih Tanggal terlebih dahulu!', 'fas fa-calendar-day', '#374151', 'Mengerti', null, null);
                        return;
                    }
                    url += '&tanggal=' + tanggal;
                } else if (tipe === 'bulanan') {
                    if (!tanggal) {
                        panggilModalKonfirmasi('Pilih Tanggal', 'Silakan pilih Tanggal terlebih dahulu (untuk menentukan bulan)!', 'fas fa-calendar-day', '#374151', 'Mengerti', null, null);
                        return;
                    }
                    const bulan = tanggal.substring(0, 7); // Mengambil format YYYY-MM
                    url += '&bulan=' + bulan;
                }

                const dropdown = document.getElementById('downloadOptions');
                if (dropdown) dropdown.classList.remove('show');

                window.location.href = url;
            }

            // Menutup dropdown jika diklik di luar tombol
            window.addEventListener('click', function(e) {
                if (!e.target.closest('.btn-download')) {
                    var dropdown = document.getElementById("downloadOptions");
                    if (dropdown && dropdown.classList.contains('show')) {
                        dropdown.classList.remove('show');
                    }
                }
            });
        </script>
        <div class="laporan-panel">
            @if(request('search') && !$searchedUser)
                <div class="empty-state">
                    <i class="fas fa-user-times" style="font-size: 3rem; margin-bottom: 15px; color: #ef4444;"></i>
                    <p style="font-size: 1.1rem; font-weight: 500;">Pengguna tidak ditemukan.</p>
                    <p>Pastikan nama pengguna (username) atau NIP yang Anda cari sudah benar.</p>
                </div>
            @elseif($searchedUser)
                @if(!$tanggal)
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 15px; color: #d1d5db;"></i>
                        <p style="font-size: 1.1rem; font-weight: 500;">Silakan pilih tanggal.</p>
                        <p>Pilih tanggal terlebih dahulu untuk melihat laporan dari <b>{{ $searchedUser->username }}</b>.</p>
                    </div>
                @elseif($laporans->count() > 0)
                    <div style="margin-bottom: 15px; font-weight: 500; color: #374151;">
                        Menampilkan laporan: <span style="color: #2563eb;">{{ $searchedUser->username }}</span>
                    </div>
                    <div class="table-responsive">
                        <table class="table-laporan">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">No</th>
                                    <th class="text-left" style="width: 150px;">Waktu</th>
                                    <th class="text-left">Kegiatan</th>
                                    <th class="text-left">Deskripsi</th>
                                    <th>Lokasi</th>
                                    <th style="width: 120px;">Bukti</th>
                                    <th style="width: 150px;">bukti bentuk pdf</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($laporans as $index => $laporan)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td class="text-left" style="color: #6b7280;">
                                            {{ \Carbon\Carbon::parse($laporan->tanggal)->translatedFormat('d F Y') }}<br>
                                            <span style="font-weight: normal; color: #9ca3af;">{{ substr($laporan->jam_mulai, 0, 5) }} -
                                            {{ substr($laporan->jam_selesai, 0, 5) }} WIB</span>
                                        </td>
                                        <td class="text-left" style="color: #6b7280;">
                                            {{ $laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-' }}
                                        </td>
                                        <td class="text-left" style="color: #6b7280;">{{ $laporan->deskripsi }}</td>
                                        <td style="color: #6b7280;">{{ $laporan->lokasi_teks ?: '-' }}</td>
                                        <td>
                                            <div class="bukti-icons" style="display: flex; gap: 8px;">
                                                @php
                                                    $fotoMulaiUrl = '';
                                                    $fotoSelesaiUrl = '';
                                                    if ($laporan->foto_mulai) {
                                                        $f = $laporan->foto_mulai;
                                                        if (preg_match('/^https?:\/\//', $f) || str_starts_with($f, '/')) {
                                                            $fotoMulaiUrl = $f;
                                                        } elseif (strpos($f, '/') !== false) {
                                                            $fotoMulaiUrl = asset('storage/' . $f);
                                                        } else {
                                                            $fotoMulaiUrl = asset('storage/foto_laporan/' . $f);
                                                        }
                                                    }
                                                    if ($laporan->foto_selesai) {
                                                        $f2 = $laporan->foto_selesai;
                                                        if (preg_match('/^https?:\/\//', $f2) || str_starts_with($f2, '/')) {
                                                            $fotoSelesaiUrl = $f2;
                                                        } elseif (strpos($f2, '/') !== false) {
                                                            $fotoSelesaiUrl = asset('storage/' . $f2);
                                                        } else {
                                                            $fotoSelesaiUrl = asset('storage/foto_laporan/' . $f2);
                                                        }
                                                    }
                                                @endphp

                                                @if($fotoMulaiUrl)
                                                    <div style="position: relative;" title="Bukti Mulai">
                                                        <a href="javascript:void(0)"
                                                            onclick="openImageModal('{{ $fotoMulaiUrl }}', 'Mulai', '{{ addslashes($searchedUser->username) }}', '{{ addslashes($laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-') }}', '{{ addslashes($laporan->deskripsi) }}', '{{ addslashes($laporan->lokasi_teks) }}', '{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}', '{{ substr($laporan->jam_mulai, 0, 5) }} WIB')"
                                                            style="display: inline-block; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                                                            <img src="{{ $fotoMulaiUrl }}" alt="Mulai" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 2px solid #d1d5db; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div style="display: none; width: 40px; height: 40px; background: #f3f4f6; border-radius: 6px; border: 2px dashed #d1d5db; align-items: center; justify-content: center; font-size: 1.2rem; color: #9ca3af;">
                                                                <i class="fas fa-camera"></i>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div style="width: 40px; height: 40px; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 1.2rem;" title="Belum ada bukti mulai">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif

                                                @if($fotoSelesaiUrl)
                                                    <div style="position: relative;" title="Bukti Selesai">
                                                        <a href="javascript:void(0)"
                                                            onclick="openImageModal('{{ $fotoSelesaiUrl }}', 'Selesai', '{{ addslashes($searchedUser->username) }}', '{{ addslashes($laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-') }}', '{{ addslashes($laporan->deskripsi) }}', '{{ addslashes($laporan->lokasi_teks) }}', '{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}', '{{ substr($laporan->jam_selesai, 0, 5) }} WIB')"
                                                            style="display: inline-block; transition: transform 0.2s; cursor: pointer;" onmouseover="this.style.transform='scale(1.15)'" onmouseout="this.style.transform='scale(1)'">
                                                            <img src="{{ $fotoSelesaiUrl }}" alt="Selesai" style="width: 40px; height: 40px; object-fit: cover; border-radius: 6px; border: 2px solid #d1d5db; box-shadow: 0 2px 4px rgba(0,0,0,0.1); display: block;" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                                            <div style="display: none; width: 40px; height: 40px; background: #f3f4f6; border-radius: 6px; border: 2px dashed #d1d5db; align-items: center; justify-content: center; font-size: 1.2rem; color: #9ca3af;">
                                                                <i class="fas fa-camera"></i>
                                                            </div>
                                                        </a>
                                                    </div>
                                                @else
                                                    <div style="width: 40px; height: 40px; background: #f9fafb; border: 2px dashed #d1d5db; border-radius: 6px; display: flex; align-items: center; justify-content: center; color: #d1d5db; font-size: 1.2rem;" title="Belum ada bukti selesai">
                                                        <i class="fas fa-image"></i>
                                                    </div>
                                                @endif
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.laporan.downloadIndividu', $laporan->id) }}" class="pdf-download"
                                                title="Unduh Bukti Foto">
                                                <i class="fas fa-file-pdf"></i> <span
                                                    style="color: #2563eb; text-decoration: underline;">unduh</span>
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
    <div id="imagePreviewModal"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.7); z-index: 10000; justify-content: center; align-items: center; backdrop-filter: blur(2px);">
        <div
            style="background: #fff; padding: 25px; border-radius: 12px; width: 95%; max-width: 1000px; max-height: 90vh; display: flex; flex-direction: column; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <button onclick="document.getElementById('imagePreviewModal').style.display='none'"
                style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; transition: color 0.2s;">&times;</button>

            <h3
                style="margin-top: 0; margin-bottom: 20px; font-size: 1.25rem; font-weight: 600; color: #1f2937; width: 100%; text-align: left;">
                Preview Bukti Foto</h3>
            <div id="modalBodyContent"
                style="display: flex; gap: 25px; margin-bottom: 25px; min-height: 0; flex: 1; overflow: hidden;">

                <div
                    style="flex: 1.6; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img id="previewImage" src="" alt="Bukti Foto"
                        style="max-width: 100%; max-height: 60vh; object-fit: contain;">
                </div>
                <div
                    style="flex: 1; display: flex; flex-direction: column; gap: 18px; padding: 5px 5px 5px 15px; border-left: 1px solid #f3f4f6; overflow-y: auto;">
                    <div style="margin-bottom: 5px; border-bottom: 3px solid #374151; padding-bottom: 8px;">
                        <span id="info_tipe"
                            style="text-transform: uppercase; font-weight: 900; color: #374151; font-size: 1.2rem; letter-spacing: 1px;">FOTO
                            MULAI</span>
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; color: #374151; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Pegawai</label>
                        <div id="info_pegawai" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; color: #374151; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Kegiatan</label>
                        <div id="info_kegiatan" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>
                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; color: #374151; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Deskripsi</label>
                        <div id="info_deskripsi" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>

                    <div>
                        <label
                            style="display: block; font-size: 0.7rem; color: #374151 text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Tempat
                            / Lokasi</label>
                        <div id="info_tempat" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>
                    <div
                        style="display: flex; gap: 30px; margin-top: auto; padding-top: 15px; border-top: 1px dashed #e5e7eb;">
                        <div>
                            <label
                                style="display: block; font-size: 0.7rem; color: #374151 text-transform: uppercase; font-weight: 800; margin-bottom: 4px;">Tanggal</label>
                            <div id="info_tanggal" style="color: #1f2937; font-weight: 600;">-</div>
                        </div>
                        <div>
                            <label
                                style="display: block; font-size: 0.7rem; color: #374151 text-transform: uppercase; font-weight: 800; margin-bottom: 4px;">Waktu</label>
                            <div id="info_waktu" style="color: #1f2937; font-weight: 600;">-</div>
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 15px; width: 100%; justify-content: flex-end;">
                <button onclick="document.getElementById('imagePreviewModal').style.display='none'" class="btn-cancel">
                    Tutup
                </button>
                <button onclick="downloadCombinedImage()" class="btn-dark" style="border-radius: 25px; padding: 10px 25px;">
                    <i class="fas fa-download"></i> Unduh Foto
                </button>
            </div>
        </div>
    </div>

    <script>
        function openImageModal(imageUrl, tipe, user, kegiatan, deskripsi, tempat, tanggal, waktu) {
            document.getElementById('previewImage').src = imageUrl;

            document.getElementById('info_tipe').innerText = 'FOTO ' + tipe;
            document.getElementById('info_pegawai').innerText = user;
            document.getElementById('info_kegiatan').innerText = kegiatan;
            document.getElementById('info_deskripsi').innerText = deskripsi;
            document.getElementById('info_tempat').innerText = tempat;
            document.getElementById('info_tanggal').innerText = tanggal;
            document.getElementById('info_waktu').innerText = waktu;

            document.getElementById('imagePreviewModal').style.display = 'flex';
        }
        async function downloadCombinedImage() {
            const img = document.getElementById('previewImage');
            const tipe = document.getElementById('info_tipe').innerText;
            const user = document.getElementById('info_pegawai').innerText;
            const kegiatan = document.getElementById('info_kegiatan').innerText;
            const deskripsi = document.getElementById('info_deskripsi').innerText;
            const tempat = document.getElementById('info_tempat').innerText;
            const tanggal = document.getElementById('info_tanggal').innerText;
            const waktu = document.getElementById('info_waktu').innerText;

            const canvas = document.createElement('canvas');
            const ctx = canvas.getContext('2d');

            const infoWidth = 350;
            const imgNatW = img.naturalWidth;
            const imgNatH = img.naturalHeight;
            const padding = 20;
            const MAX_IMG_HEIGHT = 800;
            let drawW = imgNatW;
            let drawH = imgNatH;

            if (imgNatH > MAX_IMG_HEIGHT) {
                const scale = MAX_IMG_HEIGHT / imgNatH;
                drawW = Math.round(imgNatW * scale);
                drawH = MAX_IMG_HEIGHT;
            }

            canvas.width = padding + drawW + padding + infoWidth + padding;
            canvas.height = Math.max(drawH + (padding * 2), 600);

            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            ctx.drawImage(img, padding, padding, drawW, drawH);

            const startX = padding + drawW + padding + 15;
            let currentY = padding + 35;


            ctx.fillStyle = '#374151';
            ctx.font = 'bold 32px Arial';
            ctx.fillText(tipe, startX, currentY);

            ctx.strokeStyle = '#374151';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(startX, currentY + 14);
            ctx.lineTo(startX + infoWidth - 30, currentY + 14);
            ctx.stroke();

            currentY += 75;

            const drawField = (label, value, isBoldValue = true) => {
                ctx.fillStyle = '#9ca3af';
                ctx.font = 'bold 18px Arial';
                ctx.fillText(label.toUpperCase(), startX, currentY);
                currentY += 30;

                ctx.fillStyle = isBoldValue ? '#1f2937' : '#4b5563';
                ctx.font = isBoldValue ? 'bold 24px Arial' : '21px Arial';


                const maxWidth = infoWidth - 30;
                const lineHeight = 30;
                const words = value.split(' ');
                let line = '';

                for (let n = 0; n < words.length; n++) {
                    let testLine = line + words[n] + ' ';
                    let metrics = ctx.measureText(testLine);
                    if (metrics.width > maxWidth && n > 0) {
                        ctx.fillText(line, startX, currentY);
                        line = words[n] + ' ';
                        currentY += lineHeight;
                    } else {
                        line = testLine;
                    }
                }
                ctx.fillText(line, startX, currentY);
                currentY += 48;
            };

            drawField('Pegawai', user);
            drawField('Kegiatan', kegiatan);
            drawField('Deskripsi', deskripsi, false);
            drawField('Tempat / Lokasi', tempat, false);
            drawField('Tanggal', tanggal);
            drawField('Waktu', waktu);


            ctx.fillStyle = '#9ca3af';
            ctx.font = 'italic 14px Arial';
            ctx.fillText('ALPHA-BPS Kota Sukabumi', canvas.width - 200, canvas.height - 20);


            const link = document.createElement('a');
            link.download = `Bukti_${tipe.split(' ')[1]}_${user}_${tanggal.replace(/ /g, '_')}.jpg`;
            link.href = canvas.toDataURL('image/jpeg', 0.9);
            link.click();
        }
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('search');
            const autocompleteResults = document.getElementById('autocomplete-results');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            let timeoutId;


            searchInput.addEventListener('input', function () {
                clearTimeout(timeoutId);
                const query = this.value;


                if (query.length > 0) {
                    clearSearchBtn.style.display = 'block';
                } else {
                    clearSearchBtn.style.display = 'none';
                }

                if (query.length < 2) {
                    autocompleteResults.style.display = 'none';
                    return;
                }

                timeoutId = setTimeout(() => {
                    fetch(`{{ route('admin.laporan.searchUsers') }}?q=${encodeURIComponent(query)}`)
                        .then(response => response.json())
                        .then(data => {
                            autocompleteResults.innerHTML = '';
                            if (data.length > 0) {
                                data.forEach(user => {
                                    const item = document.createElement('div');
                                    item.className = 'autocomplete-item';
                                    item.innerHTML = `
                                            <div class="autocomplete-item-name">${user.username}</div>
                                            <div class="autocomplete-item-nip">NIP: ${user.nip}</div>
                                        `;
                                    item.addEventListener('click', () => {
                                        searchInput.value = user.username;
                                        autocompleteResults.style.display = 'none';

                                        clearSearchBtn.style.display = 'block';
                                    });
                                    autocompleteResults.appendChild(item);
                                });
                                autocompleteResults.style.display = 'block';
                            } else {
                                autocompleteResults.style.display = 'none';
                            }
                        });
                }, 300);
            });
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function () {
                    searchInput.value = '';
                    clearSearchBtn.style.display = 'none';
                    autocompleteResults.style.display = 'none';
                    document.getElementById('filterForm').submit();
                });
            }
            document.addEventListener('click', function (e) {
                if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                    autocompleteResults.style.display = 'none';
                }
            });
        });
    </script>
@endsection