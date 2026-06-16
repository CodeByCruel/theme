<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSDK V1 Theme Settings</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        :root {
            --bg: #0a0e17;
            --bg2: #111827;
            --bg3: #1a2332;
            --bg4: #243044;
            --text: #e4e8f0;
            --text2: #8899aa;
            --text3: #5a6a7a;
            --border: rgba(255,255,255,0.08);
            --primary: #00d4ff;
            --primary-hover: #00b8e6;
            --accent: #00ff88;
            --danger: #ff4466;
            --warning: #ffaa00;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            padding: 24px;
        }
        .container { max-width: 1200px; margin: 0 auto; }
        .header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 1px solid var(--border);
        }
        .header h1 {
            font-size: 28px;
            font-weight: 800;
            background: linear-gradient(135deg, var(--primary), var(--accent));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        .header .badge {
            font-family: 'JetBrains Mono', monospace;
            font-size: 11px;
            letter-spacing: 1px;
            color: var(--primary);
            padding: 4px 12px;
            border: 1px solid rgba(0,212,255,0.2);
            border-radius: 20px;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            margin-bottom: 32px;
        }
        .stat-card {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
        }
        .stat-card .label {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 1.5px;
            color: var(--text3);
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .stat-card .value {
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
        }
        .tabs {
            display: flex;
            gap: 4px;
            margin-bottom: 24px;
            background: var(--bg2);
            border-radius: 10px;
            padding: 4px;
        }
        .tab {
            flex: 1;
            padding: 10px 16px;
            border: none;
            background: transparent;
            color: var(--text2);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            border-radius: 8px;
            transition: all 0.2s;
        }
        .tab:hover { color: var(--text); background: rgba(255,255,255,0.05); }
        .tab.active { color: #000; background: var(--primary); font-weight: 600; }
        .panel {
            display: none;
            background: var(--bg2);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
        }
        .panel.active { display: block; }
        .panel h2 {
            font-size: 18px;
            font-weight: 700;
            margin-bottom: 20px;
            color: var(--text);
        }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; }
        .field { margin-bottom: 16px; }
        .field label {
            display: block;
            font-size: 12px;
            font-weight: 600;
            color: var(--text2);
            margin-bottom: 6px;
            letter-spacing: 0.3px;
        }
        .field .hint {
            font-size: 11px;
            color: var(--text3);
            margin-top: 4px;
        }
        .field input[type="text"],
        .field input[type="url"],
        .field select,
        .field textarea {
            width: 100%;
            padding: 10px 14px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            color: var(--text);
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            transition: border-color 0.2s;
        }
        .field input:focus,
        .field select:focus,
        .field textarea:focus {
            outline: none;
            border-color: var(--primary);
        }
        .field textarea { min-height: 100px; resize: vertical; font-family: 'JetBrains Mono', monospace; font-size: 12px; }
        .color-field {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .color-field input[type="color"] {
            width: 44px;
            height: 44px;
            border: 2px solid var(--border);
            border-radius: 8px;
            cursor: pointer;
            background: transparent;
            padding: 2px;
        }
        .color-field input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        .color-field input[type="color"]::-webkit-color-swatch { border: none; border-radius: 5px; }
        .color-field input[type="text"] { flex: 1; }
        .toggle-field {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 8px;
            margin-bottom: 8px;
        }
        .toggle-field .info { flex: 1; }
        .toggle-field .info .name { font-size: 13px; font-weight: 500; color: var(--text); }
        .toggle-field .info .desc { font-size: 11px; color: var(--text3); margin-top: 2px; }
        .toggle {
            position: relative;
            width: 44px;
            height: 24px;
            background: var(--bg4);
            border-radius: 12px;
            cursor: pointer;
            transition: background 0.2s;
        }
        .toggle.on { background: var(--primary); }
        .toggle::after {
            content: '';
            position: absolute;
            top: 3px;
            left: 3px;
            width: 18px;
            height: 18px;
            background: #fff;
            border-radius: 50%;
            transition: transform 0.2s;
        }
        .toggle.on::after { transform: translateX(20px); }
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 8px;
            font-family: 'Inter', sans-serif;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-primary { background: var(--primary); color: #000; }
        .btn-primary:hover { background: var(--primary-hover); box-shadow: 0 0 20px rgba(0,212,255,0.3); }
        .btn-secondary { background: var(--bg4); color: var(--text); border: 1px solid var(--border); }
        .btn-secondary:hover { background: rgba(255,255,255,0.1); }
        .btn-danger { background: var(--danger); color: #fff; }
        .btn-danger:hover { opacity: 0.9; }
        .btn-row { display: flex; gap: 10px; margin-top: 24px; }
        .preview-box {
            background: var(--bg3);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 24px;
            margin-top: 16px;
        }
        .preview-card {
            background: var(--bg4);
            border: 1px solid var(--border);
            border-radius: var(--bsdk-radius, 8px);
            padding: 16px;
            margin-bottom: 12px;
            transition: all 0.2s;
        }
        .preview-card:hover { border-color: var(--primary); }
        .preview-btn {
            display: inline-block;
            padding: 8px 16px;
            border-radius: var(--bsdk-radius, 8px);
            font-size: 12px;
            font-weight: 600;
            margin-right: 8px;
        }
        .preview-btn.primary { background: var(--primary); color: #000; }
        .preview-btn.secondary { background: var(--bg4); color: var(--text); border: 1px solid var(--border); }
        .preview-btn.accent { background: var(--accent); color: #000; }
        .preview-btn.danger { background: var(--danger); color: #fff; }
        .divider { height: 1px; background: var(--border); margin: 20px 0; }
        .section-title {
            font-family: 'JetBrains Mono', monospace;
            font-size: 10px;
            letter-spacing: 2px;
            color: var(--text3);
            text-transform: uppercase;
            margin-bottom: 16px;
        }
        @media (max-width: 768px) {
            .grid { grid-template-columns: 1fr; }
            .header { flex-direction: column; gap: 12px; text-align: center; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <h1>BSDK V1</h1>
                <p style="color: var(--text2); font-size: 14px; margin-top: 4px;">Full Theme Customization Panel</p>
            </div>
            <span class="badge">v1.0.0</span>
        </div>

        <div class="stats">
            <div class="stat-card">
                <div class="label">ACTIVE SERVERS</div>
                <div class="value">{{ $server_count }}</div>
            </div>
            <div class="stat-card">
                <div class="label">PRIMARY COLOR</div>
                <div class="value" style="color: {{ $settings['primary_color'] }}">{{ $settings['primary_color'] }}</div>
            </div>
            <div class="stat-card">
                <div class="label">THEME STATUS</div>
                <div class="value" style="color: var(--accent)">ACTIVE</div>
            </div>
            <div class="stat-card">
                <div class="label">PANEL NAME</div>
                <div class="value" style="font-size: 18px;">{{ $settings['panel_name'] }}</div>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.extensions.bsdkv1.update') }}">
            @csrf
            @method('PATCH')

            <div class="tabs">
                <button type="button" class="tab active" onclick="showTab('colors')">Colors</button>
                <button type="button" class="tab" onclick="showTab('backgrounds')">Backgrounds</button>
                <button type="button" class="tab" onclick="showTab('typography')">Typography</button>
                <button type="button" class="tab" onclick="showTab('layout')">Layout</button>
                <button type="button" class="tab" onclick="showTab('branding')">Branding</button>
                <button type="button" class="tab" onclick="showTab('effects')">Effects</button>
                <button type="button" class="tab" onclick="showTab('advanced')">Advanced</button>
            </div>

            <!-- Colors Panel -->
            <div id="panel-colors" class="panel active">
                <h2>Color Scheme</h2>
                <div class="section-title">Brand Colors</div>
                <div class="grid">
                    <div class="field">
                        <label>Primary Color</label>
                        <div class="color-field">
                            <input type="color" name="primary_color" value="{{ $settings['primary_color'] }}" onchange="this.nextElementSibling.value=this.value; updatePreview()">
                            <input type="text" name="primary_color" value="{{ $settings['primary_color'] }}" oninput="this.previousElementSibling.value=this.value; updatePreview()">
                        </div>
                    </div>
                    <div class="field">
                        <label>Secondary Color</label>
                        <div class="color-field">
                            <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] }}" onchange="this.nextElementSibling.value=this.value; updatePreview()">
                            <input type="text" name="secondary_color" value="{{ $settings['secondary_color'] }}" oninput="this.previousElementSibling.value=this.value; updatePreview()">
                        </div>
                    </div>
                    <div class="field">
                        <label>Accent Color</label>
                        <div class="color-field">
                            <input type="color" name="accent_color" value="{{ $settings['accent_color'] }}" onchange="this.nextElementSibling.value=this.value; updatePreview()">
                            <input type="text" name="accent_color" value="{{ $settings['accent_color'] }}" oninput="this.previousElementSibling.value=this.value; updatePreview()">
                        </div>
                    </div>
                    <div class="field">
                        <label>Danger Color</label>
                        <div class="color-field">
                            <input type="color" name="danger_color" value="{{ $settings['danger_color'] }}" onchange="this.nextElementSibling.value=this.value; updatePreview()">
                            <input type="text" name="danger_color" value="{{ $settings['danger_color'] }}" oninput="this.previousElementSibling.value=this.value; updatePreview()">
                        </div>
                    </div>
                    <div class="field">
                        <label>Warning Color</label>
                        <div class="color-field">
                            <input type="color" name="warning_color" value="{{ $settings['warning_color'] }}" onchange="this.nextElementSibling.value=this.value; updatePreview()">
                            <input type="text" name="warning_color" value="{{ $settings['warning_color'] }}" oninput="this.previousElementSibling.value=this.value; updatePreview()">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section-title">Text Colors</div>
                <div class="grid">
                    <div class="field">
                        <label>Primary Text</label>
                        <div class="color-field">
                            <input type="color" name="text_primary" value="{{ $settings['text_primary'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="text_primary" value="{{ $settings['text_primary'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <div class="field">
                        <label>Secondary Text</label>
                        <div class="color-field">
                            <input type="color" name="text_secondary" value="{{ $settings['text_secondary'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="text_secondary" value="{{ $settings['text_secondary'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <div class="field">
                        <label>Muted Text</label>
                        <div class="color-field">
                            <input type="color" name="text_muted" value="{{ $settings['text_muted'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="text_muted" value="{{ $settings['text_muted'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                </div>

                <div class="divider"></div>
                <div class="section-title">Border Colors</div>
                <div class="grid">
                    <div class="field">
                        <label>Border Color (CSS value)</label>
                        <input type="text" name="border_color" value="{{ $settings['border_color'] }}">
                    </div>
                    <div class="field">
                        <label>Border Hover (CSS value)</label>
                        <input type="text" name="border_hover" value="{{ $settings['border_hover'] }}">
                    </div>
                </div>
            </div>

            <!-- Backgrounds Panel -->
            <div id="panel-backgrounds" class="panel">
                <h2>Background Colors</h2>
                <div class="grid">
                    <div class="field">
                        <label>Primary Background</label>
                        <div class="color-field">
                            <input type="color" name="bg_primary" value="{{ $settings['bg_primary'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="bg_primary" value="{{ $settings['bg_primary'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <div class="field">
                        <label>Secondary Background</label>
                        <div class="color-field">
                            <input type="color" name="bg_secondary" value="{{ $settings['bg_secondary'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="bg_secondary" value="{{ $settings['bg_secondary'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <div class="field">
                        <label>Card Background</label>
                        <div class="color-field">
                            <input type="color" name="bg_card" value="{{ $settings['bg_card'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="bg_card" value="{{ $settings['bg_card'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                    <div class="field">
                        <label>Elevated Background</label>
                        <div class="color-field">
                            <input type="color" name="bg_elevated" value="{{ $settings['bg_elevated'] }}" onchange="this.nextElementSibling.value=this.value">
                            <input type="text" name="bg_elevated" value="{{ $settings['bg_elevated'] }}" oninput="this.previousElementSibling.value=this.value">
                        </div>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="section-title">Login Page</div>
                <div class="grid">
                    <div class="field">
                        <label>Login Background URL</label>
                        <input type="url" name="login_bg" value="{{ $settings['login_bg'] }}">
                        <div class="hint">URL to background image/video for login page</div>
                    </div>
                </div>
            </div>

            <!-- Typography Panel -->
            <div id="panel-typography" class="panel">
                <h2>Typography</h2>
                <div class="grid">
                    <div class="field">
                        <label>Font Family</label>
                        <select name="font_family">
                            <option value="Inter" {{ $settings['font_family'] === 'Inter' ? 'selected' : '' }}>Inter</option>
                            <option value="Roboto" {{ $settings['font_family'] === 'Roboto' ? 'selected' : '' }}>Roboto</option>
                            <option value="Open Sans" {{ $settings['font_family'] === 'Open Sans' ? 'selected' : '' }}>Open Sans</option>
                            <option value="Poppins" {{ $settings['font_family'] === 'Poppins' ? 'selected' : '' }}>Poppins</option>
                            <option value="Montserrat" {{ $settings['font_family'] === 'Montserrat' ? 'selected' : '' }}>Montserrat</option>
                            <option value="Raleway" {{ $settings['font_family'] === 'Raleway' ? 'selected' : '' }}>Raleway</option>
                            <option value="Nunito" {{ $settings['font_family'] === 'Nunito' ? 'selected' : '' }}>Nunito</option>
                            <option value="Quicksand" {{ $settings['font_family'] === 'Quicksand' ? 'selected' : '' }}>Quicksand</option>
                            <option value="Space Grotesk" {{ $settings['font_family'] === 'Space Grotesk' ? 'selected' : '' }}>Space Grotesk</option>
                            <option value="Outfit" {{ $settings['font_family'] === 'Outfit' ? 'selected' : '' }}>Outfit</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Monospace Font</label>
                        <select name="font_mono">
                            <option value="JetBrains Mono" {{ $settings['font_mono'] === 'JetBrains Mono' ? 'selected' : '' }}>JetBrains Mono</option>
                            <option value="Fira Code" {{ $settings['font_mono'] === 'Fira Code' ? 'selected' : '' }}>Fira Code</option>
                            <option value="Source Code Pro" {{ $settings['font_mono'] === 'Source Code Pro' ? 'selected' : '' }}>Source Code Pro</option>
                            <option value="IBM Plex Mono" {{ $settings['font_mono'] === 'IBM Plex Mono' ? 'selected' : '' }}>IBM Plex Mono</option>
                            <option value="Cascadia Code" {{ $settings['font_mono'] === 'Cascadia Code' ? 'selected' : '' }}>Cascadia Code</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Layout Panel -->
            <div id="panel-layout" class="panel">
                <h2>Layout Options</h2>
                <div class="grid">
                    <div class="field">
                        <label>Border Radius</label>
                        <select name="border_radius">
                            <option value="0px" {{ $settings['border_radius'] === '0px' ? 'selected' : '' }}>Sharp (0px)</option>
                            <option value="4px" {{ $settings['border_radius'] === '4px' ? 'selected' : '' }}>Slight (4px)</option>
                            <option value="8px" {{ $settings['border_radius'] === '8px' ? 'selected' : '' }}>Default (8px)</option>
                            <option value="12px" {{ $settings['border_radius'] === '12px' ? 'selected' : '' }}>Rounded (12px)</option>
                            <option value="16px" {{ $settings['border_radius'] === '16px' ? 'selected' : '' }}>Extra (16px)</option>
                            <option value="20px" {{ $settings['border_radius'] === '20px' ? 'selected' : '' }}>Pill (20px)</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Sidebar Style</label>
                        <select name="sidebar_style">
                            <option value="modern" {{ $settings['sidebar_style'] === 'modern' ? 'selected' : '' }}>Modern</option>
                            <option value="compact" {{ $settings['sidebar_style'] === 'compact' ? 'selected' : '' }}>Compact</option>
                            <option value="classic" {{ $settings['sidebar_style'] === 'classic' ? 'selected' : '' }}>Classic</option>
                            <option value="hidden" {{ $settings['sidebar_style'] === 'hidden' ? 'selected' : '' }}>Hidden (toggle)</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Card Style</label>
                        <select name="card_style">
                            <option value="glass" {{ $settings['card_style'] === 'glass' ? 'selected' : '' }}>Glassmorphism</option>
                            <option value="solid" {{ $settings['card_style'] === 'solid' ? 'selected' : '' }}>Solid</option>
                            <option value="outlined" {{ $settings['card_style'] === 'outlined' ? 'selected' : '' }}>Outlined</option>
                            <option value="neon" {{ $settings['card_style'] === 'neon' ? 'selected' : '' }}>Neon Glow</option>
                        </select>
                    </div>
                    <div class="field">
                        <label>Button Style</label>
                        <select name="button_style">
                            <option value="rounded" {{ $settings['button_style'] === 'rounded' ? 'selected' : '' }}>Rounded</option>
                            <option value="sharp" {{ $settings['button_style'] === 'sharp' ? 'selected' : '' }}>Sharp</option>
                            <option value="pill" {{ $settings['button_style'] === 'pill' ? 'selected' : '' }}>Pill</option>
                            <option value="gradient" {{ $settings['button_style'] === 'gradient' ? 'selected' : '' }}>Gradient</option>
                        </select>
                    </div>
                </div>
                <div class="divider"></div>
                <div class="section-title">Display Options</div>
                <div class="toggle-field">
                    <div class="info">
                        <div class="name">Compact Mode</div>
                        <div class="desc">Reduce spacing and padding throughout the panel</div>
                    </div>
                    <div class="toggle {{ $settings['compact_mode'] === 'true' ? 'on' : '' }}" onclick="toggleSwitch(this)" data-name="compact_mode"></div>
                    <input type="hidden" name="compact_mode" value="{{ $settings['compact_mode'] }}">
                </div>
            </div>

            <!-- Branding Panel -->
            <div id="panel-branding" class="panel">
                <h2>Branding</h2>
                <div class="grid">
                    <div class="field">
                        <label>Panel Name</label>
                        <input type="text" name="panel_name" value="{{ $settings['panel_name'] }}">
                        <div class="hint">Displayed in navigation bar and page title</div>
                    </div>
                    <div class="field">
                        <label>Tagline</label>
                        <input type="text" name="panel_tagline" value="{{ $settings['panel_tagline'] }}">
                        <div class="hint">Subtitle shown below the panel name</div>
                    </div>
                    <div class="field">
                        <label>Logo URL</label>
                        <input type="url" name="logo_path" value="{{ $settings['logo_path'] }}">
                        <div class="hint">URL or path to your logo (SVG recommended)</div>
                    </div>
                    <div class="field">
                        <label>Favicon URL</label>
                        <input type="url" name="favicon_path" value="{{ $settings['favicon_path'] }}">
                        <div class="hint">Browser tab icon (ICO, PNG, or SVG)</div>
                    </div>
                </div>
            </div>

            <!-- Effects Panel -->
            <div id="panel-effects" class="panel">
                <h2>Visual Effects</h2>
                <div class="toggle-field">
                    <div class="info">
                        <div class="name">Glow Effects</div>
                        <div class="desc">Add glow/shadow effects on hover and active states</div>
                    </div>
                    <div class="toggle {{ $settings['glow_enabled'] === 'true' ? 'on' : '' }}" onclick="toggleSwitch(this)" data-name="glow_enabled"></div>
                    <input type="hidden" name="glow_enabled" value="{{ $settings['glow_enabled'] }}">
                </div>
                <div class="toggle-field">
                    <div class="info">
                        <div class="name">Animations</div>
                        <div class="desc">Enable smooth transitions and micro-animations</div>
                    </div>
                    <div class="toggle {{ $settings['animations_enabled'] === 'true' ? 'on' : '' }}" onclick="toggleSwitch(this)" data-name="animations_enabled"></div>
                    <input type="hidden" name="animations_enabled" value="{{ $settings['animations_enabled'] }}">
                </div>
                <div class="toggle-field">
                    <div class="info">
                        <div class="name">Gradient Background</div>
                        <div class="desc">Use gradient overlays on backgrounds</div>
                    </div>
                    <div class="toggle {{ $settings['gradient_enabled'] === 'true' ? 'on' : '' }}" onclick="toggleSwitch(this)" data-name="gradient_enabled"></div>
                    <input type="hidden" name="gradient_enabled" value="{{ $settings['gradient_enabled'] }}">
                </div>
                <div class="toggle-field">
                    <div class="info">
                        <div class="name">Particle Background</div>
                        <div class="desc">Animated particle effect on login page</div>
                    </div>
                    <div class="toggle {{ $settings['particle_bg'] === 'true' ? 'on' : '' }}" onclick="toggleSwitch(this)" data-name="particle_bg"></div>
                    <input type="hidden" name="particle_bg" value="{{ $settings['particle_bg'] }}">
                </div>
            </div>

            <!-- Advanced Panel -->
            <div id="panel-advanced" class="panel">
                <h2>Advanced</h2>
                <div class="field">
                    <label>Custom CSS</label>
                    <textarea name="custom_css" placeholder="/* Add your custom CSS here */">{{ $settings['custom_css'] }}</textarea>
                    <div class="hint">Injected at the end of the theme CSS — override anything</div>
                </div>
                <div class="field">
                    <label>Custom JavaScript</label>
                    <textarea name="custom_js" placeholder="// Add your custom JS here">{{ $settings['custom_js'] }}</textarea>
                    <div class="hint">Injected at the end of the dashboard wrapper</div>
                </div>
                <div class="divider"></div>
                <div class="section-title">Theme Management</div>
                <div class="btn-row">
                    <button type="button" class="btn btn-secondary" onclick="exportTheme()">Export Theme</button>
                    <button type="button" class="btn btn-secondary" onclick="document.getElementById('import-input').click()">Import Theme</button>
                    <input type="file" id="import-input" accept=".json" style="display:none" onchange="importTheme(this)">
                    <button type="button" class="btn btn-danger" onclick="if(confirm('Reset all settings to defaults?')) window.location.href='{{ route('admin.extensions.bsdkv1.reset') }}'">Reset to Defaults</button>
                </div>
            </div>

            <div class="btn-row" style="margin-top: 24px;">
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </div>
        </form>
    </div>

    <script>
        function showTab(name) {
            document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
            event.target.classList.add('active');
            document.getElementById('panel-' + name).classList.add('active');
        }

        function toggleSwitch(el) {
            el.classList.toggle('on');
            const hidden = el.nextElementSibling;
            hidden.value = el.classList.contains('on') ? 'true' : 'false';
        }

        function exportTheme() {
            const form = document.querySelector('form');
            const formData = new FormData(form);
            const theme = {};
            formData.forEach((value, key) => theme[key] = value);
            const blob = new Blob([JSON.stringify(theme, null, 2)], { type: 'application/json' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'bsdkv1-theme.json';
            a.click();
        }

        function importTheme(input) {
            const file = input.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = function(e) {
                try {
                    const theme = JSON.parse(e.target.result);
                    // Apply to form fields
                    Object.entries(theme).forEach(([key, value]) => {
                        const inputs = document.querySelectorAll(`[name="${key}"]`);
                        inputs.forEach(inp => {
                            if (inp.type === 'hidden') return;
                            inp.value = value;
                            if (inp.type === 'color') {
                                const textInput = inp.nextElementSibling;
                                if (textInput && textInput.type === 'text') textInput.value = value;
                            }
                        });
                    });
                    alert('Theme imported! Click Save to apply.');
                } catch (err) {
                    alert('Invalid theme file');
                }
            };
            reader.readAsText(file);
        }

        function updatePreview() {
            // Live preview updates would go here
        }
    </script>
</body>
</html>
