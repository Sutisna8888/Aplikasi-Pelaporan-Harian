<header class="topbar">
    <!-- Header Utama -->
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
</header>