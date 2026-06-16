<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSDK Panel — Game Server Management</title>

    {{-- Favicon --}}
    <link rel="icon" type="image/svg+xml" href="{{ asset('assets/bsdk/favicon.svg') }}">

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    {{-- Compiled Assets --}}
    @vite(['resources/scripts/main.ts', 'resources/styles/main.css'])

    {{-- Theme CSS --}}
    <link rel="stylesheet" href="{{ asset('assets/bsdk/theme.css') }}">

    @if(isset($pageComponent))
        @viteReactEntry($pageComponent)
    @endif

    {{-- Read settings from DB (with JSON fallback) --}}
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

        // Fallback to JSON config file
        if (empty($bsdkSettings)) {
            $configPath = storage_path('app/bsdk-theme.json');
            if (file_exists($configPath)) {
                $bsdkSettings = json_decode(file_get_contents($configPath), true) ?: [];
            }
        }

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
            'logo_path' => '/assets/bsdk/logo.svg',
            'favicon_path' => '/assets/bsdk/favicon.svg',
            'login_bg' => '/assets/bsdk/background.svg',
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

        $cfg = array_merge($defaults, $bsdkSettings);

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

        $primaryRgb = bsdk_hex2rgb($cfg['primary_color']);
        $accentRgb = bsdk_hex2rgb($cfg['accent_color']);
        $dangerRgb = bsdk_hex2rgb($cfg['danger_color']);
        $warningRgb = bsdk_hex2rgb($cfg['warning_color']);
    @endphp

    {{-- Inject CSS Variables --}}
    <style id="bsdk-vars">
        :root {
            --bsdk-primary: {{ $cfg['primary_color'] }};
            --bsdk-primary-hover: {{ $cfg['primary_color'] }}dd;
            --bsdk-primary-rgb: {{ $primaryRgb }};
            --bsdk-secondary: {{ $cfg['secondary_color'] }};
            --bsdk-accent: {{ $cfg['accent_color'] }};
            --bsdk-accent-rgb: {{ $accentRgb }};
            --bsdk-danger: {{ $cfg['danger_color'] }};
            --bsdk-danger-rgb: {{ $dangerRgb }};
            --bsdk-warning: {{ $cfg['warning_color'] }};
            --bsdk-warning-rgb: {{ $warningRgb }};
            --bsdk-bg: {{ $cfg['bg_primary'] }};
            --bsdk-bg2: {{ $cfg['bg_secondary'] }};
            --bsdk-bg3: {{ $cfg['bg_card'] }};
            --bsdk-bg4: {{ $cfg['bg_elevated'] }};
            --bsdk-text: {{ $cfg['text_primary'] }};
            --bsdk-text2: {{ $cfg['text_secondary'] }};
            --bsdk-text3: {{ $cfg['text_muted'] }};
            --bsdk-border: {{ $cfg['border_color'] }};
            --bsdk-border-hover: {{ $cfg['border_hover'] }};
            --bsdk-radius: {{ $cfg['border_radius'] }};
            --bsdk-font: '{{ $cfg['font_family'] }}', system-ui, sans-serif;
            --bsdk-font-mono: '{{ $cfg['font_mono'] }}', monospace;

            /* Shorthand vars for theme.css compatibility */
            --p: {{ $cfg['primary_color'] }};
            --p-rgb: {{ $primaryRgb }};
            --p-hover: {{ $cfg['primary_color'] }}dd;
            --s: {{ $cfg['secondary_color'] }};
            --s-rgb: {{ $accentRgb }};
            --a: {{ $cfg['accent_color'] }};
            --a-rgb: {{ $accentRgb }};
            --d: {{ $cfg['danger_color'] }};
            --d-rgb: {{ $dangerRgb }};
            --w: {{ $cfg['warning_color'] }};
            --w-rgb: {{ $warningRgb }};
            --bg: {{ $cfg['bg_primary'] }};
            --bg2: {{ $cfg['bg_secondary'] }};
            --bg3: {{ $cfg['bg_card'] }};
            --bg4: {{ $cfg['bg_elevated'] }};
            --tx: {{ $cfg['text_primary'] }};
            --tx2: {{ $cfg['text_secondary'] }};
            --tx3: {{ $cfg['text_muted'] }};
            --bd: {{ $cfg['border_color'] }};
            --bd-h: {{ $cfg['border_hover'] }};
            --r: {{ $cfg['border_radius'] }};
            --rl: 12px;
            --font: '{{ $cfg['font_family'] }}', system-ui, sans-serif;
            --mono: '{{ $cfg['font_mono'] }}', monospace;
        }
    </style>

    <script>
        document.title = '{{ $cfg['panel_name'] }} — {{ $cfg['panel_tagline'] }}';
        window.BSDK = {
            name: @json($cfg['panel_name']),
            tagline: @json($cfg['panel_tagline']),
            logo: @json($cfg['logo_path']),
            glow: {{ $cfg['glow_enabled'] === 'true' ? 'true' : 'false' }},
            animations: {{ $cfg['animations_enabled'] === 'true' ? 'true' : 'false' }},
            gradient: {{ $cfg['gradient_enabled'] === 'true' ? 'true' : 'false' }},
            particles: {{ $cfg['particle_bg'] === 'true' ? 'true' : 'false' }},
            compact: {{ $cfg['compact_mode'] === 'true' ? 'true' : 'false' }},
            cardStyle: @json($cfg['card_style']),
            buttonStyle: @json($cfg['button_style']),
            sidebarStyle: @json($cfg['sidebar_style']),
        };
    </script>

    {{-- Custom CSS --}}
    @if(!empty($cfg['custom_css']))
    <style id="bsdk-custom-css">
    {!! $cfg['custom_css'] !!}
    </style>
    @endif
