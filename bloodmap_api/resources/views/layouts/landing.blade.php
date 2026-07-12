<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'SmartBlood PH') — The Living Network of Vitality</title>
    <meta name="description" content="@yield('meta', 'SmartBlood PH connects donors, facilities, and emergency responders in one real-time system across the Philippines.')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/hematic.css') }}">
    @stack('styles')
</head>
<body class="hm-body">
    <div class="hm-page">
        @include('partials.landing.navbar')
        <main class="hm-main">
            @yield('content')
        </main>
        @include('partials.landing.emergency-footer')
    </div>
    <script src="{{ asset('js/hematic.js') }}"></script>
    @stack('scripts')
</body>
</html>
