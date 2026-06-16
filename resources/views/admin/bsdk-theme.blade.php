<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BSDK V1 — Theme Settings</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&family=Poppins:wght@300;400;500;600;700;800&family=Roboto:wght@300;400;500;700&family=Open+Sans:wght@300;400;500;600;700&family=Lato:wght@300;400;700;900&family=Nunito:wght@300;400;600;700;800&family=Montserrat:wght@300;400;500;600;700;800&family=Raleway:wght@300;400;500;600;700;800&family=Ubuntu:wght@300;400;500;700&family=Fira+Code:wght@300;400;500;600;700&family=Source+Code+Pro:wght@300;400;500;600;700&family=Cascadia+Code:wght@300;400;500;600;700&family=IBM+Plex+Mono:wght@300;400;500;600;700&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { background: #0a0e17; scroll-behavior: smooth; }
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

        /* Layout */
        .layout { display: flex; min-height: 100vh; }

        /* Sidebar */
        .sidebar-admin {
            width: 260px;
            background: #111827;
            border-right: 1px solid rgba(255,255,255,0.08);
            padding: 24px 16px;
            position: fixed;
            top: 0; left: 0; bottom: 0;
            overflow-y: auto;
            z-index: 100;
        }

        .sidebar-admin .brand {
            display: flex; align-items: center; gap: 12px;
            padding: 0 8px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            margin-bottom: 20px;
        }

        .sidebar-admin .brand-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: linear-gradient(135deg, var(--live-primary, #00d4ff), var(--live-secondary, #7b68ee));
            display: flex; align-items: center; justify-content: center;
            font-weight: 800; font-size: 16px; color: #000;
            transition: background 0.3s;
        }

        .sidebar-admin .brand-text h2 { font-size: 15px; font-weight: 700; color: #e4e8f0; }
        .sidebar-admin .brand-text span { font-size: 11px; color: #5a6a7a; }

        .nav-section { margin-bottom: 20px; }

        .nav-section-title {
            font-size: 10px; font-weight: 600; letter-spacing: 1.5px;
            text-transform: uppercase; color: #5a6a7a;
            padding: 0 8px; margin-bottom: 8px;
        }

        .nav-item {
            display: flex; align-items: center; gap: 10px;
            padding: 10px 12px; border-radius: 8px;
            color: #8899aa; font-size: 13px; font-weight: 500;
            text-decoration: none; transition: all 0.2s; cursor: pointer;
        }

        .nav-item:hover { background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.08); color: #e4e8f0; }
        .nav-item.active { background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.12); color: var(--live-primary, #00d4ff); }
        .nav-item svg { width: 18px; height: 18px; flex-shrink: 0; }

        /* Main Content */
        .main-content { flex: 1; margin-left: 260px; padding: 32px; max-width: 1400px; }

        .page-header { margin-bottom: 32px; display: flex; align-items: flex-start; justify-content: space-between; }
        .page-header h1 { font-size: 24px; font-weight: 700; margin-bottom: 4px; }
        .page-header p { color: #8899aa; font-size: 14px; }

        .header-actions { display: flex; gap: 8px; }

        /* Toast */
        .toast {
            position: fixed; top: 20px; right: 20px; z-index: 9999;
            padding: 14px 20px; border-radius: 10px;
            font-size: 13px; font-weight: 500;
            display: flex; align-items: center; gap: 10px;
            transform: translateX(120%); transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            box-shadow: 0 8px 32px rgba(0,0,0,0.4);
        }
        .toast.show { transform: translateX(0); }
        .toast-success { background: #1a2332; border: 1px solid rgba(0, 255, 136, 0.3); color: #00ff88; }
        .toast-error { background: #1a2332; border: 1px solid rgba(255, 68, 102, 0.3); color: #ff4466; }
        .toast-info { background: #1a2332; border: 1px solid rgba(0, 212, 255, 0.3); color: #00d4ff; }

        /* Alert */
        .alert {
            padding: 14px 18px; border-radius: 8px; font-size: 13px;
            font-weight: 500; margin-bottom: 20px;
            display: flex; align-items: center; gap: 10px;
            animation: slideDown 0.3s ease-out;
        }
        @keyframes slideDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }
        .alert-success { background: rgba(0, 255, 136, 0.08); border: 1px solid rgba(0, 255, 136, 0.2); color: #00ff88; }
        .alert-error { background: rgba(255, 68, 102, 0.08); border: 1px solid rgba(255, 68, 102, 0.2); color: #ff4466; }

        /* Section */
        .section {
            background: #1a2332; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px; padding: 24px; margin-bottom: 24px;
            transition: border-color 0.2s;
        }
        .section:hover { border-color: rgba(255,255,255,0.12); }

        .section-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 20px; }
        .section-title { font-size: 16px; font-weight: 600; }
        .section-subtitle { font-size: 12px; color: #5a6a7a; margin-top: 2px; }

        /* Preset Cards */
        .preset-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; }

        .preset-card {
            background: #111827; border: 2px solid rgba(255,255,255,0.08);
            border-radius: 10px; padding: 16px; cursor: pointer;
            transition: all 0.2s; position: relative; overflow: hidden;
        }

        .preset-card::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--c1), var(--c2), var(--c3));
            opacity: 0; transition: opacity 0.2s;
        }

        .preset-card:hover { border-color: rgba(255,255,255,0.15); transform: translateY(-2px); }
        .preset-card:hover::before { opacity: 1; }

        .preset-card.active { border-color: var(--live-primary, #00d4ff); box-shadow: 0 0 20px rgba(var(--live-primary-rgb, 0, 212, 255), 0.15); }
        .preset-card.active::before { opacity: 1; }

        .preset-card.active::after {
            content: '✓'; position: absolute; top: 8px; right: 8px;
            width: 20px; height: 20px; background: var(--live-primary, #00d4ff);
            color: #000; border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
        }

        .preset-colors { display: flex; gap: 4px; margin-bottom: 12px; }
        .preset-color { width: 28px; height: 28px; border-radius: 6px; border: 1px solid rgba(255,255,255,0.1); transition: transform 0.2s; }
        .preset-card:hover .preset-color { transform: scale(1.1); }

        .preset-name { font-size: 13px; font-weight: 600; margin-bottom: 2px; }
        .preset-desc { font-size: 11px; color: #5a6a7a; line-height: 1.4; }

        /* Color Settings */
        .color-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }

        .color-field { display: flex; flex-direction: column; gap: 6px; }
        .color-field label { font-size: 12px; font-weight: 500; color: #8899aa; }

        .color-input-wrap {
            display: flex; align-items: center; gap: 8px;
            background: #111827; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px; padding: 4px 8px; transition: border-color 0.2s;
        }
        .color-input-wrap:focus-within { border-color: var(--live-primary, #00d4ff); }

        .color-input-wrap input[type="color"] {
            width: 32px; height: 32px; border: none; border-radius: 6px;
            cursor: pointer; background: transparent; padding: 0;
        }
        .color-input-wrap input[type="color"]::-webkit-color-swatch-wrapper { padding: 0; }
        .color-input-wrap input[type="color"]::-webkit-color-swatch { border: 1px solid rgba(255,255,255,0.1); border-radius: 6px; }

        .color-input-wrap input[type="text"] {
            flex: 1; background: transparent; border: none;
            color: #e4e8f0; font-family: 'JetBrains Mono', monospace;
            font-size: 12px; outline: none; width: 100%;
        }

        /* Text Settings */
        .text-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 14px; }

        .text-field { display: flex; flex-direction: column; gap: 6px; }
        .text-field label { font-size: 12px; font-weight: 500; color: #8899aa; }

        .text-field input, .text-field select {
            background: #111827; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px; color: #e4e8f0;
            font-family: 'Inter', system-ui, sans-serif;
            font-size: 13px; padding: 10px 14px; outline: none;
            transition: all 0.2s; width: 100%;
        }
        .text-field input:focus, .text-field select:focus {
            border-color: var(--live-primary, #00d4ff);
            box-shadow: 0 0 0 3px rgba(var(--live-primary-rgb, 0, 212, 255), 0.12);
        }

        .text-field select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%238899aa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            padding-right: 36px; cursor: pointer;
        }

        .font-preview { font-size: 14px; color: #e4e8f0; margin-top: 4px; padding: 8px 12px; background: #0d1117; border-radius: 6px; }

        /* Toggle Switches */
        .toggle-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 12px; }

        .toggle-item {
            display: flex; align-items: center; justify-content: space-between;
            padding: 14px 16px; background: #111827;
            border: 1px solid rgba(255,255,255,0.08); border-radius: 8px;
            transition: all 0.2s;
        }
        .toggle-item:hover { border-color: rgba(255,255,255,0.12); }

        .toggle-label { font-size: 13px; font-weight: 500; }
        .toggle-desc { font-size: 11px; color: #5a6a7a; margin-top: 2px; }

        .toggle-switch { position: relative; width: 44px; height: 24px; flex-shrink: 0; }
        .toggle-switch input { opacity: 0; width: 0; height: 0; }

        .toggle-slider {
            position: absolute; cursor: pointer; inset: 0;
            background: #243044; border-radius: 24px; transition: all 0.3s;
        }
        .toggle-slider::before {
            content: ''; position: absolute; height: 18px; width: 18px;
            left: 3px; bottom: 3px; background: #8899aa;
            border-radius: 50%; transition: all 0.3s;
        }
        .toggle-switch input:checked + .toggle-slider { background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.3); }
        .toggle-switch input:checked + .toggle-slider::before { transform: translateX(20px); background: var(--live-primary, #00d4ff); }

        /* Style Selectors */
        .style-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 12px; }

        .style-option {
            padding: 14px; background: #111827;
            border: 2px solid rgba(255,255,255,0.08);
            border-radius: 8px; text-align: center;
            cursor: pointer; transition: all 0.2s;
        }
        .style-option:hover { border-color: rgba(255,255,255,0.15); transform: translateY(-1px); }
        .style-option.active { border-color: var(--live-primary, #00d4ff); background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.08); }
        .style-option .style-icon { font-size: 24px; margin-bottom: 6px; }
        .style-option .style-name { font-size: 12px; font-weight: 600; }

        /* Textarea */
        .textarea-field { display: flex; flex-direction: column; gap: 6px; }
        .textarea-field label { font-size: 12px; font-weight: 500; color: #8899aa; }

        .textarea-field textarea {
            background: #111827; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 8px; color: #e4e8f0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 12px; padding: 12px; outline: none;
            resize: vertical; min-height: 100px; transition: all 0.2s; width: 100%;
        }
        .textarea-field textarea:focus {
            border-color: var(--live-primary, #00d4ff);
            box-shadow: 0 0 0 3px rgba(var(--live-primary-rgb, 0, 212, 255), 0.12);
        }

        /* Buttons */
        .btn {
            padding: 10px 20px; border-radius: 8px; font-size: 13px;
            font-weight: 600; border: none; cursor: pointer;
            transition: all 0.2s; font-family: 'Inter', system-ui, sans-serif;
            display: inline-flex; align-items: center; gap: 8px;
        }
        .btn-primary { background: var(--live-primary, #00d4ff); color: #000; }
        .btn-primary:hover { box-shadow: 0 0 20px rgba(var(--live-primary-rgb, 0, 212, 255), 0.3); transform: translateY(-1px); }
        .btn-secondary { background: #243044; color: #e4e8f0; border: 1px solid rgba(255,255,255,0.08); }
        .btn-secondary:hover { background: rgba(255,255,255, 0.08); }
        .btn-danger { background: rgba(255, 68, 102, 0.12); color: #ff4466; border: 1px solid rgba(255, 68, 102, 0.2); }
        .btn-danger:hover { background: rgba(255, 68, 102, 0.2); }
        .btn-ghost { background: transparent; color: #8899aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn-ghost:hover { background: rgba(255,255,255,0.05); color: #e4e8f0; }
        .btn-group { display: flex; gap: 10px; margin-top: 24px; flex-wrap: wrap; }

        /* Live Preview */
        .preview-panel {
            position: fixed; top: 0; right: 0; bottom: 0;
            width: 380px; background: #0d1117;
            border-left: 1px solid rgba(255,255,255,0.08);
            z-index: 90; transform: translateX(100%);
            transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            display: flex; flex-direction: column;
        }
        .preview-panel.open { transform: translateX(0); }

        .preview-header {
            padding: 16px 20px; border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex; align-items: center; justify-content: space-between;
        }
        .preview-header h3 { font-size: 14px; font-weight: 600; }

        .preview-body { flex: 1; overflow-y: auto; padding: 16px; }

        .preview-sidebar {
            width: 50px; background: var(--live-bg2, #111827);
            border-radius: 8px; padding: 12px 0;
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            margin-bottom: 12px; transition: background 0.3s;
        }
        .preview-sidebar .ps-item {
            width: 32px; height: 32px; border-radius: 8px;
            background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; transition: all 0.2s;
        }
        .preview-sidebar .ps-item.active {
            background: rgba(var(--live-primary-rgb, 0, 212, 255), 0.2);
            box-shadow: 0 0 10px rgba(var(--live-primary-rgb, 0, 212, 255), 0.2);
        }

        .preview-card {
            background: var(--live-bg3, #1a2332);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--live-radius, 8px);
            padding: 14px; margin-bottom: 12px;
            transition: all 0.3s;
        }
        .preview-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
        .preview-card-title { font-size: 13px; font-weight: 600; color: var(--live-text, #e4e8f0); }
        .preview-card-badge {
            font-size: 10px; font-weight: 600; padding: 2px 8px;
            border-radius: 12px; background: rgba(var(--live-accent-rgb, 0, 255, 136), 0.15);
            color: var(--live-accent, #00ff88);
        }
        .preview-card-stats { display: flex; gap: 12px; }
        .preview-stat { font-size: 11px; color: var(--live-text2, #8899aa); }
        .preview-stat span { color: var(--live-primary, #00d4ff); font-weight: 600; }

        .preview-btn {
            width: 100%; padding: 8px; border-radius: var(--live-radius, 8px);
            font-size: 12px; font-weight: 600; border: none;
            background: var(--live-primary, #00d4ff); color: #000;
            cursor: pointer; margin-top: 8px; transition: all 0.2s;
        }
        .preview-btn:hover { box-shadow: 0 0 16px rgba(var(--live-primary-rgb, 0, 212, 255), 0.3); }

        .preview-login {
            background: var(--live-bg3, #1a2332);
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: var(--live-radius, 8px);
            padding: 20px; text-align: center;
            position: relative; overflow: hidden;
        }
        .preview-login::before {
            content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px;
            background: linear-gradient(90deg, var(--live-primary, #00d4ff), var(--live-accent, #00ff88));
        }
        .preview-login h4 {
            font-size: 16px; font-weight: 700; margin-bottom: 12px;
            background: linear-gradient(135deg, var(--live-primary, #00d4ff), var(--live-accent, #00ff88));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
        }
        .preview-login-input {
            width: 100%; padding: 8px 12px; border-radius: var(--live-radius, 8px);
            background: var(--live-bg4, #243044); border: 1px solid rgba(255,255,255,0.08);
            color: var(--live-text, #e4e8f0); font-size: 12px; margin-bottom: 8px;
        }

        .preview-color-row { display: flex; gap: 6px; margin-bottom: 12px; flex-wrap: wrap; }
        .preview-color-chip {
            width: 28px; height: 28px; border-radius: 6px;
            border: 1px solid rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 8px; font-weight: 600; color: #000;
        }

        /* Export/Import Modal */
        .modal-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.7);
            backdrop-filter: blur(4px); z-index: 200;
            display: none; align-items: center; justify-content: center;
        }
        .modal-overlay.show { display: flex; }

        .modal {
            background: #1a2332; border: 1px solid rgba(255,255,255,0.08);
            border-radius: 12px; padding: 24px; width: 90%; max-width: 500px;
            box-shadow: 0 24px 48px rgba(0,0,0,0.6);
            animation: modalIn 0.2s ease-out;
        }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }

        .modal h3 { font-size: 16px; font-weight: 600; margin-bottom: 16px; }
        .modal textarea {
            width: 100%; min-height: 200px; background: #111827;
            border: 1px solid rgba(255,255,255,0.08); border-radius: 8px;
            color: #e4e8f0; font-family: 'JetBrains Mono', monospace;
            font-size: 11px; padding: 12px; resize: vertical;
        }
        .modal-actions { display: flex; gap: 8px; margin-top: 16px; justify-content: flex-end; }

        /* Responsive */
        @media (max-width: 768px) {
            .sidebar-admin { display: none; }
            .main-content { margin-left: 0; }
            .preview-panel { width: 100%; }
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

            <div class="nav-section" style="margin-top: auto; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08);">
                <a href="#" class="nav-item" onclick="togglePreview(); return false;">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    Live Preview
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="page-header">
                <div>
                    <h1>Theme Settings</h1>
                    <p>Customize your panel's appearance. Changes apply instantly — no rebuild needed.</p>
                </div>
                <div class="header-actions">
                    <button type="button" class="btn btn-ghost" onclick="exportConfig()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        Export
                    </button>
                    <button type="button" class="btn btn-ghost" onclick="importConfig()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                        Import
                    </button>
                    <button type="button" class="btn btn-primary" onclick="togglePreview()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        Preview
                    </button>
                </div>
            </div>

            @if(session('success'))
                <div class="alert alert-success">✓ {{ session('success') }}</div>
            @endif

            @if(session('error'))
                <div class="alert alert-error">✗ {{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('admin.bsdk-theme.update') }}" id="themeForm">
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
                                 style="--c1: {{ $preset['preview'][0] }}; --c2: {{ $preset['preview'][1] }}; --c3: {{ $preset['preview'][2] }};"
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
                        @foreach([
                            'primary_color' => 'Primary',
                            'secondary_color' => 'Secondary',
                            'accent_color' => 'Accent',
                            'danger_color' => 'Danger',
                            'warning_color' => 'Warning',
                            'bg_primary' => 'Background',
                            'bg_secondary' => 'Surface',
                            'bg_card' => 'Card',
                            'bg_elevated' => 'Elevated',
                            'text_primary' => 'Text',
                            'text_secondary' => 'Text Secondary',
                            'text_muted' => 'Text Muted',
                        ] as $key => $label)
                        <div class="color-field">
                            <label>{{ $label }}</label>
                            <div class="color-input-wrap">
                                <input type="color" name="{{ $key }}" value="{{ $settings[$key] }}">
                                <input type="text" value="{{ $settings[$key] }}">
                            </div>
                        </div>
                        @endforeach
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
                            <select name="font_family" onchange="updateFontPreview(this.value)">
                                @foreach(['Inter', 'Poppins', 'Roboto', 'Open Sans', 'Lato', 'Nunito', 'Source Sans Pro', 'Montserrat', 'Raleway', 'Ubuntu'] as $font)
                                    <option value="{{ $font }}" {{ $settings['font_family'] === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                            <div class="font-preview" id="fontPreview" style="font-family: '{{ $settings['font_family'] }}';">
                                The quick brown fox jumps over the lazy dog
                            </div>
                        </div>
                        <div class="text-field">
                            <label>Monospace Font</label>
                            <select name="font_mono" onchange="updateMonoPreview(this.value)">
                                @foreach(['JetBrains Mono', 'Fira Code', 'Source Code Pro', 'Cascadia Code', 'IBM Plex Mono', 'Space Mono'] as $font)
                                    <option value="{{ $font }}" {{ $settings['font_mono'] === $font ? 'selected' : '' }}>{{ $font }}</option>
                                @endforeach
                            </select>
                            <div class="font-preview" id="monoPreview" style="font-family: '{{ $settings['font_mono'] }}';">
                                const theme = "BSDK V1";
                            </div>
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
                            @foreach(['modern' => '📱', 'classic' => '📋', 'minimal' => '➖'] as $style => $icon)
                            <div class="style-option {{ $settings['sidebar_style'] === $style ? 'active' : '' }}" onclick="selectStyle('sidebar_style', '{{ $style }}', this)">
                                <div class="style-icon">{{ $icon }}</div>
                                <div class="style-name">{{ ucfirst($style) }}</div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="sidebar_style" value="{{ $settings['sidebar_style'] }}">
                    </div>

                    <div style="margin-top: 20px;">
                        <div class="section-subtitle" style="margin-bottom: 12px;">Card Style</div>
                        <div class="style-grid">
                            @foreach(['glass' => '💎', 'elevated' => '📦', 'flat' => '📄'] as $style => $icon)
                            <div class="style-option {{ $settings['card_style'] === $style ? 'active' : '' }}" onclick="selectStyle('card_style', '{{ $style }}', this)">
                                <div class="style-icon">{{ $icon }}</div>
                                <div class="style-name">{{ ucfirst($style) }}</div>
                            </div>
                            @endforeach
                        </div>
                        <input type="hidden" name="card_style" value="{{ $settings['card_style'] }}">
                    </div>

                    <div style="margin-top: 20px;">
                        <div class="section-subtitle" style="margin-bottom: 12px;">Button Style</div>
                        <div class="style-grid">
                            @foreach(['rounded' => '🔘', 'pill' => '💊', 'square' => '⬜'] as $style => $icon)
                            <div class="style-option {{ $settings['button_style'] === $style ? 'active' : '' }}" onclick="selectStyle('button_style', '{{ $style }}', this)">
                                <div class="style-icon">{{ $icon }}</div>
                                <div class="style-name">{{ ucfirst($style) }}</div>
                            </div>
                            @endforeach
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
                        @foreach([
                            'glow_enabled' => ['Glow Effects', 'Neon glow on hover and focus'],
                            'animations_enabled' => ['Animations', 'Smooth transitions and effects'],
                            'gradient_enabled' => ['Gradients', 'Gradient buttons and accents'],
                            'particle_bg' => ['Particle Background', 'Animated particles on login'],
                            'compact_mode' => ['Compact Mode', 'Reduced padding and spacing'],
                        ] as $key => [$label, $desc])
                        <div class="toggle-item">
                            <div>
                                <div class="toggle-label">{{ $label }}</div>
                                <div class="toggle-desc">{{ $desc }}</div>
                            </div>
                            <label class="toggle-switch">
                                <input type="checkbox" name="{{ $key }}" value="true" {{ $settings[$key] === 'true' ? 'checked' : '' }}>
                                <span class="toggle-slider"></span>
                            </label>
                        </div>
                        @endforeach
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
                    <button type="submit" class="btn btn-primary">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        Save Settings
                    </button>
                    <button type="button" class="btn btn-danger" onclick="if(confirm('Reset all settings to defaults?')) document.getElementById('reset-form').submit()">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                        Reset to Defaults
                    </button>
                </div>
            </form>

            <form id="reset-form" method="POST" action="{{ route('admin.bsdk-theme.reset') }}" style="display: none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- Live Preview Panel -->
    <div class="preview-panel" id="previewPanel">
        <div class="preview-header">
            <h3>Live Preview</h3>
            <button type="button" class="btn btn-ghost" onclick="togglePreview()" style="padding: 6px 10px;">✕</button>
        </div>
        <div class="preview-body">
            <!-- Color Palette -->
            <div class="section-subtitle" style="margin-bottom: 8px;">Color Palette</div>
            <div class="preview-color-row" id="previewColors">
                <div class="preview-color-chip" style="background: {{ $settings['primary_color'] }};" data-color="primary">{{ substr($settings['primary_color'], 1) }}</div>
                <div class="preview-color-chip" style="background: {{ $settings['secondary_color'] }};">S</div>
                <div class="preview-color-chip" style="background: {{ $settings['accent_color'] }};">A</div>
                <div class="preview-color-chip" style="background: {{ $settings['danger_color'] }};">D</div>
                <div class="preview-color-chip" style="background: {{ $settings['warning_color'] }};">W</div>
            </div>

            <!-- Mini Sidebar -->
            <div class="section-subtitle" style="margin-bottom: 8px;">Sidebar</div>
            <div class="preview-sidebar" id="previewSidebar">
                <div class="ps-item active">🏠</div>
                <div class="ps-item">💻</div>
                <div class="ps-item">📁</div>
                <div class="ps-item">🗄️</div>
                <div class="ps-item">⚙️</div>
            </div>

            <!-- Server Card -->
            <div class="section-subtitle" style="margin-bottom: 8px;">Server Card</div>
            <div class="preview-card" id="previewCard">
                <div class="preview-card-header">
                    <div class="preview-card-title">Minecraft Server</div>
                    <div class="preview-card-badge">Online</div>
                </div>
                <div class="preview-card-stats">
                    <div class="preview-stat">CPU <span>18.7%</span></div>
                    <div class="preview-stat">RAM <span>1.36 GiB</span></div>
                    <div class="preview-stat">Disk <span>200 MiB</span></div>
                </div>
                <button class="preview-btn">Manage Server</button>
            </div>

            <!-- Login Preview -->
            <div class="section-subtitle" style="margin-bottom: 8px;">Login Page</div>
            <div class="preview-login" id="previewLogin">
                <h4 id="previewLoginTitle">{{ $settings['panel_name'] }}</h4>
                <input class="preview-login-input" placeholder="Email" disabled>
                <input class="preview-login-input" placeholder="Password" type="password" disabled>
                <button class="preview-btn">Sign In</button>
            </div>
        </div>
    </div>

    <!-- Export/Import Modal -->
    <div class="modal-overlay" id="modal">
        <div class="modal">
            <h3 id="modalTitle">Export Config</h3>
            <textarea id="modalTextarea" readonly></textarea>
            <div class="modal-actions">
                <button type="button" class="btn btn-secondary" onclick="closeModal()">Close</button>
                <button type="button" class="btn btn-primary" id="modalAction" onclick="copyExport()">Copy to Clipboard</button>
            </div>
        </div>
    </div>

    <!-- Toast -->
    <div class="toast" id="toast"></div>

    <script>
        // ── Live CSS Variable Updates ──
        const root = document.documentElement;
        const colorMap = {
            'primary_color': ['--live-primary', '--p'],
            'secondary_color': ['--live-secondary', '--s'],
            'accent_color': ['--live-accent', '--a'],
            'danger_color': ['--live-danger', '--d'],
            'warning_color': ['--live-warning', '--w'],
            'bg_primary': ['--live-bg', '--bg'],
            'bg_secondary': ['--live-bg2', '--bg2'],
            'bg_card': ['--live-bg3', '--bg3'],
            'bg_elevated': ['--live-bg4', '--bg4'],
            'text_primary': ['--live-text', '--tx'],
            'text_secondary': ['--live-text2', '--tx2'],
            'text_muted': ['--live-text3', '--tx3'],
        };

        function hexToRgb(hex) {
            hex = hex.replace('#', '');
            if (hex.length === 3) hex = hex[0]+hex[0]+hex[1]+hex[1]+hex[2]+hex[2];
            return `${parseInt(hex.substr(0,2),16)}, ${parseInt(hex.substr(2,2),16)}, ${parseInt(hex.substr(4,2),16)}`;
        }

        function updateLiveVars() {
            document.querySelectorAll('input[type="color"]').forEach(input => {
                const vars = colorMap[input.name];
                if (vars) {
                    vars.forEach(v => root.style.setProperty(v, input.value));
                    root.style.setProperty(vars[0] + '-rgb', hexToRgb(input.value));
                }
            });
        }

        // ── Sync color inputs ──
        document.querySelectorAll('.color-input-wrap').forEach(wrap => {
            const color = wrap.querySelector('input[type="color"]');
            const text = wrap.querySelector('input[type="text"]');
            if (color && text) {
                color.addEventListener('input', () => { text.value = color.value; updateLiveVars(); updatePreview(); });
                text.addEventListener('input', () => { if (/^#[0-9a-f]{6}$/i.test(text.value)) { color.value = text.value; updateLiveVars(); updatePreview(); } });
            }
        });

        // ── Font preview ──
        function updateFontPreview(font) {
            document.getElementById('fontPreview').style.fontFamily = `'${font}', system-ui, sans-serif`;
        }
        function updateMonoPreview(font) {
            document.getElementById('monoPreview').style.fontFamily = `'${font}', monospace`;
        }

        // ── Style selector ──
        function selectStyle(field, value, el) {
            el.parentElement.querySelectorAll('.style-option').forEach(o => o.classList.remove('active'));
            el.classList.add('active');
            el.parentElement.parentElement.querySelector('input[type="hidden"]').value = value;
            updatePreview();
        }

        // ── Preset apply ──
        function applyPreset(id) {
            window.location.href = '{{ url("admin/bsdk-theme/preset") }}/' + id;
        }

        // ── Preview Panel ──
        function togglePreview() {
            document.getElementById('previewPanel').classList.toggle('open');
        }

        function updatePreview() {
            const get = (name) => {
                const input = document.querySelector(`input[name="${name}"]`);
                return input ? input.value : '';
            };

            // Update preview colors
            document.getElementById('previewCard').style.background = get('bg_card');
            document.getElementById('previewCard').style.borderColor = 'rgba(255,255,255,0.08)';
            document.getElementById('previewCard').style.borderRadius = get('border_radius');

            document.getElementById('previewLogin').style.background = get('bg_card');
            document.getElementById('previewLogin').style.borderRadius = get('border_radius');

            document.getElementById('previewLoginTitle').textContent = get('panel_name') || 'Panel';

            document.getElementById('previewSidebar').style.background = get('bg_secondary');

            // Update preview buttons
            document.querySelectorAll('.preview-btn').forEach(btn => {
                btn.style.background = get('primary_color');
                btn.style.borderRadius = get('border_radius');
            });

            // Update preview text
            document.querySelectorAll('.preview-card-title').forEach(el => {
                el.style.color = get('text_primary');
            });
            document.querySelectorAll('.preview-stat').forEach(el => {
                el.style.color = get('text_secondary');
            });

            // Update color chips
            const chips = document.querySelectorAll('.preview-color-chip');
            const colors = ['primary_color', 'secondary_color', 'accent_color', 'danger_color', 'warning_color'];
            chips.forEach((chip, i) => {
                if (colors[i]) {
                    chip.style.background = get(colors[i]);
                }
            });
        }

        // ── Nav active state ──
        document.querySelectorAll('.nav-item').forEach(item => {
            item.addEventListener('click', function() {
                document.querySelectorAll('.nav-item').forEach(i => i.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // ── Toast ──
        function showToast(msg, type = 'success') {
            const toast = document.getElementById('toast');
            toast.textContent = (type === 'success' ? '✓ ' : type === 'error' ? '✗ ' : 'ℹ ') + msg;
            toast.className = `toast toast-${type} show`;
            setTimeout(() => toast.classList.remove('show'), 3000);
        }

        // ── Export/Import ──
        function exportConfig() {
            const config = {};
            document.querySelectorAll('#themeForm input[name], #themeForm select[name], #themeForm textarea[name]').forEach(el => {
                if (el.type === 'checkbox') {
                    config[el.name] = el.checked ? 'true' : 'false';
                } else {
                    config[el.name] = el.value;
                }
            });
            document.getElementById('modalTitle').textContent = 'Export Theme Config';
            document.getElementById('modalTextarea').value = JSON.stringify(config, null, 2);
            document.getElementById('modalTextarea').readOnly = true;
            document.getElementById('modalAction').textContent = 'Copy to Clipboard';
            document.getElementById('modalAction').onclick = copyExport;
            document.getElementById('modal').classList.add('show');
        }

        function importConfig() {
            document.getElementById('modalTitle').textContent = 'Import Theme Config';
            document.getElementById('modalTextarea').value = '';
            document.getElementById('modalTextarea').readOnly = false;
            document.getElementById('modalTextarea').placeholder = 'Paste your theme config JSON here...';
            document.getElementById('modalAction').textContent = 'Apply Config';
            document.getElementById('modalAction').onclick = applyImport;
            document.getElementById('modal').classList.add('show');
        }

        function copyExport() {
            navigator.clipboard.writeText(document.getElementById('modalTextarea').value);
            showToast('Copied to clipboard!');
            closeModal();
        }

        function applyImport() {
            try {
                const config = JSON.parse(document.getElementById('modalTextarea').value);
                Object.entries(config).forEach(([key, value]) => {
                    const el = document.querySelector(`[name="${key}"]`);
                    if (!el) return;
                    if (el.type === 'checkbox') {
                        el.checked = value === 'true';
                    } else if (el.type === 'color') {
                        el.value = value;
                        const textInput = el.parentElement.querySelector('input[type="text"]');
                        if (textInput) textInput.value = value;
                    } else {
                        el.value = value;
                    }
                });
                updateLiveVars();
                updatePreview();
                showToast('Config imported! Click Save to apply.');
                closeModal();
            } catch (e) {
                showToast('Invalid JSON', 'error');
            }
        }

        function closeModal() {
            document.getElementById('modal').classList.remove('show');
        }

        // ── Init ──
        updateLiveVars();
        updatePreview();

        // Close modal on overlay click
        document.getElementById('modal').addEventListener('click', function(e) {
            if (e.target === this) closeModal();
        });

        // ESC to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
                document.getElementById('previewPanel').classList.remove('open');
            }
        });
    </script>
</body>
</html>
