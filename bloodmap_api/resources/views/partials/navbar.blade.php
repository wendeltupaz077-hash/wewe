<nav class="navbar" id="navbar">
    <div class="container navbar-inner">
        <a href="{{ route('home') }}" class="logo" style="display:flex;align-items:center;gap:0.5rem;">
            <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:32px;height:32px;object-fit:contain;border-radius:4px;">
            <span style="font-weight:600;">SmartBlood PH</span>
        </a>
        <button class="nav-toggle" id="navToggle" aria-label="Toggle menu">☰</button>
        <ul class="nav-links" id="navLinks">
            <li><a href="{{ route('home') }}" class="{{ request()->routeIs('home') ? 'active' : '' }}">Home</a></li>
            <li><a href="{{ route('about') }}" class="{{ request()->routeIs('about') ? 'active' : '' }}">About</a></li>
            <li><a href="{{ route('features') }}" class="{{ request()->routeIs('features') ? 'active' : '' }}">Features</a></li>
            <li><a href="{{ route('how-it-works') }}" class="{{ request()->routeIs('how-it-works') ? 'active' : '' }}">How It Works</a></li>
            <li><a href="{{ route('stock-status') }}" class="{{ request()->routeIs('stock-status') ? 'active' : '' }}">Stock Status</a></li>
            <li><a href="{{ route('download') }}" class="{{ request()->routeIs('download') ? 'active' : '' }}">Download</a></li>
            <li><a href="{{ route('contact') }}" class="{{ request()->routeIs('contact') ? 'active' : '' }}">Contact</a></li>
            <li><a href="{{ route('portal.login') }}" class="btn btn-primary btn-sm">Staff Portal</a></li>
        </ul>
    </div>
</nav>
