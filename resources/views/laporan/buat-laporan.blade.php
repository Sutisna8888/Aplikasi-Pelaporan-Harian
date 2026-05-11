@extends('layouts.master')

@section('title', ' Input Laporan Harian -ALPHA')
@section('header_title', 'Input Laporan Harian')

@section('content')
<div class="section-panel" style="max-width: 800px; margin: 0 auto; background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); position: relative;">
    
    @if ($errors->any())
        <div style="background-color: #fee2e2; color: #b91c1c; padding: 15px; border-radius: 8px; margin-bottom: 20px;">
            <strong>Oops! Ada kesalahan:</strong>
            <ul style="margin-left: 20px; margin-top: 10px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(isset($laporanAktif) && $laporanAktif)
        
        <div style="background: #eff6ff; border: 1px solid #bfdbfe; padding: 20px; border-radius: 8px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 15px;">
                <i class="fas fa-spinner fa-spin" style="color: #3b82f6; font-size: 1.5rem;"></i>
                <h3 style="margin: 0; color: #1e3a8a;">Kegiatan Sedang Berjalan</h3>
            </div>
            <table style="width: 100%; color: #444; font-size: 0.95rem; border-collapse: collapse;">
                <tr>
                    <td style="padding: 8px 0; width: 35%; vertical-align: top;"><strong>Kegiatan</strong></td>
                    <td style="padding: 8px 0; width: 5%; vertical-align: top;">:</td>
                    <td style="padding: 8px 0; width: 60%; vertical-align: top;">{{ $laporanAktif->kegiatan->nama_kegiatan ?? 'Kegiatan' }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; vertical-align: top;"><strong>Deskripsi</strong></td>
                    <td style="padding: 8px 0; vertical-align: top;">:</td>
                    <td style="padding: 8px 0; vertical-align: top;">{{ $laporanAktif->deskripsi }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; vertical-align: top;"><strong>Lokasi</strong></td>
                    <td style="padding: 8px 0; vertical-align: top;">:</td>
                    <td style="padding: 8px 0; vertical-align: top;">{{ $laporanAktif->lokasi_teks }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px 0; vertical-align: top;"><strong>Waktu Mulai</strong></td>
                    <td style="padding: 8px 0; vertical-align: top;">:</td>
                    <td style="padding: 8px 0; vertical-align: top;">
                        <span style="background: #3b82f6; color: white; padding: 3px 8px; border-radius: 4px; display: inline-block;">
                            {{ \Carbon\Carbon::parse($laporanAktif->jam_mulai)->format('H:i') }} WIB
                        </span>
                    </td>
                </tr>
            </table>
        </div>

        <form id="formTahap2" action="{{ route('laporan.updateSelesai', $laporanAktif->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <input type="hidden" id="lokasi_teks" value="{{ $laporanAktif->lokasi_teks }}">
            <input type="hidden" name="foto_selesai_base64" id="foto_selesai_base64" required>
            <input type="file" id="input_foto_selesai" accept="image/*" capture="environment" style="display: none;">

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button type="button" id="btn_buka_kamera_selesai" style="padding: 15px; background-color: #3b82f6; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    <i class="fas fa-camera" style="margin-right: 8px;"></i> Ambil Bukti Selesai
                </button>

                <button type="submit" id="btn_submit_selesai" style="display: none; padding: 15px; background-color: #10b981; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem; cursor: pointer;">
                    <i class="fas fa-check-circle" style="margin-right: 8px;"></i> Akhiri Kegiatan & Kirim
                </button>
            </div>
        </form>

    @else
        
        <form id="formTahap1" action="{{ route('laporan.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <input type="hidden" name="foto_mulai_base64" id="foto_mulai_base64" required>
            <input type="file" id="input_foto_mulai" accept="image/*" capture="environment" style="display: none;">

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #555; margin-bottom: 8px; font-weight: 500;">Jenis Kegiatan</label>
                <select name="kegiatan_id" id="kegiatan_id" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background: #fff;">
                    <option value="">-- Pilih Jenis --</option>
                    @foreach($kegiatans as $kegiatan)
                        <option value="{{ $kegiatan->id }}">{{ $kegiatan->nama_kegiatan }}</option>
                    @endforeach
                </select>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="display: block; color: #555; margin-bottom: 8px; font-weight: 500;">Deskripsi Kegiatan</label>
                <input type="text" name="deskripsi" id="deskripsi" placeholder="Misal: Entri Data Sensus Pertanian" required style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px;">
            </div>

            <div style="margin-bottom: 30px;">
                <label style="display: block; color: #555; margin-bottom: 8px; font-weight: 500;">Lokasi</label>
                <input type="text" name="lokasi_teks" id="lokasi_teks" placeholder="Kosongkan jika di Kantor BPS Kota Sukabumi" style="width: 100%; padding: 12px; border: 1px solid #ccc; border-radius: 6px; background: #f9f9f9;">
            </div>

            <div style="display: flex; flex-direction: column; gap: 15px;">
                <button type="button" id="btn_trigger_mulai" style="padding: 15px; background-color: #2563eb; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem; cursor: pointer;">
                    <i class="fas fa-file-signature" style="margin-right: 8px;"></i> Buat Laporan
                </button>

                <button type="button" id="btn_buka_kamera_mulai" style="display: none; padding: 15px; background-color: #a18800; color: white; border: none; border-radius: 6px; font-weight: bold; cursor: pointer;">
                    <i class="fas fa-camera" style="margin-right: 8px;"></i> Ambil Bukti Mulai
                </button>

                <button type="submit" id="btn_submit_mulai" style="display: none; padding: 15px; background-color: #10b981; color: white; border: none; border-radius: 8px; font-weight: bold; font-size: 1.1rem; cursor: pointer;">
                    <i class="fas fa-play" style="margin-right: 8px;"></i> Mulai Kegiatan
                </button>
            </div>
        </form>

    @endif

    <div id="modal_error" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(3px);">
        <div style="background: #fff; padding: 25px 20px; border-radius: 12px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: scaleUp 0.3s ease-out;">
            <div style="background: #fee2e2; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-exclamation-triangle" style="color: #ef4444; font-size: 2rem;"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #7f1d1d;">Form Belum Lengkap</h3>
            <p style="color: #555; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px;">
                Silakan pilih jenis kegiatan dan isi deskripsi terlebih dahulu sebelum membuat laporan.
            </p>
            <button type="button" id="btn_tutup_error" style="width: 100%; padding: 12px; background: #ef4444; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                Mengerti
            </button>
        </div>
    </div>

    <div id="modal_konfirmasi" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(3px);">
        <div style="background: #fff; padding: 25px 20px; border-radius: 12px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: scaleUp 0.3s ease-out;">
            <div style="background: #eff6ff; width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i class="fas fa-play" style="color: #3b82f6; font-size: 1.8rem;"></i>
            </div>
            <h3 style="margin: 0 0 10px 0; color: #1e3a8a;">Mulai Kegiatan?</h3>
            <p style="color: #555; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px;">
                Anda yakin ingin memulai kegiatan ini?<br>Status laporan akan berubah menjadi <strong>"Sedang Berjalan"</strong>.
            </p>
            <div style="display: flex; gap: 10px;">
                <button type="button" id="btn_batal_modal" style="flex: 1; padding: 12px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Batal
                </button>
                <button type="button" id="btn_yakin_modal" style="flex: 1; padding: 12px; background: #10b981; color: white; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Ya, Mulai
                </button>
            </div>
        </div>
    </div>

    <style>
        @keyframes scaleUp {
            from { transform: scale(0.8); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* --- Perbaikan Responsive Mobile --- */
        @media (max-width: 768px) {
            .section-panel {
                padding: 20px 15px !important;
                margin: 0 !important;
                border-radius: 0 !important; /* Full width di HP lebih rapi */
                box-shadow: none !important;
            }

            .section-panel h3 {
                font-size: 1.1rem;
            }

            /* Perbaikan Tabel di HP */
            .section-panel table tr {
                display: flex;
                flex-direction: column;
                margin-bottom: 10px;
                border-bottom: 1px solid #eee;
                padding-bottom: 5px;
            }
            .section-panel table td {
                width: 100% !important;
                padding: 2px 0 !important;
            }
            .section-panel table td:nth-child(2) {
                display: none; /* Sembunyikan titik dua (:) */
            }

            /* Perbaikan Input & Select */
            select, input {
                font-size: 16px !important; /* Mencegah auto-zoom di iOS */
            }
        }
    </style>

</div> 

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Fungsi Watermark Asli ---
        function prosesFotoWatermark(file, targetInputBase64, callbackSukses) {
            if (!file) return;

            const reader = new FileReader();
            reader.onload = function(event) {
                const img = new Image();
                img.onload = function() {
                    const canvas = document.createElement('canvas');
                    const ctx = canvas.getContext('2d');

                    const MAX_WIDTH = 1200;
                    let width = img.width;
                    let height = img.height;

                    if (width > MAX_WIDTH) {
                        height = Math.round((height * MAX_WIDTH) / width);
                        width = MAX_WIDTH;
                    }
                    
                    canvas.width = width;
                    canvas.height = height;

                    ctx.drawImage(img, 0, 0, width, height);

                    const fontSize = Math.max(12, Math.floor(width * 0.03)); 
                    ctx.font = "bold " + fontSize + "px Arial"; 
                    
                    const shadowSize = Math.max(1, Math.floor(fontSize * 0.1));
                    ctx.shadowColor = "rgba(0, 0, 0, 0.8)"; 
                    ctx.shadowBlur = shadowSize * 2;
                    ctx.shadowOffsetX = shadowSize;
                    ctx.shadowOffsetY = shadowSize;

                    const sekarang = new Date();
                    const formatWaktu = sekarang.toLocaleDateString('id-ID') + ' ' + sekarang.toLocaleTimeString('id-ID');
                    
                    const inputLokasi = document.getElementById('lokasi_teks');
                    const lokasi = inputLokasi ? inputLokasi.value : "Kantor BPS Kota Sukabumi";
                    
                    const marginKiri = Math.floor(width * 0.03); 
                    const marginBawah = height;
                    const spasiBaris = fontSize * 1.5; 

                    ctx.fillStyle = "#fbbf24"; 
                    ctx.fillText("ALPHA-BPS Kota Sukabumi", marginKiri, marginBawah - (spasiBaris * 2.8));
                    
                    ctx.fillStyle = "#ffffff"; 
                    ctx.fillText( lokasi, marginKiri, marginBawah - (spasiBaris * 1.6));
                    ctx.fillText(formatWaktu, marginKiri, marginBawah - (spasiBaris * 0.6));

                    const base64Data = canvas.toDataURL('image/jpeg', 0.75);
                    targetInputBase64.value = base64Data; 

                    callbackSukses();
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        // ================= LOGIKA JAVASCRIPT ALUR BARU DENGAN POP-UP =================

        // --- 1. INISIALISASI ELEMEN TAHAP 1 (MULAI) ---
        const btnTriggerMulai = document.getElementById('btn_trigger_mulai');
        const btnBukaKameraMulai = document.getElementById('btn_buka_kamera_mulai');
        const inputFotoMulai = document.getElementById('input_foto_mulai');
        const btnSubmitMulai = document.getElementById('btn_submit_mulai');
        const inputFotoMulaiBase64 = document.getElementById('foto_mulai_base64');
        const inputLokasi = document.getElementById('lokasi_teks');
        const formTahap1 = document.getElementById('formTahap1');

        // Elemen Modal Lokal (Hanya untuk Error Form)
        const modalError = document.getElementById('modal_error');
        const btnTutupError = document.getElementById('btn_tutup_error');

        if (btnTriggerMulai) {
            // ALUR 1: Validasi awal sebelum ambil foto
            btnTriggerMulai.addEventListener('click', () => {
                if (!document.getElementById('kegiatan_id').value || !document.getElementById('deskripsi').value) {
                    modalError.style.display = 'flex'; // Gunakan modal error lokal
                    return;
                }

                // Auto-fill Lokasi
                if (!inputLokasi.value || inputLokasi.value.trim() === '') {
                    inputLokasi.value = "Kantor BPS Kota Sukabumi";
                }

                btnTriggerMulai.style.display = 'none';
                btnBukaKameraMulai.style.display = 'block';
            });

            btnTutupError.addEventListener('click', () => {
                modalError.style.display = 'none';
            });

            // ALUR 2: Buka Kamera
            btnBukaKameraMulai.addEventListener('click', () => {
                inputFotoMulai.click();
            });

            // ALUR 3: Proses Foto Mulai
            inputFotoMulai.addEventListener('change', function(e) {
                btnBukaKameraMulai.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                prosesFotoWatermark(e.target.files[0], inputFotoMulaiBase64, function() {
                    btnBukaKameraMulai.innerHTML = '<i class="fas fa-check"></i> Bukti Mulai Tersimpan';
                    btnBukaKameraMulai.style.backgroundColor = '#6b7280';
                    btnBukaKameraMulai.disabled = true;
                    btnSubmitMulai.style.display = 'block'; 
                });
            });

            // ALUR 4: Konfirmasi Mulai (MENGGUNAKAN MODAL GLOBAL)
            btnSubmitMulai.addEventListener('click', function(e) {
                e.preventDefault(); 
                panggilModalKonfirmasi(
                    'Mulai Kegiatan?', 
                    'Anda yakin ingin memulai kegiatan ini?<br>Status laporan akan berubah menjadi <strong>"Sedang Berjalan"</strong>.', 
                    'fas fa-play', 
                    '#2563eb', // Biru
                    '<i class="fas fa-play"></i> Ya, Mulai', 
                    function() {
                        btnSubmitMulai.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                        formTahap1.submit(); 
                    }
                );
            });
        }

        // --- 2. INISIALISASI ELEMEN TAHAP 2 (SELESAI) ---
        const btnBukaKameraSelesai = document.getElementById('btn_buka_kamera_selesai');
        const inputFotoSelesai = document.getElementById('input_foto_selesai');
        const btnSubmitSelesai = document.getElementById('btn_submit_selesai');
        const inputFotoSelesaiBase64 = document.getElementById('foto_selesai_base64');
        const formTahap2 = document.getElementById('formTahap2'); 

        if (btnBukaKameraSelesai) {
            // ALUR 1: Buka Kamera Selesai
            btnBukaKameraSelesai.addEventListener('click', () => {
                inputFotoSelesai.click();
            });

            // ALUR 2: Proses Foto Selesai
            inputFotoSelesai.addEventListener('change', function(e) {
                btnBukaKameraSelesai.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Memproses...';
                prosesFotoWatermark(e.target.files[0], inputFotoSelesaiBase64, function() {
                    btnBukaKameraSelesai.innerHTML = '<i class="fas fa-check"></i> Bukti Selesai Tersimpan';
                    btnBukaKameraSelesai.style.backgroundColor = '#6b7280';
                    btnBukaKameraSelesai.disabled = true;
                    btnSubmitSelesai.style.display = 'block'; 
                });
            });

            // ALUR 3: Konfirmasi Selesai (MENGGUNAKAN MODAL GLOBAL)
            btnSubmitSelesai.addEventListener('click', function(e) {
                e.preventDefault();
                panggilModalKonfirmasi(
                    'Kirim Laporan?', 
                    'Anda yakin mengakhiri kegiatan ini?<br>Data waktu dan foto akan dikirim ke server.', 
                    'fas fa-paper-plane', 
                    '#10b981', // Hijau
                    '<i class="fas fa-check-circle"></i> Kirim Laporan', 
                    function() {
                        btnSubmitSelesai.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mengirim...';
                        formTahap2.submit(); 
                    }
                );
            });
        }
    });
</script>
@endsection