<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'ALPHA BPS')</title>
    
    <!-- FontAwesome untuk Ikon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Memanggil file CSS Utama -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body>

    <!-- Memanggil Sidebar Sesuai Role -->
    @if(auth()->check() && auth()->user()->role === 'admin')
        @include('layouts.sidebar-admin')
    @else
        @include('layouts.sidebar-user')
    @endif
    <div class="sidebar-overlay" id="sidebar-overlay"></div>

    <div class="main-wrapper">
        <!-- Memanggil Header -->
        @include('layouts.header')

        <!-- Area Konten Utama -->
        <main class="content">
            @yield('content')
        </main>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var menuToggle = document.getElementById('menu-toggle');
            var sidebar = document.querySelector('.sidebar');
            var overlay = document.getElementById('sidebar-overlay');

            function toggleSidebar() {
                if (!sidebar || !overlay) return;
                sidebar.classList.toggle('active');
                overlay.classList.toggle('active');
            }

            if (menuToggle) {
                menuToggle.addEventListener('click', toggleSidebar);
            }
            if (overlay) {
                overlay.addEventListener('click', toggleSidebar);
            }
        });
    </script>

    <div id="global_modal_konfirmasi" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.6); z-index: 9999; justify-content: center; align-items: center; backdrop-filter: blur(3px);">
        <div style="background: #fff; padding: 25px 20px; border-radius: 12px; width: 90%; max-width: 400px; text-align: center; box-shadow: 0 10px 25px rgba(0,0,0,0.2); animation: scaleUp 0.3s ease-out;">
            <div id="gmk_icon_bg" style="width: 60px; height: 60px; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 15px auto;">
                <i id="gmk_icon" class="fas fa-question" style="font-size: 1.8rem;"></i>
            </div>
            <h3 id="gmk_title" style="margin: 0 0 10px 0; color: #1e3a8a;">Judul</h3>
            <p id="gmk_message" style="color: #555; font-size: 0.95rem; line-height: 1.5; margin-bottom: 25px;">Pesan</p>
            <div style="display: flex; gap: 10px;">
                <button type="button" id="gmk_btn_batal" onclick="document.getElementById('global_modal_konfirmasi').style.display='none'" style="flex: 1; padding: 12px; background: #f3f4f6; color: #4b5563; border: none; border-radius: 8px; font-weight: bold; cursor: pointer;">
                    Batal
                </button>
                <button type="button" id="gmk_btn_yakin" style="flex: 1; padding: 12px; border: none; border-radius: 8px; font-weight: bold; cursor: pointer; color: white;">
                    Yakin
                </button>
            </div>
        </div>
    </div>
    
    <style>
        @keyframes scaleUp { from { transform: scale(0.8); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>

    <script>
        let aksiKonfirmasiAktif = null;
        function panggilModalKonfirmasi(judul, pesan, iconClass, warnaTema, teksTombol, aksiLanjutan, teksBatal = 'Batal') {
            // Ganti Teks
            document.getElementById('gmk_title').innerText = judul;
            document.getElementById('gmk_message').innerHTML = pesan;
            document.getElementById('gmk_icon').className = iconClass;
            
            // Ganti Tema Warna Dinamis 
            document.getElementById('gmk_icon').style.color = warnaTema;
            document.getElementById('gmk_icon_bg').style.background = warnaTema + '20'; 
            document.getElementById('gmk_btn_yakin').style.background = warnaTema;
            document.getElementById('gmk_btn_yakin').innerHTML = teksTombol;

            // Jika teksBatal null atau kosong, sembunyikan tombol batal (mode pesan/alert)
            const btnBatal = document.getElementById('gmk_btn_batal');
            if (!teksBatal) {
                btnBatal.style.display = 'none';
            } else {
                btnBatal.style.display = 'block';
                btnBatal.innerText = teksBatal;
            }

            // Tampilkan Modal
            document.getElementById('global_modal_konfirmasi').style.display = 'flex';
            aksiKonfirmasiAktif = aksiLanjutan;
        }
        document.getElementById('gmk_btn_yakin').addEventListener('click', function() {
            document.getElementById('global_modal_konfirmasi').style.display = 'none';
            if(typeof aksiKonfirmasiAktif === 'function') {
                aksiKonfirmasiAktif(); 
            }
        });
    </script>
</body>
</html>