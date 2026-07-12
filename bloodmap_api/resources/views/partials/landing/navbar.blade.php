@php
    $navLinks = [
        ['label' => 'Home', 'route' => 'home'],
        ['label' => 'Donor Directory', 'route' => 'donor-directory'],
        ['label' => 'Our Mission', 'route' => 'about'],
        ['label' => 'FAQ', 'route' => 'faq'],
        ['label' => 'Blog', 'route' => 'blog'],
        ['label' => 'Contact', 'route' => 'contact'],
    ];
@endphp

<header class="hm-navbar">
    <nav class="hm-navbar-inner">
        <a href="{{ route('home') }}" class="hm-logo" style="display:flex;align-items:center;gap:0.5rem;">
            <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:32px;height:32px;object-fit:contain;">
            <span style="font-weight:600;">SmartBlood <span class="hm-logo-ph">PH</span></span>
        </a>

        <ul class="hm-nav-links">
            @foreach ($navLinks as $link)
                <li>
                    <a href="{{ route($link['route']) }}"
                       class="hm-nav-link {{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                        {{ $link['label'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <a href="{{ route('donor-directory') }}" class="hm-btn-nav">Get Started</a>

        <button class="hm-nav-toggle" id="hmNavToggle" aria-label="Toggle menu" aria-expanded="false">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="21" y2="18"/>
            </svg>
        </button>
    </nav>

    <div class="hm-nav-mobile" id="hmNavMobile">
        @foreach ($navLinks as $link)
            <a href="{{ route($link['route']) }}"
               class="{{ request()->routeIs($link['route']) ? 'is-active' : '' }}">
                {{ $link['label'] }}
            </a>
        @endforeach
        <a href="{{ route('donor-directory') }}" class="hm-btn-nav">Get Started</a>
    </div>
</header>
