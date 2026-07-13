(function () {
    'use strict';

    // Handle login form with loading state
    const loginForm = document.getElementById('login-form');
    const submitBtn = document.getElementById('submit-btn');
    const btnText = document.getElementById('btn-text');
    const btnSpinner = document.getElementById('btn-spinner');

    if (loginForm && submitBtn && btnText && btnSpinner) {
        loginForm.addEventListener('submit', function (e) {
            // Prevent multiple submissions
            if (submitBtn.disabled) {
                e.preventDefault();
                return;
            }

            // Set loading state
            submitBtn.disabled = true;
            btnText.textContent = 'Signing In...';
            btnSpinner.style.display = 'inline-block';
        });
    }

    // Handle password visibility toggle
    const togglePasswordBtn = document.getElementById('toggle-password');
    const passwordInput = document.getElementById('password');

    if (togglePasswordBtn && passwordInput) {
        togglePasswordBtn.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePasswordBtn.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    }

    // Real-time Philippine Standard Time
    const philippineTimeEl = document.getElementById('philippine-time');
    if (philippineTimeEl) {
        const updatePhilippineTime = () => {
            const now = new Date();
            const phTime = new Date(now.toLocaleString('en-US', { timeZone: 'Asia/Manila' }));
            
            const options = {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit',
                hour12: true,
                weekday: 'long',
                year: 'numeric',
                month: 'long',
                day: 'numeric'
            };
            
            philippineTimeEl.textContent = phTime.toLocaleString('en-US', options);
        };
        
        updatePhilippineTime();
        setInterval(updatePhilippineTime, 1000);
    }

    // Navbar scroll effect
    const navbar = document.getElementById('navbar');
    if (navbar) {
        window.addEventListener('scroll', () => {
            navbar.classList.toggle('scrolled', window.scrollY > 20);
        });
    }

    // Mobile nav toggle
    const navToggle = document.getElementById('navToggle');
    const navLinks = document.getElementById('navLinks');
    if (navToggle && navLinks) {
        navToggle.addEventListener('click', () => navLinks.classList.toggle('open'));
    }

    // Portal sidebar + overlay toggle (consolidated)
    const portalToggle = document.getElementById('portalToggle');
    const portalSidebar = document.getElementById('portalSidebar');
    const portalSidebarOverlay = document.getElementById('portalSidebarOverlay');
    const portalCloseSidebar = () => {
        portalSidebar?.classList.remove('open');
        portalSidebarOverlay?.classList.remove('open');
        if (portalToggle) portalToggle.setAttribute('aria-expanded', 'false');
    };
    const portalOpenSidebar = () => {
        portalSidebar?.classList.add('open');
        portalSidebarOverlay?.classList.add('open');
        if (portalToggle) portalToggle.setAttribute('aria-expanded', 'true');
    };

    if (portalToggle && portalSidebar) {
        portalToggle.addEventListener('click', () => {
            const isOpen = portalSidebar.classList.toggle('open');
            portalSidebarOverlay?.classList.toggle('open', isOpen);
            portalToggle.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
        });
    }

    // Clicking overlay closes sidebar
    portalSidebarOverlay?.addEventListener('click', portalCloseSidebar);

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            portalCloseSidebar();
        }
    });

    // Toggle switch behavior for forms
    document.querySelectorAll('.switch').forEach(sw => {
        const nestedInput = sw.querySelector('input[type="checkbox"]');
        const hiddenInput = sw.nextElementSibling;

        const setState = (checked) => {
            sw.classList.toggle('on', checked);
            if (hiddenInput && hiddenInput.type === 'hidden') {
                hiddenInput.value = checked ? '1' : '0';
            }
            if (nestedInput) {
                nestedInput.checked = checked;
            }
        };

        if (nestedInput) {
            setState(nestedInput.checked);
            nestedInput.addEventListener('change', () => {
                setState(nestedInput.checked);
            });
        }

        if (hiddenInput) {
            setState(hiddenInput.value === '1');
            sw.addEventListener('click', () => {
                setState(!sw.classList.contains('on'));
            });
        }
    });

    // Toast helper
    window.showToast = function (message, type = 'success', timeout = 3500) {
        const container = document.getElementById('toastContainer');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = 'toast toast-' + type;
        toast.style.padding = '0.8rem 1rem';
        toast.style.borderRadius = '8px';
        toast.style.boxShadow = '0 8px 24px rgba(0,0,0,0.08)';
        toast.style.background = type === 'error' ? '#fee2e2' : (type === 'info' ? '#e6f4ff' : '#dcfce7');
        toast.style.color = type === 'error' ? '#991b1b' : (type === 'info' ? '#1e40af' : '#166534');
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => { toast.style.opacity = '0'; toast.style.transform = 'translateY(-8px)'; }, timeout - 400);
        setTimeout(() => toast.remove(), timeout);
    };

    // Modal helper
    window.showModal = function (opts = {}) {
        const root = document.getElementById('modalRoot');
        if (!root) return;
        const modal = document.getElementById('globalModal');
        const title = document.getElementById('modalTitle');
        const body = document.getElementById('modalBody');
        const confirm = document.getElementById('modalConfirm');
        const cancel = document.getElementById('modalCancel');
        root.style.display = 'block';
        title.textContent = opts.title || 'Confirm';
        if (typeof opts.body === 'string') body.innerHTML = opts.body; else body.innerHTML = '';
        const close = () => { root.style.display = 'none'; if (opts.onClose) opts.onClose(); };
        cancel.onclick = () => { close(); };
        confirm.onclick = () => { if (opts.onConfirm) opts.onConfirm(); close(); };
    };

    // Spinner helper (minimal)
    window.showSpinner = function (show = true) {
        let s = document.getElementById('globalSpinner');
        if (show) {
            if (!s) {
                s = document.createElement('div');
                s.id = 'globalSpinner';
                s.style.position = 'fixed';
                s.style.inset = '0';
                s.style.display = 'grid';
                s.style.placeItems = 'center';
                s.style.background = 'rgba(0,0,0,0.25)';
                s.style.zIndex = '1300';
                s.innerHTML = '<div style="width:72px;height:72px;border-radius:12px;background:rgba(255,255,255,0.95);display:grid;place-items:center;"><div class="spinner" style="width:36px;height:36px;border:4px solid #eee;border-top-color: #b30000;border-radius:50%;animation:spin 1s linear infinite;"></div></div>';
                document.body.appendChild(s);
            }
        } else {
            s?.remove();
        }
    };

    // Spinner keyframes (inject if not present)
    if (!document.getElementById('spinnerKeyframes')) {
        const style = document.createElement('style');
        style.id = 'spinnerKeyframes';
        style.textContent = '@keyframes spin { to { transform: rotate(360deg); } }';
        document.head.appendChild(style);
    }

    // Replace floating bubbles with blood-vessels (sharp SVG shapes)
    const bloodCells = document.getElementById('bloodCells');
    if (bloodCells) {
        const rootStyle = getComputedStyle(document.documentElement);
        const strokeColor = rootStyle.getPropertyValue('--blood-red').trim() || '#c41e3a';
        const vesselCount = 8;
        for (let i = 0; i < vesselCount; i++) {
            const vessel = document.createElement('div');
            vessel.className = 'blood-vessel';
            const widthPct = 30 + Math.random() * 50; // percent width
            const heightVh = 6 + Math.random() * 18; // height in vh
            vessel.style.width = widthPct + '%';
            vessel.style.height = heightVh + 'vh';
            vessel.style.left = Math.random() * 100 + '%';
            vessel.style.top = (10 + Math.random() * 70) + 'vh';
            vessel.style.animationDuration = (18 + Math.random() * 32) + 's';
            vessel.style.animationDelay = (Math.random() * 8) + 's';
            vessel.style.opacity = (0.06 + Math.random() * 0.14).toFixed(2);

            const svg = `<svg viewBox="0 0 200 60" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg"><path d="M0,30 C50,10 150,50 200,30" stroke="${strokeColor}" stroke-width="6" fill="none" stroke-linecap="round" stroke-linejoin="round"/></svg>`;
            vessel.innerHTML = svg;
            bloodCells.appendChild(vessel);
        }
    }

    // Scroll reveal
    const reveals = document.querySelectorAll('.reveal');
    if (reveals.length) {
        const observer = new IntersectionObserver(
            (entries) => {
                entries.forEach((entry) => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            },
            { threshold: 0.12, rootMargin: '0px 0px -40px 0px' }
        );
        reveals.forEach((el) => observer.observe(el));
    }

    // Counter animation for stats
    document.querySelectorAll('[data-count]').forEach((el) => {
        const target = parseInt(el.dataset.count, 10);
        if (isNaN(target)) return;
        const duration = 1500;
        const start = performance.now();
        const animate = (now) => {
            const progress = Math.min((now - start) / duration, 1);
            const eased = 1 - Math.pow(1 - progress, 3);
            el.textContent = Math.floor(eased * target);
            if (progress < 1) requestAnimationFrame(animate);
            else el.textContent = target;
        };
        const statObserver = new IntersectionObserver(
            (entries) => {
                if (entries[0].isIntersecting) {
                    requestAnimationFrame(animate);
                    statObserver.disconnect();
                }
            },
            { threshold: 0.5 }
        );
        statObserver.observe(el);
    });

    // Blood drop ripple on hero CTA hover
    document.querySelectorAll('.btn-primary').forEach((btn) => {
        btn.addEventListener('mouseenter', function () {
            this.style.boxShadow = '0 8px 32px rgba(196, 30, 58, 0.5), 0 0 0 4px rgba(196, 30, 58, 0.1)';
        });
        btn.addEventListener('mouseleave', function () {
            this.style.boxShadow = '';
        });
    });

    // Blood bag toggle: transparent PNGs + stage background swap
    const bloodBagWrapper = document.querySelector('.blood-bag-wrapper');
    if (bloodBagWrapper) {
        const toggleBag = () => {
            const filled = bloodBagWrapper.classList.toggle('filled');
            bloodBagWrapper.setAttribute('aria-pressed', filled ? 'true' : 'false');
        };

        bloodBagWrapper.addEventListener('click', toggleBag);
        bloodBagWrapper.addEventListener('keydown', (event) => {
            if (event.key === 'Enter' || event.key === ' ') {
                event.preventDefault();
                toggleBag();
            }
        });
    }

    const updateFloatingLabel = (input) => {
        const formGroup = input.closest('.form-group.floating-label');
        const label = formGroup?.querySelector('label');
        if (!label) return;

        const hasValue = input.value.trim() !== '' || !input.matches(':placeholder-shown');
        if (hasValue) {
            label.style.top = '0.5rem';
            label.style.fontSize = '0.75rem';
            label.style.color = input.matches(':focus') ? '#c41e3a' : '#6b7280';
            label.style.transform = 'translateY(0)';
        } else {
            label.style.top = '50%';
            label.style.fontSize = '1rem';
            label.style.color = '#6b7280';
            label.style.transform = 'translateY(-50%)';
        }
    };

    // Floating label initialization: check inputs for existing values on load
    document.querySelectorAll('.form-group.floating-label input').forEach(input => {
        updateFloatingLabel(input);
        input.addEventListener('input', () => updateFloatingLabel(input));
        input.addEventListener('focus', () => updateFloatingLabel(input));
        input.addEventListener('blur', () => updateFloatingLabel(input));
    });

    setTimeout(() => {
        document.querySelectorAll('.form-group.floating-label input').forEach(updateFloatingLabel);
    }, 100);
})();
