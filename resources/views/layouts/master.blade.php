<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="robots" content="index, follow">
    <meta name="application-name" content="BSDK Panel">

    @php
        $bsdkSettings = [];
        $themeMeta = null;
        $themeVars = null;
        $themeEnforce = false;
        $pwaSettings = null;
        $addonsDecoded = [];
        $appName = 'BSDK Panel';

        try {
            if (Schema::hasTable('bsdkv1_settings')) {
                $rows = DB::table('bsdkv1_settings')->get();
                foreach ($rows as $row) {
                    $bsdkSettings[$row->setting_key] = $row->setting_value;
                }
            }
        } catch (\Exception $e) {}

        if (empty($bsdkSettings)) {
            $configPath = storage_path('app/bsdk-theme.json');
            if (file_exists($configPath)) {
                $bsdkSettings = json_decode(file_get_contents($configPath), true) ?: [];
            }
        }

        try {
            $settingsRepository = app(\Pterodactyl\Repositories\Eloquent\SettingsRepository::class);
            $raw = $settingsRepository->get('settings::app:theme:hyperv2', '{}');
            $decoded = json_decode($raw ?: '{}', true, 512, JSON_THROW_ON_ERROR);
            $themeMeta = $decoded['site']['meta'] ?? null;
            $themeVars = $decoded['variables'] ?? null;
            $themeEnforce = (bool) ($decoded['enforce'] ?? false);
            $appName = $settingsRepository->get('settings::app:name', 'BSDK Panel');

            $addonsRaw = $settingsRepository->get('settings::app:addons:hyperv2', '{}');
            $addonsDecoded = json_decode($addonsRaw ?: '{}', true, 512, JSON_THROW_ON_ERROR) ?: [];
            $pwaSettings = $addonsDecoded['addons']['pwa'] ?? null;
        } catch (\Throwable $e) {}

        $pwaEnabled = $pwaSettings['enabled'] ?? false;

        $defaults = [
            'primary_color' => '#00d4ff', 'secondary_color' => '#7b68ee',
            'accent_color' => '#00ff88', 'danger_color' => '#ff4466',
            'warning_color' => '#ffaa00', 'bg_primary' => '#0a0e17',
            'bg_secondary' => '#111827', 'bg_card' => '#1a2332',
            'bg_elevated' => '#243044', 'text_primary' => '#e4e8f0',
            'text_secondary' => '#8899aa', 'text_muted' => '#5a6a7a',
            'border_color' => 'rgba(255, 255, 255, 0.08)',
            'border_hover' => 'rgba(255, 255, 255, 0.15)',
            'border_radius' => '8px', 'font_family' => 'Inter',
            'font_mono' => 'JetBrains Mono', 'panel_name' => 'BSDK Panel',
            'panel_tagline' => 'Game Server Management',
            'logo_path' => '/assets/bsdk/logo.svg',
            'favicon_path' => '/assets/bsdk/favicon.svg',
            'login_bg' => '/DGEN/background.webp',
            'glow_enabled' => 'true', 'animations_enabled' => 'true',
            'gradient_enabled' => 'true', 'particle_bg' => 'true',
            'compact_mode' => 'false', 'sidebar_style' => 'modern',
            'card_style' => 'glass', 'button_style' => 'rounded',
            'custom_css' => '', 'custom_js' => '',
        ];

        $cfg = array_merge($defaults, $bsdkSettings);

        function bsdk_hex2rgb($hex) {
            $hex = ltrim($hex, '#');
            if (strlen($hex) === 3) $hex = $hex[0].$hex[0].$hex[1].$hex[1].$hex[2].$hex[2];
            return implode(', ', [hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2))]);
        }

        $primaryRgb = bsdk_hex2rgb($cfg['primary_color']);
        $accentRgb = bsdk_hex2rgb($cfg['accent_color']);
        $dangerRgb = bsdk_hex2rgb($cfg['danger_color']);
    @endphp

    <title>{{ $appName }} — {{ $cfg['panel_tagline'] }}</title>

    @if($themeMeta && !empty($themeMeta['faviconUrl']))
        <link rel="icon" href="{{ $themeMeta['faviconUrl'] }}">
    @else
        <link rel="apple-touch-icon" sizes="180x180" href="/favicons/apple-touch-icon.png">
        <link rel="icon" type="image/png" href="/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="shortcut icon" href="/favicons/favicon.ico">
    @endif

    @if($themeMeta && !empty($themeMeta['color']))
        <meta name="theme-color" content="{{ $themeMeta['color'] }}">
    @else
        <meta name="theme-color" content="{{ $cfg['primary_color'] }}">
    @endif

    @if($pwaEnabled)
        <link rel="manifest" href="/api/public/pwa/manifest.json?v=3">
        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="{{ $pwaSettings['status_bar_style'] ?? 'default' }}">
        <meta name="apple-mobile-web-app-title" content="{{ $pwaSettings['app_short_name'] ?? $appName }}">
    @endif

    @if($themeMeta && !empty($themeMeta['description']))
        <meta name="description" content="{{ Str::limit($themeMeta['description'], 300) }}">
        <meta property="og:description" content="{{ Str::limit($themeMeta['description'], 300) }}">
    @endif

    @if($themeMeta && !empty($themeMeta['image']))
        <meta property="og:image" content="{{ $themeMeta['image'] }}">
        <meta name="twitter:card" content="summary_large_image">
    @endif

    @if($themeMeta && !empty($themeMeta['title']))
        <meta property="og:title" content="{{ $themeMeta['title'] }}">
    @else
        <meta property="og:title" content="{{ $appName }}">
    @endif

    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">

    {{-- User data for React --}}
    @php
        $__authUser = Auth::user();
        $__vueUserJson = null;
        if ($__authUser) {
            $__authUser->loadMissing('permissionRole');
            $__vueUserJson = json_encode($__authUser->toVueObject(), JSON_HEX_TAG | JSON_UNESCAPED_UNICODE);
        }
    @endphp
    @if($__vueUserJson !== null)
        <script data-cfasync="false">window.PterodactylUser = {!! $__vueUserJson !!};</script>
    @endif

    {{-- Fonts --}}
    @php $__fontsCssV = @filemtime(public_path('assets/css/fonts.css')) ?: 0; @endphp
    <link rel="stylesheet" href="/assets/css/fonts.css?v={{ $__fontsCssV }}" media="print" onload="this.media='all'">
    <noscript><link rel="stylesheet" href="/assets/css/fonts.css?v={{ $__fontsCssV }}"></noscript>

    {{-- Core CSS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">

    @vite(['resources/scripts/main.ts', 'resources/styles/main.css'])

    <link rel="stylesheet" href="/assets/css/hyper.css?t={{ @filemtime(public_path('assets/css/hyper.css')) ?: time() }}">
    <link rel="stylesheet" href="{{ asset('assets/bsdk/theme.css') }}">

    @if(isset($pageComponent))
        @viteReactEntry($pageComponent)
    @endif

    {{-- Theme variables from DB --}}
    @if($themeVars && is_array($themeVars) && count($themeVars) > 0)
        <style id="hyper-theme-vars">
            :root {
                @foreach($themeVars as $key => $value)
                    @if(Str::startsWith($key, '--hyper-') && !empty($value) && !Str::endsWith($key, '-rgb'))
                        {{ $key }}: {{ $value }}{{ $themeEnforce ? ' !important' : '' }};
                    @endif
                @endforeach
                @if(isset($themeVars['--hyper-primary']) && preg_match('/^#([a-f0-9]{6})(?:[a-f0-9]{2})?$/i', $themeVars['--hyper-primary'], $m))
                    --hyper-primary-rgb: {{ hexdec(substr($m[1], 0, 2)) }}, {{ hexdec(substr($m[1], 2, 2)) }}, {{ hexdec(substr($m[1], 4, 2)) }}{{ $themeEnforce ? ' !important' : '' }};
                @endif
                @if(isset($themeVars['--hyper-background']) && preg_match('/^#([a-f0-9]{6})(?:[a-f0-9]{2})?$/i', $themeVars['--hyper-background'], $m))
                    --hyper-background-rgb: {{ hexdec(substr($m[1], 0, 2)) }}, {{ hexdec(substr($m[1], 2, 2)) }}, {{ hexdec(substr($m[1], 4, 2)) }}{{ $themeEnforce ? ' !important' : '' }};
                @endif
            }
        </style>
        @if(isset($themeVars['--hyper-font-url']) && !empty($themeVars['--hyper-font-url']))
            <link rel="stylesheet" href="{{ $themeVars['--hyper-font-url'] }}" media="print" onload="this.media='all'">
        @endif
    @endif

    {{-- Inject BSDK CSS Variables --}}
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

    <style>
        :root { --hyper-font-family: '{{ $cfg['font_family'] }}', sans-serif !important; }
        body *:not(.fa):not(.fas):not(.far):not(.fab):not(.glyphicon):not([class^="ion-"]):not([class*=" ion-"]):not(.xterm):not(.xterm *),
        body { font-family: '{{ $cfg['font_family'] }}', sans-serif !important; }
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
    <style id="bsdk-custom-css">{!! $cfg['custom_css'] !!}</style>
    @endif

    {{-- Background preload for auth page --}}
    @php
        $__authBgUrl = null;
        $__onAuthView = \Illuminate\Support\Str::startsWith(request()->path(), 'auth') || \Illuminate\Support\Facades\Auth::guest();
        if ($__onAuthView && !empty($cfg['login_bg']) && preg_match('#^https?://#i', $cfg['login_bg'])) {
            $__authBgUrl = $cfg['login_bg'];
        }
    @endphp
    @if($__authBgUrl)
        <link rel="preload" as="image" href="{{ $__authBgUrl }}" fetchpriority="high">
    @endif
</head>
<body class="{{ $css['body'] ?? '' }}">
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

    {{-- Service Worker --}}
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                @if($pwaEnabled)
                navigator.serviceWorker.register('/service-worker.js', { scope: '/' }).catch(function(){});
                @else
                navigator.serviceWorker.register('/service-worker.js', { scope: '/' });
                @endif
            });
        }
    </script>

    {{-- Custom JS --}}
    @if(!empty($cfg['custom_js']))
    <script id="bsdk-custom-js">{!! $cfg['custom_js'] !!}</script>
    @endif
</body>
</html>
