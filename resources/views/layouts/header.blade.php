<header class="topbar">
    @if(auth()->check() && auth()->user()->role === 'admin')
        <div class="topbar-row-1">
            <div class="topbar-brand">
                <button type="button" class="hamburger" id="menu-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="brand-content">
                    <h2 class="brand-name" style="margin: 0; color: #ffffff;">@yield('header_title', 'Dashboard') @if(auth()->check() && request()->routeIs('dashboard')) {{ auth()->user()->username }} @endif</h2>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
    @else
        <div class="topbar-row-1">
            <div class="topbar-brand">
                <button type="button" class="hamburger" id="menu-toggle" aria-label="Toggle menu">
                    <i class="fas fa-bars"></i>
                </button>
                <div class="brand-content">
                    <img src="{{ asset('images/logo_alpha.png') }}" alt="Logo ALPHA" style="height: 30px; width: auto; margin-bottom: 4px;">
                    <p class="brand-subtitle">Aplikasi Laporan Harian</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn-logout">Logout</button>
            </form>
        </div>
        
        <div class="topbar-row-2">
            <h2>@yield('header_title', 'Dashboard') @if(auth()->check() && request()->routeIs('dashboard')) {{ auth()->user()->username }} @endif</h2>
        </div>
    @endif
</header>