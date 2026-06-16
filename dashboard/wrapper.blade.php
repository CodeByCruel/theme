{{-- BSDK V1 Dashboard Wrapper - Injects CSS variables and fonts into the client dashboard --}}
@php
    $bsdkSettings = [];
    try {
        if (Schema::hasTable('bsdkv1_settings')) {
            $rows = DB::table('bsdkv1_settings')->get();
            foreach ($rows as $row) {
                $bsdkSettings[$row->setting_key] = $row->setting_value;
            }
        }
    } catch (\Exception $e) {}

    $defaults = [
        'primary_color' => '#00d4ff',
        'secondary_color' => '#7b68ee',
        'accent_color' => '#00ff88',
        'danger_color' => '#ff4466',
        'warning_color' => '#ffaa00',
        'bg_primary' => '#0a0e17',
        'bg_secondary' => '#111827',
        'bg_card' => '#1a2332',
        'bg_elevated' => '#243044',
        'text_primary' => '#e4e8f0',
        'text_secondary' => '#8899aa',
        'text_muted' => '#5a6a7a',
        'border_color' => 'rgba(255, 255, 255, 0.08)',
        'border_hover' => 'rgba(255, 255, 255, 0.15)',
        'border_radius' => '8px',
        'font_family' => 'Inter',
        'font_mono' => 'JetBrains Mono',
        'panel_name' => 'BSDK Panel',
        'panel_tagline' => 'Game Server Management',
        'logo_path' => '/extensions/bsdkv1/assets/logo.svg',
        'favicon_path' => '/extensions/bsdkv1/assets/favicon.ico',
        'login_bg' => '/extensions/bsdkv1/assets/background.svg',
        'glow_enabled' => 'true',
        'animations_enabled' => 'true',
        'gradient_enabled' => 'true',
        'particle_bg' => 'true',
        'compact_mode' => 'false',
        'sidebar_style' => 'modern',
        'card_style' => 'glass',
        'button_style' => 'rounded',
        'custom_css' => '',
        'custom_js' => '',
    ];

    $settings = array_merge($defaults, $bsdkSettings);

    // Convert hex to RGB for CSS variables
    function bsdk_hex2rgb($hex) {
        $hex = ltrim($hex, '#');
        if (strlen($hex) === 3) {
            $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
        }
        return implode(', ', [
            hexdec(substr($hex, 0, 2)),
            hexdec(substr($hex, 2, 2)),
            hexdec(substr($hex, 4, 2)),
        ]);
    }

    $primaryRgb = bsdk_hex2rgb($settings['primary_color']);
    $accentRgb = bsdk_hex2rgb($settings['accent_color']);
    $dangerRgb = bsdk_hex2rgb($settings['danger_color']);
    $warningRgb = bsdk_hex2rgb($settings['warning_color']);
@endphp

{{-- Inject CSS variables --}}
<style id="bsdk-variables">
:root {
    --bsdk-primary: {{ $settings['primary_color'] }};
    --bsdk-primary-hover: {{ $settings['primary_color'] }}dd;
    --bsdk-primary-rgb: {{ $primaryRgb }};
    --bsdk-secondary: {{ $settings['secondary_color'] }};
    --bsdk-accent: {{ $settings['accent_color'] }};
    --bsdk-accent-rgb: {{ $accentRgb }};
    --bsdk-danger: {{ $settings['danger_color'] }};
    --bsdk-danger-rgb: {{ $dangerRgb }};
    --bsdk-warning: {{ $settings['warning_color'] }};
    --bsdk-warning-rgb: {{ $warningRgb }};
    --bsdk-bg: {{ $settings['bg_primary'] }};
    --bsdk-bg2: {{ $settings['bg_secondary'] }};
    --bsdk-bg3: {{ $settings['bg_card'] }};
    --bsdk-bg4: {{ $settings['bg_elevated'] }};
    --bsdk-text: {{ $settings['text_primary'] }};
    --bsdk-text2: {{ $settings['text_secondary'] }};
    --bsdk-text3: {{ $settings['text_muted'] }};
    --bsdk-border: {{ $settings['border_color'] }};
    --bsdk-border-hover: {{ $settings['border_hover'] }};
    --bsdk-radius: {{ $settings['border_radius'] }};
    --bsdk-font: '{{ $settings['font_family'] }}', system-ui, sans-serif;
    --bsdk-font-mono: '{{ $settings['font_mono'] }}', monospace;
}
</style>

