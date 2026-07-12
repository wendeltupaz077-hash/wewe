<footer class="hm-footer">
    <div class="hm-footer-bg hm-breathe"></div>
    <div class="hm-footer-inner">
        <div class="hm-footer-cta">
            <h2>Every Drop <span class="hm-glow hm-text-arterial">Keeps Someone Alive</span></h2>
            <p>Join the living network. Register as a donor or connect a facility today — the next emergency won't wait.</p>
            <a href="{{ route('donor-directory') }}" class="hm-btn hm-btn-primary hm-heartbeat">Find a Donation Center</a>
        </div>

        <div class="hm-footer-grid">
            <div>
                <div class="hm-logo" style="margin-bottom:0.75rem;display:flex;align-items:center;gap:0.5rem;">
                    <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:28px;height:28px;object-fit:contain;border-radius:4px;">
                    <span style="font-weight:600;">SmartBlood PH</span>
                </div>
                <p class="hm-text-muted" style="font-size:0.875rem;line-height:1.6;">
                    Smart blood bank inventory management connecting donors, facilities, and emergency responders across the Philippines.
                </p>
            </div>

            <div>
                <h4>Navigate</h4>
                <ul>
                    <li><a href="{{ route('donor-directory') }}">Donor Directory</a></li>
                    <li><a href="{{ route('about') }}">Our Mission</a></li>
                    <li><a href="{{ route('faq') }}">FAQ</a></li>
                    <li><a href="{{ route('blog') }}">Blog</a></li>
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <ul>
                    <li>
                        <a href="mailto:info@smartblood.ph" style="display:flex;align-items:center;gap:0.5rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/></svg>
                            info@smartblood.ph
                        </a>
                    </li>
                    <li>
                        <a href="tel:+6329811000" style="display:flex;align-items:center;gap:0.5rem;">
                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.127.96.361 1.903.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.907.339 1.85.573 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
                            (02) 8981-1000
                        </a>
                    </li>
                    <li><a href="{{ route('contact') }}">Contact Form</a></li>
                </ul>
            </div>

            <div>
                <h4>Follow</h4>
                <div class="hm-social-links">
                    <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="hm-social-link" aria-label="Facebook">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="hm-social-link" aria-label="Instagram">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="2" width="20" height="20" rx="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="hm-social-link" aria-label="Twitter">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M23 3a10.9 10.9 0 0 1-3.14 1.53 4.48 4.48 0 0 0-7.86 3v1A10.66 10.66 0 0 1 3 4s-4 9 5 13a11.64 11.64 0 0 1-7 2c9 5 20 0 20-11.5a4.5 4.5 0 0 0-.08-.83A7.72 7.72 0 0 0 23 3z"/></svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="hm-footer-bottom">
            <p>&copy; {{ date('Y') }} SmartBlood PH. All rights reserved.</p>
            <div class="hm-footer-legal">
                <a href="#">Privacy Policy</a>
                <a href="#">Terms of Service</a>
            </div>
        </div>
    </div>
</footer>