</head>
<body>
    <div id="app"></div>

    @yield('content')

    {{-- Particle canvas --}}
    @if($cfg['particle_bg'] === 'true')
    <canvas id="bsdk-particles" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;pointer-events:none;"></canvas>
    <script>
    (function(){
        var c=document.getElementById('bsdk-particles');if(!c)return;
        var x=c.getContext('2d'),p=[],W=function(){c.width=innerWidth;c.height=innerHeight};
        W();addEventListener('resize',W);
        function P(){this.x=Math.random()*c.width;this.y=Math.random()*c.height;this.vx=(Math.random()-.5)*.3;this.vy=(Math.random()-.5)*.3;this.s=Math.random()*2+.5;this.a=Math.random()*.3+.1}
        P.prototype=function(){this.x+=this.vx;this.y+=this.vy;(this.x<0||this.x>c.width||this.y<0||this.y>c.height)&&(this.x=Math.random()*c.width,this.y=Math.random()*c.height)};
        P.prototype.draw=function(){x.beginPath();x.arc(this.x,this.y,this.s,0,Math.PI*2);x.fillStyle='{{ $cfg['primary_color'] }}';x.globalAlpha=this.a;x.fill();x.globalAlpha=1};
        for(var i=0;i<50;i++)p.push(new P);
        (function loop(){x.clearRect(0,0,c.width,c.height);p.forEach(function(q){q.draw();q.x+=q.vx;q.y+=q.vy;(q.x<0||q.x>c.width||q.y<0||q.y>c.height)&&(q.x=Math.random()*c.width,q.y=Math.random()*c.height)});for(var i=0;i<p.length;i++)for(var j=i+1;j<p.length;j++){var dx=p[i].x-p[j].x,dy=p[i].y-p[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<150){x.beginPath();x.moveTo(p[i].x,p[i].y);x.lineTo(p[j].x,p[j].y);x.strokeStyle='{{ $cfg['primary_color'] }}';x.globalAlpha=(1-d/150)*.08;x.stroke();x.globalAlpha=1}}requestAnimationFrame(loop)})();
    })();
    </script>
    @endif

    {{-- Custom JS --}}
    @if(!empty($cfg['custom_js']))
    <script id="bsdk-custom-js">
    {!! $cfg['custom_js'] !!}
    </script>
    @endif
</body>
</html>
