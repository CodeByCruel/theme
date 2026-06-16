<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSDK V1 — Theme Settings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { background: #0a0e17; }
        body {
            font-family: 'Inter', system-ui, sans-serif;
            background: #0a0e17;
            color: #e4e8f0;
            min-height: 100vh;
        }

        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #111827; }
        ::-webkit-scrollbar-thumb { background: #243044; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #00d4ff; }

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar-admin {
            width: 260px;
            background: #111827;
            border-right: 1px solid rgba(255,255,255,0.08);
            padding: 24px 16px;
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            overflow-y: auto;
        }

        .sidebar-admin .brand {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 8px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .sidebar-admin .brand-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            background: linear-gradient(135deg, #00d4ff, #7b68ee);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 800;
            font-size: 16px;
            color: #000;
        }

        .sidebar-admin .brand-text h2 {
            font-size: 15px;
            font-weight: 700;
            color: #e4e8f0;
        }

        .sidebar-admin .brand-text span {
            font-size: 11px;
            color: #5a6a7a;
        }

        .nav-section {
            margin-bottom: 20px;
        }

        .nav-section-title {
            font-size: 10px;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            color: #5a6a7a;
            padding: 0 8px;
            margin-bottom: 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            border-radius: 8px;
            color: #8899aa;
            font-size: 13px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s;
            cursor: pointer;
        }

        .nav-item:hover {
            background: rgba(0, 212, 255, 0.08);
            color: #e4e8f0;
        }

        .nav-item.active {
            background: rgba(0, 212, 255, 0.12);
            color: #00d4ff;
        }

        .nav-item svg {
            width: 18px;
            height: 18px;
            flex-shrink: 0;
        }

        .main-content {
            flex: 1;
            margin-left: 260px;
            padding: 32px;
            max-width: 1200px;
        }

        .page-header {
            margin-bottom: 32px;
        }

        .page-header h1 {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 4px;
        }

        .page-header p {
            color: #8899aa;
            font-size: 14px;
        }

        .alert {
            padding: 14px 18px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: rgba(0, 255, 136, 0.08);
            border: 1px solid rgba(0, 255, 136, 0.2);
            color: #00ff88;
        }

        .alert-error {
            background: rgba(255, 68, 102, 0.08);
            border: 1px solid rgba(255, 68, 102, 0.2);
            color: #ff4466;
        }

        .section {
            background: #1a2332;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
        }

        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 16px;
            font-weight: 600;
        }

        .section-subtitle {
            font-size: 12px;
            color: #5a6a7a;
            margin-top: 2px;
        }

        /* Preset Cards */
        .preset-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 12px;
        }

        .preset-card {
            background: #111827;
            border: 2px solid rgba(255,255,255,0.08);
            border-radius: 10px;
            padding: 16px;
            cursor: pointer;
            transition: all 0.2s;
            position: relative;
        }

        .preset-card:hover {
            border-color: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }

        .preset-card.active {
            border-color: #00d4ff;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.15);
        }

        .preset-card.active::after {
            content: '✓';
            position: absolute;
            top: 8px;
            right: 8px;
            width: 20px;
            height: 20px;
            background: #00d4ff;
            color: #000;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
        }

        .preset-colors {
            display: flex;
            gap: 4px;
            margin-bottom: 12px;
        }

        .preset-color {
            width: 24px;
            height: 24px;
            border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1);
        }

        .preset-name {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .preset-desc {
            font-size: 11px;
            color: #5a6a7a;
            line-height: 1.4;
        }

        /* Color Settings */
        .color-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }

        .color-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .color-field label {
            font-size: 12px;
            font-weight: 500;
            color: #8899aa;
        }

        .color-input-wrap {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            padding: 4px 8px;
        }

        .color-input-wrap input[type="color"] {
            width: 32px;
            height: 32px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            background: transparent;
            padding: 0;
        }

        .color-input-wrap input[type="color"]::-webkit-color-swatch-wrapper {
            padding: 0;
        }

        .color-input-wrap input[type="color"]::-webkit-color-swatch {
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 6px;
        }

        .color-input-wrap input[type="text"] {
            flex: 1;
            background: transparent;
            border: none;
            color: #e4e8f0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            outline: none;
            width: 100%;
        }

        /* Text Settings */
        .text-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 16px;
        }

        .text-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .text-field label {
            font-size: 12px;
            font-weight: 500;
            color: #8899aa;
        }

        .text-field input,
        .text-field select {
            background: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: #e4e8f0;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 13px;
            padding: 10px 14px;
            outline: none;
            transition: all 0.2s;
            width: 100%;
        }

        .text-field input:focus,
        .text-field select:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.12);
        }

        .text-field select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%238899aa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px;
            cursor: pointer;
        }

        /* Toggle Switches */
        .toggle-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
            gap: 12px;
        }

        .toggle-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            background: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
        }

        .toggle-label {
            font-size: 13px;
            font-weight: 500;
        }

        .toggle-desc {
            font-size: 11px;
            color: #5a6a7a;
            margin-top: 2px;
        }

        .toggle-switch {
            position: relative;
            width: 44px;
            height: 24px;
            flex-shrink: 0;
        }

        .toggle-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .toggle-slider {
            position: absolute;
            cursor: pointer;
            inset: 0;
            background: #243044;
            border-radius: 24px;
            transition: all 0.3s;
        }

        .toggle-slider::before {
            content: '';
            position: absolute;
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background: #8899aa;
            border-radius: 50%;
            transition: all 0.3s;
        }

        .toggle-switch input:checked + .toggle-slider {
            background: rgba(0, 212, 255, 0.3);
        }

        .toggle-switch input:checked + .toggle-slider::before {
            transform: translateX(20px);
            background: #00d4ff;
        }

        /* Style Selectors */
        .style-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 12px;
        }

        .style-option {
            padding: 14px;
            background: #111827;
            border: 2px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .style-option:hover {
            border-color: rgba(255,255,255,0.15);
        }

        .style-option.active {
            border-color: #00d4ff;
            background: rgba(0, 212, 255, 0.08);
        }

        .style-option .style-icon {
            font-size: 24px;
            margin-bottom: 6px;
        }

        .style-option .style-name {
            font-size: 12px;
            font-weight: 600;
        }

        /* Textarea */
        .textarea-field {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .textarea-field label {
            font-size: 12px;
            font-weight: 500;
            color: #8899aa;
        }

        .textarea-field textarea {
            background: #111827;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            color: #e4e8f0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px;
            padding: 12px;
            outline: none;
            resize: vertical;
            min-height: 100px;
            transition: all 0.2s;
            width: 100%;
        }

        .textarea-field textarea:focus {
            border-color: #00d4ff;
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.12);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            border: none;
            cursor: pointer;
            transition: all 0.2s;
            font-family: 'Inter', system-ui, sans-serif;
        }

        .btn-primary {
            background: #00d4ff;
            color: #000;
        }

        .btn-primary:hover {
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            transform: translateY(-1px);
        }

        .btn-secondary {
            background: #243044;
            color: #e4e8f0;
            border: 1px solid rgba(255,255,255,0.08);
        }

        .btn-secondary:hover {
            background: rgba(255,255,255, 0.08);
        }

        .btn-danger {
            background: rgba(255, 68, 102, 0.12);
            color: #ff4466;
            border: 1px solid rgba(255, 68, 102, 0.2);
        }

        .btn-danger:hover {
            background: rgba(255, 68, 102, 0.2);
        }

        .btn-group {
            display: flex;
            gap: 10px;
            margin-top: 24px;
        }

        /* Preview */
        .preview-frame {
            background: #0a0e17;
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px;
            overflow: hidden;
            margin-top: 16px;
        }

        .preview-bar {
            background: #111827;
            padding: 8px 12px;
            display: flex;
            align-items: center;
            gap: 6px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
        }

        .preview-dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .preview-content {
            padding: 16px;
            min-height: 200px;
        }

        @media (max-width: 768px) {
            .sidebar-admin { display: none; }
            .main-content { margin-left: 0; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <!-- Sidebar -->
        <div class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">B</div>
                <div class="brand-text">
                    <h2>BSDK V1</h2>
                    <span>Theme Settings</span>
                </div>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Theme</div>
                <a href="#presets" class="nav-item active">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Presets
                </a>
                <a href="#colors" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Colors
                </a>
                <a href="#typography" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                    Typography
                </a>
                <a href="#layout" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z"/></svg>
                    Layout
                </a>
            </div>

            <div class="nav-section">
                <div class="nav-section-title">Advanced</div>
                <a href="#features" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Features
                </a>
                <a href="#custom" class="nav-item">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    Custom Code
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <h1>Theme Settings</h1>
                <p>Customize your panel's appearance. Changes apply instantly — no rebuild needed.</p>
            </div>

            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">✗ {{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.bsdk-theme.update') }}">
                @csrf

                <!-- Preset Selector -->
                <div class="section" id="presets">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Theme Presets</div>
                            <div class="section-subtitle">Choose a pre-made style as your starting point</div>
                        </div>
                    </div>
                    <div class="preset-grid">
                        @foreach($presets as $key => $preset)
                            <div class="preset-card {{ $activePreset === $key ? 'active' : '' }}"
                                 onclick="applyPreset('{{ $key }}')">
                                <div class="preset-colors">
                                    @foreach($preset['preview'] as $color)
                                        <div class="preset-color" style="background: {{ $color }};"></div>
                                    @endforeach
                                </div>
                                <div class="preset-name">{{ $preset['name'] }}</div>
                                <div class="preset-desc">{{ $preset['description'] }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Color Settings -->
                <div class="section" id="colors">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Colors</div>
                            <div class="section-subtitle">Customize the color palette</div>
                        </div>
                    </div>
                    <div class="color-grid">
                        <div class="color-field">
                            <label>Primary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="primary_color" value="{{ $settings['primary_color'] }}" onchange="this.nextElementSibling.value=this.value; previewTheme()">
                                <input type="text" value="{{ $settings['primary_color'] }}" onchange="this.previousElementSibling.value=this.value; previewTheme()">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Secondary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="secondary_color" value="{{ $settings['secondary_color'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['secondary_color'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Accent</label>
                            <div class="color-input-wrap">
                                <input type="color" name="accent_color" value="{{ $settings['accent_color'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['accent_color'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Danger</label>
                            <div class="color-input-wrap">
                                <input type="color" name="danger_color" value="{{ $settings['danger_color'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['danger_color'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Warning</label>
                            <div class="color-input-wrap">
                                <input type="color" name="warning_color" value="{{ $settings['warning_color'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['warning_color'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Background Primary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="bg_primary" value="{{ $settings['bg_primary'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['bg_primary'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Background Secondary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="bg_secondary" value="{{ $settings['bg_secondary'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['bg_secondary'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Background Card</label>
                            <div class="color-input-wrap">
                                <input type="color" name="bg_card" value="{{ $settings['bg_card'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['bg_card'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Background Elevated</label>
                            <div class="color-input-wrap">
                                <input type="color" name="bg_elevated" value="{{ $settings['bg_elevated'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['bg_elevated'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Text Primary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="text_primary" value="{{ $settings['text_primary'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['text_primary'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Text Secondary</label>
                            <div class="color-input-wrap">
                                <input type="color" name="text_secondary" value="{{ $settings['text_secondary'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['text_secondary'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                        <div class="color-field">
                            <label>Text Muted</label>
                            <div class="color-input-wrap">
                                <input type="color" name="text_muted" value="{{ $settings['text_muted'] }}" onchange="this.nextElementSibling.value=this.value">
                                <input type="text" value="{{ $settings['text_muted'] }}" onchange="this.previousElementSibling.value=this.value">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Typography -->
                <div class="section" id="typography">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Typography</div>
                            <div class="section-subtitle">Fonts and text styling</div>
                        </div>
                    </div>
                    <div class="text-grid">
                        <div class="text-field">
                            <label>Font Family</label>
                            <select name="font_family">
                                @foreach(['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato', 'Nunito', 'Source Sans Pro', 'Montserrat', 'Raleway', 'Ubuntu'] as $font)
                                    <option value="{{ $font }}" {{ $settings['font_family'] === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-field">
                            <label>Monospace Font</label>
                            <select name="font_mono">
                                @foreach(['JetBrains Mono', 'Fira Code', 'Source Code Pro', 'Cascadia Code', 'IBM Plex Mono', 'Space Mono'] as $font)
                                    <option value="{{ $font }}" {{ $settings['font_mono'] === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="text-field">
                            <label>Border Radius</label>
                            <select name="border_radius">
                                @foreach(['0px', '4px', '6px', '8px', '10px', '12px', '16px'] as $r)
                                    <option value="{{ $r }}" {{ $settings['border_radius'] === $r ? 'selected' : '' }}>{{ $r }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Layout & Branding -->
                <div class="section" id="layout">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Layout & Branding</div>
                            <div class="section-subtitle">Panel name, logo, and layout options</div>
                        </div>
                    </div>
                    <div class="text-grid">
                        <div class="text-field">
                            <label>Panel Name</label>
                            <input type="text" name="panel_name" value="{{ $settings['panel_name'] }}" placeholder="My Panel">
                        </div>
                        <div class="text-field">
                            <label>Tagline</label>
                            <input type="text" name="panel_tagline" value="{{ $settings['panel_tagline'] }}" placeholder="Game Server Management">
                        </div>
                        <div class="text-field">
                            <label>Logo Path</label>
                            <input type="text" name="logo_path" value="{{ $settings['logo_path'] }}" placeholder="/assets/bsdk/logo.svg">
                        </div>
                        <div class="text-field">
                            <label>Favicon Path</label>
                            <input type="text" name="favicon_path" value="{{ $settings['favicon_path'] }}" placeholder="/assets/bsdk/favicon.svg">
                        </div>
                        <div class="text-field">
                            <label>Login Background</label>
                            <input type="text" name="login_bg" value="{{ $settings['login_bg'] }}" placeholder="/assets/bsdk/background.svg">
                        </div>
                    </div>

                    <div style="margin-top: 20px;">
                        <div class="section-subtitle" style="margin-bottom: 12px;">Sidebar Style</div>
                        <div class="style-grid">
                            <div class="style-option {{ $settings['sidebar_style'] === 'modern' ? 'active' : '' }}" onclick="selectStyle('sidebar_style', 'modern', this)">
                                <div class="style-icon">📱</div>
                                <div class="style-name">Modern</div>
                            </div>
                            <div class="style-option {{ $settings['sidebar_style'] === 'classic' ? 'active' : '' }}" onclick="selectStyle('sidebar_style', 'classic', this)">
                                <div class="style-icon">📋</div>
                                <div class="style-name">Classic</div>
                            </div>
                            <div class="style-option {{ $settings['sidebar_style'] === 'minimal' ? 'active' : '' }}" onclick="selectStyle('sidebar_style', 'minimal', this)">
                                <div class="style-icon">➖</div>
                                <div class="style-name">Minimal</div>
                            </div>
                        </div>
                        <input type="hidden" name="sidebar_style" value="{{ $settings['sidebar_style'] }}">
                    </div>

                    <div style="margin-top: 20px;">
                        <div class="section-subtitle" style="margin-bottom: 12px;">Card Style</div>
                        <div class="style-grid">
                            <div class="style-option {{ $settings['card_style'] === 'glass' ? 'active' : '' }}" onclick="selectStyle('card_style', 'glass', this)">
                                <div class="style-icon">💎</div>
                                <div class="style-name">Glass</div>
                            </div>
                            <div class="style-option {{ $settings['card_style'] === 'elevated' ? 'active' : '' }}" onclick="selectStyle('card_style', 'elevated', this)">
                                <div class="style-icon">📦</div>
                                <div class="style-name">Elevated</div>
                            </div>
                            <div class="style-option {{ $settings['card_style'] === 'flat' ? 'active' : '' }}" onclick="selectStyle('card_style', 'flat', this)">
                                <div class="style-icon">📄</div>
                                <div class="style-name">Flat</div>
                            </div>
                        </div>
                        <input type="hidden" name="card_style" value="{{ $settings['card_style'] }}">
                    </div>

                    <div style="margin-top: 20px;">
                        <div class="section-subtitle" style="margin-bottom: 12px;">Button Style</div>
                        <div class="style-grid">
                            <div class="style-option {{ $settings['button_style'] === 'rounded' ? 'active' : '' }}" onclick="selectStyle('button_style', 'rounded', this)">
                                <div class="style-icon">🔘</div>
                                <div class="style-name">Rounded</div>
                            </div>
                            <div class="style-option {{ $settings['button_style'] === 'pill' ? 'active' : '' }}" onclick="selectStyle('button_style', 'pill', this)">
                                <div class="style-icon">💊</div>
                                <div class="style-name">Pill</div>
                            </div>
                            <div class="style-option {{ $settings['button_style'] === 'square' ? 'active' : '' }}" onclick="selectStyle('button_style', 'square', this)">
                                <div class="style-icon">⬜</div>
                                <div class="style-name">Square</div>
                            </div>
                        </div>
                        <input type="hidden" name="button_style" value="{{ $settings['button_style'] }}">
                    </div>
                </div>

                <!-- Features -->
                <div class="section" id="features">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Features</div>
                            <div class="section-subtitle">Toggle visual effects and behaviors</div>
                        </div>
                    </div>
                    <div class="toggle-grid">
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">Glow Effects</div>
                                <div class="toggle-desc">Neon glow on hover and focus</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="glow_enabled" value="true" {{ $settings['glow_enabled'] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">Animations</div>
                                <div class="toggle-desc">Smooth transitions and effects</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="animations_enabled" value="true" {{ $settings['animations_enabled'] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">Gradients</div>
                                <div class="toggle-desc">Gradient buttons and accents</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="gradient_enabled" value="true" {{ $settings['gradient_enabled'] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">Particle Background</div>
                                <div class="toggle-desc">Animated particles on login</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="particle_bg" value="true" {{ $settings['particle_bg'] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">Compact Mode</div>
                                <div class="toggle-desc">Reduced padding and spacing</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="compact_mode" value="true" {{ $settings['compact_mode'] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Custom Code -->
                <div class="section" id="custom">
                    <div class="section-header">
                        <div>
                            <div class="section-title">Custom Code</div>
                            <div class="section-subtitle">Inject custom CSS and JavaScript</div>
                        </div>
                    </div>
                    <div style="display: grid; gap: 16px;">
                        <div class="textarea-field">
                            <label>Custom CSS</label>
                            <textarea name="custom_css" rows="6" placeholder="/* Add your custom CSS here */">{{ $settings['custom_css'] }}</textarea>
                        </div>
                        <div class="textarea-field">
                            <label>Custom JavaScript</label>
                            <textarea name="custom_js" rows="6" placeholder="// Add your custom JavaScript here">{{ $settings['custom_js'] }}</textarea>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <button type="button" class="btn btn-danger" onclick="if(confirm('Reset all settings to defaults?')) document.getElementById('reset-form').submit()">
                        Reset to Defaults
                    </button>
                </div>
            </form>

            <form id="reset-form" method="POST" action="{{ route('admin.bsdk-theme.reset') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <script>
        function applyPreset(presetId) {
            window.location.href = '{{ url("admin/bsdk-theme/preset") }}/' + presetId;
        }

        function selectStyle(field, value, el) {
            const hidden = el.parentElement.querySelector('input[type="hidden"]');
            hidden.value = value;
            el.parentElement.querySelectorAll('.style-option').forEach(opt => opt.classList.remove('active'));
            el.classList.add('active');
        }

        function previewTheme() {
            const colorInputs = document.querySelectorAll('input[type="color"]');
            colorInputs.forEach(input => {
                const varName = input.name.replace(/_/g, '-');
                document.documentElement.style.setProperty('--' + varName, input.value);
            });
        }

        // Nav active state
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Sync color inputs
        document.querySelectorAll('.color-input-wrap').forEach(wrap => {
            const color = wrap.querySelector('input[type="color"]');
            const text = wrap.querySelector('input[type="text"]');
            if (color && text) {
                color.addEventListener('input', () => { text.value = color.value; previewTheme(); });
                text.addEventListener('input', () => { color.value = text.value; previewTheme(); });
            }
        });
    </script>
</body>
</html>