{{-- Google Fonts --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $settings['font_family'] }}:wght@300;400;500;600;700;800;900&family={{ str_replace(' ', '+', $settings['font_mono'] }}:wght@400;500;600&display=swap" rel="stylesheet">

{{-- Update page title --}}
<script>
    document.title = '{{ $settings['panel_name'] }} — {{ $settings['panel_tagline'] }}';
</script>

{{-- Favicon --}}
<link rel="icon" type="image/x-icon" href="{{ $settings['favicon_path'] }}">

{{-- Custom CSS --}}
@if(!empty($settings['custom_css']))
<style id="bsdk-custom-css">
{!! $settings['custom_css'] !!}
</style>
@endif

{{-- Global JS config --}}
<script>
    window.BSDK = {
        name: @json($settings['panel_name']),
        tagline: @json($settings['panel_tagline']),
        logo: @json($settings['logo_path']),
        glow: {{ $settings['glow_enabled'] === 'true' ? 'true' : 'false' }},
        animations: {{ $settings['animations_enabled'] === 'true' ? 'true' : 'false' }},
        gradient: {{ $settings['gradient_enabled'] === 'true' ? 'true' : 'false' }},
        particles: {{ $settings['particle_bg'] === 'true' ? 'true' : 'false' }},
        compact: {{ $settings['compact_mode'] === 'true' ? 'true' : 'false' }},
        cardStyle: @json($settings['card_style']),
        buttonStyle: @json($settings['button_style']),
        sidebarStyle: @json($settings['sidebar_style']),
    };
</script>

{{-- Custom JS --}}
@if(!empty($settings['custom_js']))
<script id="bsdk-custom-js">
{!! $settings['custom_js'] !!}
</script>
@endif

{{-- Particle background for login page --}}
@if($settings['particle_bg'] === 'true')
<canvas id="bsdk-particles" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;pointer-events:none;"></canvas>
<script>
(function() {
    const canvas = document.getElementById('bsdk-particles');
    if (!canvas) return;
    const ctx = canvas.getContext('2d');
    let particles = [];
    const resize = () => { canvas.width = window.innerWidth; canvas.height = window.innerHeight; };
    resize();
    window.addEventListener('resize', resize);

    class Particle {
        constructor() {
            this.reset();
        }
        reset() {
            this.x = Math.random() * canvas.width;
            this.y = Math.random() * canvas.height;
            this.vx = (Math.random() - 0.5) * 0.3;
            this.vy = (Math.random() - 0.5) * 0.3;
            this.size = Math.random() * 2 + 0.5;
            this.alpha = Math.random() * 0.3 + 0.1;
            this.color = '{{ $settings['primary_color'] }}';
        }
        update() {
            this.x += this.vx;
            this.y += this.vy;
            if (this.x < 0 || this.x > canvas.width || this.y < 0 || this.y > canvas.height) {
                this.reset();
            }
        }
        draw() {
            ctx.beginPath();
            ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
            ctx.fillStyle = this.color;
            ctx.globalAlpha = this.alpha;
            ctx.fill();
            ctx.globalAlpha = 1;
        }
    }

    for (let i = 0; i < 50; i++) {
        particles.push(new Particle());
    }

    function animate() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        particles.forEach(p => { p.update(); p.draw(); });
        
        // Draw connections
        for (let i = 0; i < particles.length; i++) {
            for (let j = i + 1; j < particles.length; j++) {
                const dx = particles[i].x - particles[j].x;
                const dy = particles[i].y - particles[j].y;
                const dist = Math.sqrt(dx * dx + dy * dy);
                if (dist < 150) {
                    ctx.beginPath();
                    ctx.moveTo(particles[i].x, particles[i].y);
                    ctx.lineTo(particles[j].x, particles[j].y);
                    ctx.strokeStyle = '{{ $settings['primary_color'] }}';
                    ctx.globalAlpha = (1 - dist / 150) * 0.08;
                    ctx.stroke();
                    ctx.globalAlpha = 1;
                }
            }
        }
        requestAnimationFrame(animate);
    }
    animate();
})();
</script>
@endif
