<div class="hm-pulse-visual">
    {{-- Pulsing concentric rings --}}
    @foreach ([0, 1, 2] as $i)
        <div class="hm-pulse-ring-animation" style="animation-delay: {{ $i * 1 }}s;"></div>
    @endforeach

    {{-- Center pulse circle --}}
    <div class="hm-pulse-center">
        <div class="hm-pulse-center-bg hm-breathe"></div>

        {{-- Heartbeat SVG --}}
        <svg viewBox="0 0 300 80" class="hm-heartbeat-svg">
            <path d="M0,40 L60,40 L80,15 L100,65 L120,10 L140,70 L160,40 L200,40 L215,20 L230,60 L245,40 L300,40" 
                  fill="none" 
                  stroke="hsl(var(--arterial))" 
                  stroke-width="3" 
                  stroke-linecap="round" 
                  stroke-linejoin="round" 
                  class="hm-heartbeat-path">
            </path>
        </svg>

        {{-- BPM Display --}}
        <div class="hm-pulse-bpm hm-flicker">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline>
            </svg>
            72 <span class="hm-pulse-bpm-unit">bpm</span>
        </div>

        {{-- Live Network Pulse text --}}
        <p class="hm-pulse-label">Live Network Pulse</p>
    </div>

    {{-- Floating logo badge (top right) --}}
    <div class="hm-pulse-droplet hm-float-up">
        <img src="{{ asset('images/smartblood-logo.png') }}" alt="SmartBlood PH" style="width:100%;height:100%;object-fit:contain;border-radius:0.5rem;">
    </div>

    {{-- Lives saved counter (bottom left) --}}
    <div class="hm-pulse-counter hm-float-down">
        <p class="hm-pulse-counter-label">Lives Saved Today</p>
        <p class="hm-pulse-counter-value">1,204</p>
    </div>
</div>

<style>
    .hm-pulse-visual {
        position: relative;
        width: 100%;
        max-width: 28rem;
        margin: 0 auto;
        aspect-ratio: 1;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    {{-- Pulsing ring animations --}}
    .hm-pulse-ring-animation {
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 1px solid hsl(var(--arterial) / 0.3);
        animation: hm-pulse-ring-expand 3s ease-in-out infinite;
    }

    @keyframes hm-pulse-ring-expand {
        0% {
            transform: scale(1);
            opacity: 0.6;
        }
        50% {
            opacity: 0;
        }
        100% {
            transform: scale(1.4);
            opacity: 0.6;
        }
    }

    {{-- Center pulse circle --}}
    .hm-pulse-center {
        position: relative;
        width: 80%;
        aspect-ratio: 1;
        border-radius: 50%;
        background: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        box-shadow: 0 0 60px hsl(var(--arterial) / 0.25);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        animation: hm-center-fade-in 0.8s cubic-bezier(0.65, 0, 0.35, 1);
    }

    @keyframes hm-center-fade-in {
        from {
            opacity: 0;
            transform: scale(0.8);
        }
        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    .hm-pulse-center-bg {
        position: absolute;
        inset: 0;
        background: radial-gradient(circle at 50% 50%, hsl(var(--arterial) / 0.25), transparent 70%);
    }

    {{-- Heartbeat SVG --}}
    .hm-heartbeat-svg {
        position: relative;
        width: 80%;
        height: 4rem;
        margin-bottom: 1rem;
        z-index: 1;
    }

    .hm-heartbeat-path {
        stroke-dasharray: 1000;
        stroke-dashoffset: 1000;
        animation: hm-heartbeat-draw 2.2s linear infinite;
        filter: drop-shadow(0 0 6px hsl(var(--arterial) / 0.8));
    }

    @keyframes hm-heartbeat-draw {
        to {
            stroke-dashoffset: 0;
        }
    }

    {{-- BPM Display --}}
    .hm-pulse-bpm {
        position: relative;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: hsl(var(--arterial));
        font-family: var(--font-mono);
        font-size: 1.875rem;
        font-weight: 700;
        z-index: 1;
    }

    .hm-pulse-bpm svg {
        width: 1.5rem;
        height: 1.5rem;
    }

    .hm-pulse-bpm-unit {
        font-size: 0.875rem;
        color: hsl(var(--muted-foreground));
        font-family: var(--font-body);
    }

    {{-- Live Network Pulse Label --}}
    .hm-pulse-label {
        position: relative;
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        font-family: var(--font-mono);
        text-transform: uppercase;
        letter-spacing: 0.15em;
        margin-top: 0.5rem;
        margin-bottom: 0;
        z-index: 1;
    }

    {{-- Floating logo badge --}}
    .hm-pulse-droplet {
        position: absolute;
        top: 0.5rem;
        right: 0.5rem;
        width: 4rem;
        height: 4rem;
        border-radius: 0.5rem;
        background: transparent;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 0 30px hsl(var(--arterial) / 0.4);
        z-index: 2;
        padding: 0.25rem;
    }

    @media (min-width: 768px) {
        .hm-pulse-droplet {
            top: 0.5rem;
            right: 0.5rem;
        }
    }

    .hm-float-up {
        animation: hm-float-up-animation 3.5s cubic-bezier(0.65, 0, 0.35, 1) infinite;
    }

    @keyframes hm-float-up-animation {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-14px);
        }
    }

    {{-- Lives saved counter --}}
    .hm-pulse-counter {
        position: absolute;
        bottom: 0.5rem;
        left: 0.5rem;
        padding: 1rem 1rem;
        border-radius: 0.75rem;
        background: hsl(var(--card));
        border: 1px solid hsl(var(--border));
        box-shadow: 0 0 20px hsl(var(--arterial) / 0.15);
        z-index: 2;
    }

    @media (min-width: 768px) {
        .hm-pulse-counter {
            bottom: 1rem;
            left: 0;
        }
    }

    .hm-float-down {
        animation: hm-float-down-animation 4s cubic-bezier(0.65, 0, 0.35, 1) infinite;
        animation-delay: 0.5s;
    }

    @keyframes hm-float-down-animation {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(12px);
        }
    }

    .hm-pulse-counter-label {
        font-size: 0.75rem;
        color: hsl(var(--muted-foreground));
        margin: 0;
        font-family: var(--font-body);
    }

    .hm-pulse-counter-value {
        font-family: var(--font-mono);
        font-weight: 700;
        color: hsl(var(--arterial));
        font-size: 1.125rem;
        margin: 0;
    }
</style>
