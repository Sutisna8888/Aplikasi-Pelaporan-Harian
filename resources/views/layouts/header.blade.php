<header class="topbar">
    <button type="button" class="hamburger" id="menu-toggle" aria-label="Toggle menu">
        <i class="fas fa-bars"></i>
    </button>
    <h2>@yield('header_title', 'Dashboard')</h2>
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit" class="btn-logout">Logout</button>
    </form>
</header>