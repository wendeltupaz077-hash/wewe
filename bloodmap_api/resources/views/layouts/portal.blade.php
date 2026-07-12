<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Portal') — SmartBlood PH</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/smartblood.css') }}">
    @stack('styles')
</head>
<script>
    // Early theme application to avoid flash: if user has local preference, apply immediately
    try {
        const dm = localStorage.getItem('portal:dark_mode');
        if (dm === '1') {
            document.documentElement.classList.add('dark-mode');
        }
    } catch (e) {
        // ignore
    }
</script>
<body class="portal-body{{ auth()->check() && auth()->user()->getPreference('dark_mode', false) ? ' dark-mode' : '' }}">
    <div class="portal-shell">
        <aside class="portal-sidebar" id="portalSidebar">
            <div class="logo portal-logo" style="display:flex;align-items:center;gap:0.5rem;cursor: default; pointer-events: none;">
                <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:32px;height:32px;object-fit:contain;border-radius:4px;">
                <span style="font-weight:600;">SmartBlood PH</span>
            </div>
            <nav class="portal-nav">
                <a href="{{ route('portal.dashboard') }}" class="{{ request()->routeIs('portal.dashboard') ? 'active' : '' }}">
                    Dashboard
                </a>
                <a href="{{ route('portal.stock') }}" class="{{ request()->routeIs('portal.stock') ? 'active' : '' }}">
                    Stock Status
                </a>
                <a href="{{ route('portal.facilities.index') }}" class="{{ request()->routeIs('portal.facilities.*') ? 'active' : '' }}">
                    Facilities
                </a>
                <a href="{{ route('portal.inventory') }}" class="{{ request()->routeIs('portal.inventory') ? 'active' : '' }}">
                    Inventory
                </a>
                <a href="{{ route('portal.requests') }}" class="{{ request()->routeIs('portal.requests') ? 'active' : '' }}">
                    Requests
                </a>
                <a href="{{ route('portal.donors') }}" class="{{ request()->routeIs('portal.donors') ? 'active' : '' }}">
                    Donors
                </a>
                @if(Auth::check() && Auth::user()->isAdminUser())
                <a href="{{ route('portal.users.index') }}" class="{{ request()->routeIs('portal.users.*') ? 'active' : '' }}">
                    Registered Users
                </a>
                @endif
                <a href="{{ route('portal.reports') }}" class="{{ request()->routeIs('portal.reports') ? 'active' : '' }}">
                    Reports
                </a>
                @if(Auth::check() && Auth::user()->isSuperAdmin())
                <a href="{{ route('portal.admins.index') }}" class="{{ request()->routeIs('portal.admins.*') ? 'active' : '' }}">
                    Admin Management
                </a>
                @endif
                <a href="{{ route('portal.settings') }}" class="{{ request()->routeIs('portal.settings') ? 'active' : '' }}">
                    Settings
                </a>
            </nav>
            <div class="portal-sidebar-footer">
                <div class="portal-user">
                    @if(auth()->user()->profile_picture)
                    <img src="{{ Storage::url(auth()->user()->profile_picture) }}" alt="Profile" class="portal-avatar" style="width:48px;height:48px;border-radius:50%;object-fit:cover;">
                    @else
                    <div class="portal-avatar">{{ strtoupper(substr(auth()->user()->fullname ?? auth()->user()->name, 0, 1)) }}</div>
                    @endif
                    <div>
                        <strong>{{ auth()->user()->fullname ?? auth()->user()->name }}</strong>
                        <small>{{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</small>
                    </div>
                </div>
                <form action="{{ route('portal.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm">Logout</button>
                </form>
            </div>
        </aside>
        <div id="portalSidebarOverlay" class="portal-sidebar-overlay"></div>

        <div class="portal-main">
            <header class="portal-topbar" style="display:flex;align-items:center;gap:1rem;position:relative;">
                <button class="nav-toggle portal-toggle" id="portalToggle" aria-label="Toggle menu">☰</button>
                <h1 class="portal-page-title" style="flex:1;">@yield('page-title', 'Dashboard')</h1>
                <a href="{{ route('portal.stock') }}" class="btn btn-outline btn-sm">
                    Stock View
                </a>
                <div style="position:relative;">
                    <button id="notificationsBtn" class="btn btn-ghost btn-sm" style="position:relative;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                        <span id="notificationBadge" class="notification-badge" style="display:none;position:absolute;top:-4px;right:-4px;background:#c41e3a;color:white;border-radius:9999px;padding:2px 6px;font-size:0.7rem;font-weight:700;">0</span>
                    </button>
                    <!-- Notifications Dropdown -->
                    <div id="notificationsDropdown" class="notifications-dropdown" style="display:none;position:absolute;top:100%;right:0;margin-top:0.5rem;background:white;border:1px solid #e5e7eb;border-radius:0.75rem;box-shadow:0 10px 30px rgba(0,0,0,0.15);width:360px;max-height:400px;overflow:auto;z-index:1000;">
                        <div style="padding:1rem;border-bottom:1px solid #e5e7eb;display:flex;justify-content:space-between;align-items:center;">
                            <h4 style="margin:0;">Notifications</h4>
                            <a href="{{ route('portal.notifications') }}" class="btn btn-ghost btn-sm">View All</a>
                        </div>
                        <div id="notificationsList" style="padding:0.5rem;">
                            <div style="padding:1rem;text-align:center;color:#6b7280;">Loading...</div>
                        </div>
                    </div>
                </div>
            </header>
            <div class="portal-content">
                @if(session('success'))
                    <div class="alert alert-success auto-hide-alert">{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="alert alert-error auto-hide-alert">{{ session('error') }}</div>
                @endif
                @yield('content')
            </div>
        </div>
    </div>

    <!-- Toasts + Modal root -->
    <div id="toastContainer" style="position:fixed;top:1rem;right:1rem;z-index:1100;display:grid;gap:0.5rem;"></div>
    <div id="modalRoot" style="display:none;">
        <div id="globalModal" style="position:fixed;inset:0;display:flex;align-items:center;justify-content:center;z-index:1200;">
            <div id="modalOverlay" style="position:absolute;inset:0;background:rgba(0,0,0,0.5);"></div>
            <div id="modalCard" style="position:relative;background:#fff;border-radius:12px;padding:1.25rem;max-width:640px;width:94%;box-shadow:0 18px 60px rgba(0,0,0,0.25);">
                <h3 id="modalTitle" style="margin:0 0 0.5rem;">Title</h3>
                <div id="modalBody" style="color:#374151;margin-bottom:1rem;">Body</div>
                <div style="display:flex;gap:0.5rem;justify-content:flex-end;">
                    <button id="modalCancel" class="btn btn-ghost">Cancel</button>
                    <button id="modalConfirm" class="btn btn-primary">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/smartblood.js') }}"></script>
    <script>
        // Ensure early-applied html dark-mode class is reflected on body for CSS that targets body.dark-mode
        document.addEventListener('DOMContentLoaded', function () {
            try {
                if (document.documentElement.classList.contains('dark-mode')) {
                    document.body.classList.add('dark-mode');
                }
            } catch (e) {}
            // Brand toggle: reuse header portalToggle if available
            try {
                const brandBtn = document.getElementById('brandToggle');
                const headerToggle = document.getElementById('portalToggle');
                if (brandBtn && headerToggle) {
                    brandBtn.addEventListener('click', function (e) {
                        e.preventDefault();
                        headerToggle.click();
                    });
                }
            } catch (e) {}
        });
    </script>
    <script>
        // Auto-hide alerts after 3 seconds
        document.addEventListener('DOMContentLoaded', () => {
            const alerts = document.querySelectorAll('.auto-hide-alert');
            alerts.forEach(alert => {
                setTimeout(() => {
                    alert.style.transition = 'opacity 0.5s, transform 0.5s';
                    alert.style.opacity = '0';
                    alert.style.transform = 'translateY(-10px)';
                    setTimeout(() => {
                        alert.remove();
                    }, 500);
                }, 3000);
            });
        });

        // Sidebar toggle handled in public/js/smartblood.js (consolidated)

        // Notifications functionality
        const notificationsBtn = document.getElementById('notificationsBtn');
        const notificationsDropdown = document.getElementById('notificationsDropdown');
        const notificationsList = document.getElementById('notificationsList');
        const notificationBadge = document.getElementById('notificationBadge');
        const unreadCountUrl = '{{ route("portal.api.notifications.unread-count") }}';
        const latestUrl = '{{ route("portal.api.notifications.latest") }}';

        // Toggle dropdown
        notificationsBtn?.addEventListener('click', (e) => {
            e.stopPropagation();
            notificationsDropdown.style.display = notificationsDropdown.style.display === 'none' ? 'block' : 'none';
            if (notificationsDropdown.style.display === 'block') {
                loadLatestNotifications();
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!notificationsDropdown.contains(e.target) && e.target !== notificationsBtn) {
                notificationsDropdown.style.display = 'none';
            }
        });

        // Load unread count and update badge
        async function loadUnreadCount() {
            try {
                const res = await fetch(unreadCountUrl);
                const data = await res.json();
                if (data.count > 0) {
                    notificationBadge.style.display = 'flex';
                    notificationBadge.textContent = data.count;
                } else {
                    notificationBadge.style.display = 'none';
                }
            } catch (err) {
                console.error('Failed to load unread count', err);
            }
        }

        // Load latest notifications for dropdown
        async function loadLatestNotifications() {
            try {
                const res = await fetch(latestUrl);
                const notifications = await res.json();
                if (notifications.length === 0) {
                    notificationsList.innerHTML = '<div style="padding:1rem;text-align:center;color:#6b7280;">No notifications yet.</div>';
                } else {
                    notificationsList.innerHTML = notifications.map(n => `
                        <div style="padding:0.75rem;border-radius:0.5rem;background:${n.is_read ? 'transparent' : 'rgba(225,6,0,0.05)'};margin-bottom:0.5rem;">
                            <h5 style="margin:0 0 0.25rem 0;font-size:0.9rem;">${n.title}</h5>
                            <p style="margin:0;color:#6b7280;font-size:0.8rem;">${n.message}</p>
                            <small style="color:#9ca3af;font-size:0.7rem;">Just now</small>
                        </div>
                    `).join('');
                }
            } catch (err) {
                console.error('Failed to load notifications', err);
                notificationsList.innerHTML = '<div style="padding:1rem;text-align:center;color:#6b7280;">Failed to load.</div>';
            }
        }

        // Initial load and polling every 30 seconds for realtime updates
        loadUnreadCount();
        setInterval(loadUnreadCount, 30000);
    </script>
    @stack('scripts')
</body>
</html>
