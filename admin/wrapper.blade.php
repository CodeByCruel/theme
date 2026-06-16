{{-- BSDK V1 Admin Wrapper - Injects CSS variables into admin panel --}}
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
        'logo_path' => '/extensions/bsdkv1/assets/logo.svg',
        'glow_enabled' => 'true',
        'animations_enabled' => 'true',
        'gradient_enabled' => 'true',
    ];

    $settings = array_merge($defaults, $bsdkSettings);
@endphp

<style>
:root {
    --bsdk-primary: {{ $settings['primary_color'] }};
    --bsdk-primary-hover: {{ $settings['primary_color'] }}dd;
    --bsdk-secondary: {{ $settings['secondary_color'] }};
    --bsdk-accent: {{ $settings['accent_color'] }};
    --bsdk-danger: {{ $settings['danger_color'] }};
    --bsdk-warning: {{ $settings['warning_color'] }};
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

<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family={{ str_replace(' ', '+', $settings['font_family'] }}:wght@300;400;500;600;700;800;900&family={{ str_replace(' ', '+', $settings['font_mono'] }}:wght@400;500;600&display=swap" rel="stylesheet">

<script>
    // Make panel name available globally
    window.BSDK = {
        name: '{{ $settings['panel_name'] }}',
        logo: '{{ $settings['logo_path'] }}',
        glow: {{ $settings['glow_enabled'] === 'true' ? 'true' : 'false' }},
        animations: {{ $settings['animations_enabled'] === 'true' ? 'true' : 'false' }},
    };
</script>
