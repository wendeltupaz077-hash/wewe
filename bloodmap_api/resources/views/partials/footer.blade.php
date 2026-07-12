<footer class="footer">
    <div class="container footer-grid">
        <div>
            <a href="{{ route('home') }}" class="logo" style="color:white;margin-bottom:1rem;display:flex;align-items:center;gap:0.5rem;">
                <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:32px;height:32px;object-fit:contain;border-radius:4px;">
                <span style="font-weight:600;">SmartBlood PH</span>
            </a>
            <p style="margin:0;line-height:1.7;font-size:0.95rem;">
                Smart Blood Bank System for centralized inventory tracking, donor matching,
                and emergency escalation across facilities and PRC chapters.
            </p>
        </div>
        <div>
            <h4>Explore</h4>
            <ul>
                <li><a href="{{ route('about') }}">About Us</a></li>
                <li><a href="{{ route('features') }}">Features</a></li>
                <li><a href="{{ route('how-it-works') }}">How It Works</a></li>
                <li><a href="{{ route('stock-status') }}">Stock Status</a></li>
            </ul>
        </div>
        <div>
            <h4>Resources</h4>
            <ul>
                <li><a href="{{ route('download') }}">Download App</a></li>
                <li><a href="{{ route('contact') }}">Contact</a></li>
                <li><a href="{{ route('portal.login') }}">Staff Portal</a></li>
            </ul>
        </div>
        <div>
            <h4>Privacy</h4>
            <ul>
                <li><a href="#">Data Privacy (RA 10173)</a></li>
                <li><a href="#">Donor Protection Policy</a></li>
            </ul>
            <p style="margin-top:1rem;font-size:0.8rem;opacity:0.6;">
                Donor contact info is never publicly exposed.
            </p>
        </div>
    </div>
    <div class="container footer-bottom">
        &copy; {{ date('Y') }} SmartBlood PH — Smart Blood Bank System. Capstone Project.
    </div>
</footer>
