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

    {{-- Inline CSS Variables from config --}}
    @php
        $configPath = storage_path('app/bsdk-theme.json');
        $config = file_exists($configPath) ? json_decode(file_get_contents($configPath), true) : [];
        $defaults = [
            'primary' => '#00d4ff',
            'secondary' => '#7b68ee',
            'accent' => '#00ff88',
            'danger' => '#ff4466',
            'warning' => '#ffaa00',
            'bg' => '#0a0e17',
            'bg2' => '#111827',
            'bg3' => '#1a2332',
            'bg4' => '#243044',
            'text' => '#e4e8f0',
            'text2' => '#8899aa',
            'text3' => '#5a6a7a',
            'radius' => '8px',
            'radius-lg' => '12px',
            'font' => 'Inter',
            'font-mono' => 'JetBrains Mono',
            'panel_name' => 'BSDK Panel',
            'tagline' => 'Game Server Management',
        ];
        $cfg = array_merge($defaults, $config);
    @endphp

    <style id="bsdk-vars">
        :root {
            --bsdk-primary: {{ $cfg['primary'] }};
            --bsdk-secondary: {{ $cfg['secondary'] }};
            --bsdk-accent: {{ $cfg['accent'] }};
            --bsdk-danger: {{ $cfg['danger'] }};
            --bsdk-warning: {{ $cfg['warning'] }};
            --bsdk-bg: {{ $cfg['bg'] }};
            --bsdk-bg2: {{ $cfg['bg2'] }};
            --bsdk-bg3: {{ $cfg['bg3'] }};
            --bsdk-bg4: {{ $cfg['bg4'] }};
            --bsdk-text: {{ $cfg['text'] }};
            --bsdk-text2: {{ $cfg['text2'] }};
            --bsdk-text3: {{ $cfg['text3'] }};
            --bsdk-radius: {{ $cfg['radius'] }};
            --bsdk-radius-lg: {{ $cfg['radius-lg'] }};
            --bsdk-font: '{{ $cfg['font'] }}', system-ui, sans-serif;
            --bsdk-font-mono: '{{ $cfg['font-mono'] }}', monospace;
        }
    </style>

    <script>
        document.title = '{{ $cfg['panel_name'] }} — {{ $cfg['tagline'] }}';
    </script>
</head>
<body>
    <div id="app"></div>

    @yield('content')

    {{-- Particle canvas for login --}}
    <canvas id="bsdk-particles" style="position:fixed;top:0;left:0;width:100vw;height:100vh;z-index:0;pointer-events:none;"></canvas>
    <script>
    (function(){
        var c=document.getElementById('bsdk-particles');if(!c)return;
        var x=c.getContext('2d'),p=[],W=function(){c.width=innerWidth;c.height=innerHeight};
        W();addEventListener('resize',W);
        function P(){this.x=Math.random()*c.width;this.y=Math.random()*c.height;this.vx=(Math.random()-.5)*.3;this.vy=(Math.random()-.5)*.3;this.s=Math.random()*2+.5;this.a=Math.random()*.3+.1}
        P.prototype=function(){this.x+=this.vx;this.y+=this.vy;(this.x<0||this.x>c.width||this.y<0||this.y>c.height)&&(this.x=Math.random()*c.width,this.y=Math.random()*c.height)};
        P.prototype.draw=function(){x.beginPath();x.arc(this.x,this.y,this.s,0,Math.PI*2);x.fillStyle='{{ $cfg['primary'] }}';x.globalAlpha=this.a;x.fill();x.globalAlpha=1};
        for(var i=0;i<50;i++)p.push(new P);
        (function loop(){x.clearRect(0,0,c.width,c.height);p.forEach(function(q){q.draw();q.x+=q.vx;q.y+=q.vy;(q.x<0||q.x>c.width||q.y<0||q.y>c.height)&&(q.x=Math.random()*c.width,q.y=Math.random()*c.height)});for(var i=0;i<p.length;i++)for(var j=i+1;j<p.length;j++){var dx=p[i].x-p[j].x,dy=p[i].y-p[j].y,d=Math.sqrt(dx*dx+dy*dy);if(d<150){x.beginPath();x.moveTo(p[i].x,p[i].y);x.lineTo(p[j].x,p[j].y);x.strokeStyle='{{ $cfg['primary'] }}';x.globalAlpha=(1-d/150)*.08;x.stroke();x.globalAlpha=1}}requestAnimationFrame(loop)})();
    })();
    </script>
</body>
</html>
