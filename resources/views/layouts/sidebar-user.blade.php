<style>
    .sidebar {
        /* 1. PENGUNCIAN PERMANEN */
        width: 250px !important;
        min-width: 250px !important;
        max-width: 250px !important;
        flex-shrink: 0 !important;
        height: 100vh;
        background: #24272bff; /* Disesuaikan dengan warna navbar (#2b3035) */

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
        justify-content: center; 
        align-items: center;
        padding: 20px 13px; 
        background: transparent !important; /* Menghapus warna bawaan dari sidebar.css */
        border-bottom: 1px solid rgba(255,255,255,0.05); 
    }

    .brand-logo {
        width: 103px; 
        height: auto;
        margin-right: 0; 
        display: block;
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
    }
</style>

<aside class="sidebar">
    <div>
        <div class="sidebar-brand">
            <img src="{{ asset('images/logo_alpha.png') }}" alt="Logo Alpha" class="brand-logo">
        </div>
        
        <ul class="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar"></i> Dashboard
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.create') }}" class="{{ request()->routeIs('laporan.create') ? 'active' : '' }}">
                    <i class="fas fa-file-signature"></i> Laporan
                </a>
            </li>
            <li>
                <a href="{{ route('laporan.history') }}" class="{{ request()->routeIs('laporan.history') ? 'active' : '' }}">
                    <i class="fas fa-history"></i> Riwayat Laporan
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