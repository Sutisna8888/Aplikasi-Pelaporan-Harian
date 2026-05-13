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
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
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
                        <div style="position: relative; flex: 1;">
                            <input type="text" name="search" id="search" class="search-input" style="width: 100%; padding-right: 35px; box-sizing: border-box;" placeholder="Cari nama pengguna (username) atau NIP" value="{{ request('search') }}" autocomplete="off">
                            <button type="button" id="clearSearchBtn" title="Reset Pencarian" style="position: absolute; right: 12px; top: 50%; transform: translateY(-50%); background: transparent; border: none; color: #9ca3af; cursor: pointer; display: {{ request('search') ? 'block' : 'none' }}; padding: 0; outline: none;">
                                <i class="fas fa-times"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn-dark">Cari</button>
                        <div id="autocomplete-results" class="autocomplete-dropdown"></div>
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
                    panggilModalKonfirmasi('Pencarian Diperlukan', 'Silakan cari pengguna (tekan Cari) terlebih dahulu!', 'fas fa-search', '#374151', 'Mengerti', null, null);
                    return;
                }

                let url = '{{ route("admin.laporan.download") }}?user_id=' + userId + '&tipe=' + tipe;
                
                if (tipe === 'bulanan') {
                    const bulan = document.getElementById('input_bulan').value;
                    if (!bulan) {
                        panggilModalKonfirmasi('Pilih Bulan', 'Silakan pilih Bulan terlebih dahulu!', 'fas fa-calendar-alt', '#374151', 'Mengerti', null, null);
                        return;
                    }
                    url += '&bulan=' + bulan;
                } else if (tipe === 'harian') {
                    const tanggal = document.getElementById('input_tanggal').value;
                    if (!tanggal) {
                        panggilModalKonfirmasi('Pilih Tanggal', 'Silakan pilih Tanggal terlebih dahulu!', 'fas fa-calendar-day', '#374151', 'Mengerti', null, null);
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
                @if(!$tanggal)
                    <div class="empty-state">
                        <i class="fas fa-calendar-alt" style="font-size: 3rem; margin-bottom: 15px; color: #d1d5db;"></i>
                        <p style="font-size: 1.1rem; font-weight: 500;">Silakan pilih tanggal.</p>
                        <p>Pilih tanggal terlebih dahulu untuk melihat laporan dari <b>{{ $searchedUser->username }}</b>.</p>
                    </div>
                @elseif($laporans->count() > 0)
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
                                                <a href="javascript:void(0)" 
                                                   onclick="openImageModal('{{ asset('storage/' . $laporan->foto_mulai) }}', 'Mulai', '{{ addslashes($searchedUser->username) }}', '{{ addslashes($laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-') }}', '{{ addslashes($laporan->deskripsi) }}', '{{ addslashes($laporan->lokasi_teks) }}', '{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}', '{{ substr($laporan->jam_mulai, 0, 5) }} WIB')" 
                                                   title="Foto Mulai" style="color: #4b5563;">
                                                    <i class="fas fa-image"></i>
                                                </a>
                                            @endif
                                            @if($laporan->foto_selesai)
                                                <a href="javascript:void(0)" 
                                                   onclick="openImageModal('{{ asset('storage/' . $laporan->foto_selesai) }}', 'Selesai', '{{ addslashes($searchedUser->username) }}', '{{ addslashes($laporan->kegiatan ? $laporan->kegiatan->nama_kegiatan : '-') }}', '{{ addslashes($laporan->deskripsi) }}', '{{ addslashes($laporan->lokasi_teks) }}', '{{ \Carbon\Carbon::parse($laporan->tanggal)->format('d M Y') }}', '{{ substr($laporan->jam_selesai, 0, 5) }} WIB')" 
                                                   title="Foto Selesai" style="color: #4b5563;">
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
        <div style="background: #fff; padding: 25px; border-radius: 12px; width: 95%; max-width: 1000px; max-height: 90vh; display: flex; flex-direction: column; position: relative; box-shadow: 0 10px 25px rgba(0,0,0,0.2);">
            <button onclick="document.getElementById('imagePreviewModal').style.display='none'" style="position: absolute; top: 15px; right: 20px; background: none; border: none; font-size: 1.5rem; color: #9ca3af; cursor: pointer; transition: color 0.2s;">&times;</button>
            
            <h3 style="margin-top: 0; margin-bottom: 20px; font-size: 1.25rem; font-weight: 600; color: #1f2937; width: 100%; text-align: left;">Preview Bukti Foto</h3>
            
            <!-- Modal Body with 2 columns -->
            <div id="modalBodyContent" style="display: flex; gap: 25px; margin-bottom: 25px; min-height: 0; flex: 1; overflow: hidden;">
                <!-- Left: Image -->
                <div style="flex: 1.6; background: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; display: flex; align-items: center; justify-content: center; overflow: hidden;">
                    <img id="previewImage" src="" alt="Bukti Foto" style="max-width: 100%; max-height: 60vh; object-fit: contain;">
                </div>
                
                <!-- Right: Information (White Panel) -->
                <div style="flex: 1; display: flex; flex-direction: column; gap: 18px; padding: 5px 5px 5px 15px; border-left: 1px solid #f3f4f6; overflow-y: auto;">
                    <div style="margin-bottom: 5px; border-bottom: 3px solid #374151; padding-bottom: 8px;">
                        <span id="info_tipe" style="text-transform: uppercase; font-weight: 900; color: #374151; font-size: 1.2rem; letter-spacing: 1px;">FOTO MULAI</span>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Pegawai</label>
                        <div id="info_pegawai" style="font-weight: 700; color: #1f2937; font-size: 1.05rem;">-</div>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Kegiatan</label>
                        <div id="info_kegiatan" style="font-weight: 700; color: #1f2937; font-size: 1.05rem;">-</div>
                    </div>
                    
                    <div>
                        <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Deskripsi</label>
                        <div id="info_deskripsi" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>

                    <div>
                        <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px; letter-spacing: 0.5px;">Tempat / Lokasi</label>
                        <div id="info_tempat" style="color: #4b5563; font-size: 0.95rem; line-height: 1.4;">-</div>
                    </div>
                    
                    <div style="display: flex; gap: 30px; margin-top: auto; padding-top: 15px; border-top: 1px dashed #e5e7eb;">
                        <div>
                            <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px;">Tanggal</label>
                            <div id="info_tanggal" style="color: #1f2937; font-weight: 600;">-</div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 0.7rem; color: #9ca3af; text-transform: uppercase; font-weight: 800; margin-bottom: 4px;">Waktu</label>
                            <div id="info_waktu" style="color: #1f2937; font-weight: 600;">-</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div style="display: flex; gap: 15px; width: 100%; justify-content: flex-end;">
                <button onclick="document.getElementById('imagePreviewModal').style.display='none'" style="padding: 10px 25px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 25px; cursor: pointer; font-weight: 600; transition: background 0.2s;">
                    Tutup
                </button>
                <button onclick="downloadCombinedImage()" class="btn-dark" style="border-radius: 25px; padding: 10px 25px;">
                    <i class="fas fa-download"></i> Unduh Gabungan
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

            // Ukuran canvas: lebar info lebih lebar (40% dari lebar gambar) dengan batas yang lebih luas
            const imgWidth = img.naturalWidth;
            const imgHeight = img.naturalHeight;
            const infoWidth = Math.max(450, Math.min(imgWidth * 0.40, 800)); 
            
            canvas.width = imgWidth + infoWidth;
            // Pastikan tinggi minimal 700px agar teks informasi tidak terpotong jika foto sangat pendek
            canvas.height = Math.max(imgHeight, 600); 

            // Background putih untuk seluruh canvas
            ctx.fillStyle = '#ffffff';
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            // Gambar foto di sebelah kiri (diposisikan di tengah jika tinggi canvas > tinggi gambar)
            const imgY = (canvas.height - imgHeight) / 2;
            ctx.drawImage(img, 0, imgY, imgWidth, imgHeight);

            // Gambar area info di sebelah kanan
            const startX = imgWidth + (infoWidth * 0.1); // Margin kiri info
            const maxTextWidth = infoWidth * 0.82; // Lebar teks maksimal
            let currentY = canvas.height * 0.07; // Margin atas dinamis berdasarkan tinggi canvas
            if (currentY < 50) currentY = 50; 

            // Judul/Tipe
            ctx.fillStyle = '#374151';
            ctx.font = 'bold ' + Math.floor(infoWidth * 0.065) + 'px Arial';
            ctx.fillText(tipe, startX, currentY);
            
            ctx.strokeStyle = '#374151';
            ctx.lineWidth = 3;
            ctx.beginPath();
            ctx.moveTo(startX, currentY + 15);
            ctx.lineTo(startX + maxTextWidth, currentY + 15);
            ctx.stroke();

            currentY += Math.floor(infoWidth * 0.14);

            const drawField = (label, value, isBoldValue = true) => {
                ctx.fillStyle = '#9ca3af';
                ctx.font = 'bold ' + Math.floor(infoWidth * 0.038) + 'px Arial';
                ctx.fillText(label.toUpperCase(), startX, currentY);
                currentY += Math.floor(infoWidth * 0.055);
                
                ctx.fillStyle = isBoldValue ? '#1f2937' : '#4b5563';
                ctx.font = isBoldValue ? 'bold ' + Math.floor(infoWidth * 0.048) + 'px Arial' : Math.floor(infoWidth * 0.042) + 'px Arial';
                
                // Wrap text if needed
                const lineHeight = Math.floor(infoWidth * 0.055);
                const words = value.split(' ');
                let line = '';
                
                for(let n = 0; n < words.length; n++) {
                    let testLine = line + words[n] + ' ';
                    let metrics = ctx.measureText(testLine);
                    if (metrics.width > maxTextWidth && n > 0) {
                        ctx.fillText(line, startX, currentY);
                        line = words[n] + ' ';
                        currentY += lineHeight;
                    } else {
                        line = testLine;
                    }
                }
                ctx.fillText(line, startX, currentY);
                currentY += Math.floor(infoWidth * 0.09); 
            };

            drawField('Pegawai', user);
            drawField('Kegiatan', kegiatan);
            drawField('Deskripsi', deskripsi, false);
            drawField('Tempat / Lokasi', tempat, false);
            
            // Tanggal & Waktu bersebelahan
            currentY += Math.floor(infoWidth * 0.05);
            ctx.fillStyle = '#9ca3af';
            ctx.font = 'bold ' + Math.floor(infoWidth * 0.04) + 'px Arial';
            ctx.fillText('TANGGAL', startX, currentY);
            ctx.fillText('WAKTU', startX + Math.floor(infoWidth * 0.48), currentY);
            currentY += Math.floor(infoWidth * 0.06);
            ctx.fillStyle = '#1f2937';
            ctx.font = 'bold ' + Math.floor(infoWidth * 0.05) + 'px Arial';
            ctx.fillText(tanggal, startX, currentY);
            ctx.fillText(waktu, startX + Math.floor(infoWidth * 0.48), currentY);

            // Watermark di pojok kanan bawah
            ctx.fillStyle = '#9ca3af';
            ctx.font = 'italic ' + Math.floor(infoWidth * 0.03) + 'px Arial';
            const watermarkText = 'ALPHA-BPS Kota Sukabumi';
            const watermarkWidth = ctx.measureText(watermarkText).width;
            ctx.fillText(watermarkText, canvas.width - watermarkWidth - 20, canvas.height - 20);

            // Trigger Download
            const link = document.createElement('a');
            link.download = `Bukti_${tipe.split(' ')[1]}_${user}_${tanggal.replace(/ /g, '_')}.jpg`;
            link.href = canvas.toDataURL('image/jpeg', 0.9);
            link.click();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('search');
            const autocompleteResults = document.getElementById('autocomplete-results');
            const clearSearchBtn = document.getElementById('clearSearchBtn');
            let timeoutId;

            // Toggle clear button visibility and fetch autocomplete
            searchInput.addEventListener('input', function() {
                clearTimeout(timeoutId);
                const query = this.value;

                // Show/hide clear button
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
                                        // Update tombol x jika sudah terisi
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

            // Handle clear button click
            if (clearSearchBtn) {
                clearSearchBtn.addEventListener('click', function() {
                    searchInput.value = '';
                    clearSearchBtn.style.display = 'none';
                    autocompleteResults.style.display = 'none';
                    document.getElementById('filterForm').submit(); // Submit to reset page
                });
            }

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!searchInput.contains(e.target) && !autocompleteResults.contains(e.target)) {
                    autocompleteResults.style.display = 'none';
                }
            });
        });
    </script>
@endsection
