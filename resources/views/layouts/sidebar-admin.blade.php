<style>
    .sidebar {
        /* 1. PENGUNCIAN PERMANEN */
        width: 250px !important;
        min-width: 250px !important;
        max-width: 250px !important;
        flex-shrink: 0 !important;
        height: 100vh;
        background: #24272bff;

        /* 2. LAYOUT ATAS-BAWAH */
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .sidebar-brand {
        display: flex;
        flex-direction: column;
        justify-content: flex-start;
        align-items: flex-start;
        padding: 20px 13px; 
        background: transparent !important; /* Menghapus warna bawaan dari sidebar.css */
        border-bottom: 1px solid rgba(255,255,255,0.05); 
    }

    .brand-logo {
        width: 103px; 
        height: auto;
        margin-right: 0;
        margin-bottom: 8px;
        display: block;
    }

    .brand-text {
        margin: 0;
        color: #ffffff;
        font-family: inherit;
        font-size: 0.80rem;
        font-weight: 600;
        line-height: 1.2;
        text-align: left;
    }

    .sidebar-menu {
        list-style: none;
        padding: 20px 15px;
        margin: 0;
    }

    .sidebar-menu li { margin-bottom: 5px; }

    .sidebar-footer {
        padding: 15px;
        border-top: 1px solid rgba(255,255,255,0.05);
        text-align: center;
        display: flex;
        flex-direction: column; 
        align-items: center; 
        justify-content: center;
    }

    .sidebar-footer img {
        width: 50px;
        margin: 0 0 10px 0; 
        display: block;
    }
 
    /* 3. PERSIAPAN MOBILE */
    @media (max-width: 768px) {
        .sidebar {
            position: fixed;
            left: -250px; 
            transition: 0.3s;
        }
        .sidebar.active {
            left: 0;
        }
        .sidebar-brand {
            padding: 15px 15px; 
        }
        .brand-logo {
            width: 90px; 
        }
        .brand-text {
            font-size: 0.9rem;
        }
    }
</style>

<aside class="sidebar">
    <div>
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo_alpha.png') }}" alt="Logo Alpha" class="brand-logo">
            <p class="brand-text">Aplikasi laporan harian</p>
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </li>
            <li> 
                <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">
                    <i class="fas fa-user-edit"></i> Kelola Pengguna
                </a>
            </li>
            <li>
                <a href="{{ route('admin.kegiatan.index') }}" class="{{ request()->routeIs('admin.kegiatan.*') ? 'active' : '' }}">
                    <i class="fas fa-clipboard-list"></i> Kelola Kegiatan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">
                    <i class="fas fa-file-signature"></i> Kelola Laporan
                </a>
            </li>
            <li>
                <a href="{{ route('admin.info-bps.index') }}" class="{{ request()->routeIs('admin.info-bps.*') ? 'active' : '' }}">
                    <i class="fas fa-newspaper"></i> Info BPS Kota Sukabumi
                </a>
            </li>
            <li>
                <a href="{{ route('profil-bps') }}" class="{{ request()->routeIs('profil-bps') ? 'active' : '' }}">
                    <i class="fas fa-building"></i> Profil BPS
                </a>
            </li>
        </ul>
    </div>

    <div class="sidebar-footer">
        <img src="{{ asset('images/logo_bps.png') }}" alt="Logo BPS">
        <p>Badan Pusat Statistik<br>Kota Sukabumi</p>
    </div>
</aside>