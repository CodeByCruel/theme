<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hyper Settings — BSDK V1</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Poppins:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/assets/css/hyper.css?t={{ @filemtime(public_path('assets/css/hyper.css')) ?: time() }}">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        html { background: #0c0a09; scroll-behavior: smooth; }
        body { font-family: 'Inter', system-ui, sans-serif; background: #0c0a09; color: #ffffff; min-height: 100vh; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #191919; }
        ::-webkit-scrollbar-thumb { background: #292524; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #df3050; }
        .layout { display: flex; min-height: 100vh; }
        .sidebar-admin { width: 260px; background: #191919cc; border-right: 1px solid rgba(255,255,255,0.08); padding: 24px 16px; position: fixed; top: 0; left: 0; bottom: 0; overflow-y: auto; z-index: 100; backdrop-filter: blur(20px); }
        .sidebar-admin .brand { display: flex; align-items: center; gap: 12px; padding: 0 8px 20px; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 20px; }
        .sidebar-admin .brand-icon { width: 36px; height: 36px; border-radius: 10px; background: linear-gradient(135deg, #df3050, #ff6b6b); display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 16px; color: #fff; }
        .sidebar-admin .brand-text h2 { font-size: 15px; font-weight: 700; color: #fff; }
        .sidebar-admin .brand-text span { font-size: 11px; color: #a1a1aa; }
        .nav-section { margin-bottom: 20px; }
        .nav-section-title { font-size: 10px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: #a1a1aa; padding: 0 8px; margin-bottom: 8px; }
        .nav-item { display: flex; align-items: center; gap: 10px; padding: 10px 12px; border-radius: 8px; color: #fafafa; font-size: 13px; font-weight: 500; text-decoration: none; transition: all 0.2s; cursor: pointer; }
        .nav-item:hover { background: rgba(223, 48, 80, 0.1); color: #fff; }
        .nav-item.active { background: rgba(223, 48, 80, 0.15); color: #df3050; }
        .nav-item i { width: 18px; text-align: center; }
        .main-content { flex: 1; margin-left: 260px; padding: 32px; max-width: 1400px; }
        .page-header { margin-bottom: 32px; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .view-toggle { display: flex; gap: 4px; margin-bottom: 20px; }
        .view-toggle button { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .view-toggle button.active, .view-toggle button:hover { background: #df3050; color: #fff; border-color: #df3050; }
        .settings-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 16px; margin-bottom: 40px; }
        .settings-grid.list { grid-template-columns: 1fr; }
        .settings-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; cursor: pointer; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .settings-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #df3050, #ff6b6b); opacity: 0; transition: opacity 0.3s; }
        .settings-card:hover { transform: translateY(-2px); border-color: #df3050; }
        .settings-card:hover::before { opacity: 1; }
        .settings-card-icon { width: 48px; height: 48px; border-radius: 12px; background: rgba(223, 48, 80, 0.1); display: flex; align-items: center; justify-content: center; font-size: 20px; color: #df3050; margin-bottom: 16px; }
        .settings-card h3 { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .settings-card p { font-size: 13px; color: #a1a1aa; line-height: 1.5; margin-bottom: 16px; }
        .settings-card-btn { width: 100%; padding: 10px; border: none; border-radius: 8px; background: #df3050; color: #fff; font-weight: 600; cursor: pointer; transition: all 0.2s; font-size: 13px; }
        .settings-card-btn:hover { background: #e44b63; }
        .detail-view { display: none; }
        .detail-view.active { display: block; }
        .grid-view.hidden { display: none; }
        .back-btn { display: inline-flex; align-items: center; gap: 8px; padding: 8px 16px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; margin-bottom: 24px; transition: all 0.2s; font-size: 13px; }
        .back-btn:hover { border-color: #df3050; color: #df3050; }
        .detail-view h2 { font-size: 22px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .detail-view h2 i { margin-right: 8px; color: #df3050; }
        .detail-desc { color: #a1a1aa; margin-bottom: 24px; }
        .settings-fields { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 20px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .field-group input[type="text"], .field-group input[type="number"], .field-group textarea, .field-group select {
            width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;
        }
        .field-group input:focus, .field-group textarea:focus, .field-group select:focus { border-color: #df3050; }
        .color-row { display: flex; gap: 8px; align-items: center; }
        .color-row input[type="color"] { width: 40px; height: 40px; border: none; border-radius: 8px; cursor: pointer; background: none; }
        .color-row input[type="text"] { flex: 1; }
        .toggle-btn { position: relative; width: 48px; height: 26px; border: none; background: #292524; border-radius: 13px; cursor: pointer; transition: background 0.3s; }
        .toggle-btn.active { background: #df3050; }
        .toggle-slider { position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: transform 0.3s; }
        .toggle-btn.active .toggle-slider { transform: translateX(22px); }
        .footer-bar { position: fixed; bottom: 0; left: 260px; right: 0; height: 64px; background: #191919cc; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; z-index: 100; backdrop-filter: blur(20px); }
        .save-status { font-size: 13px; display: flex; align-items: center; gap: 8px; color: #a1a1aa; }
        .save-status i { color: #22c55e; }
        .footer-actions { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; }
        .btn.danger:hover { background: #991b1b; }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .footer-bar { left: 0; }
            .settings-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <nav class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">H</div>
                <div class="brand-text">
                    <h2>Hyper Settings</h2>
                    <span>BSDK V1 Admin</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Navigation</div>
                <a href="/admin/bsdk-theme" class="nav-item"><i class="fa fa-paint-brush"></i> BSDK Theme</a>
                <a href="/admin/hyper-settings" class="nav-item active"><i class="fa fa-magic"></i> Hyper Settings</a>
                <a href="/admin/addon-settings" class="nav-item"><i class="fa fa-puzzle-piece"></i> Addon Settings</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Quick Links</div>
                <a href="/admin" class="nav-item"><i class="fa fa-arrow-left"></i> Back to Admin</a>
                <a href="/" class="nav-item"><i class="fa fa-home"></i> Panel Home</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fa fa-magic"></i> Hyper Settings</h1>
                    <p>Customize the Hyper theme appearance, colors, layout, and behavior</p>
                </div>
                <div class="view-toggle">
                    <button id="viewGrid" class="active" title="Grid View"><i class="fa fa-th"></i></button>
                    <button id="viewList" title="List View"><i class="fa fa-list"></i></button>
                </div>
            </div>

            <div id="gridView" class="settings-grid"></div>
            <div id="listView" class="settings-grid list" style="display:none;"></div>

            <!-- Detail views for each category -->
            <div id="detailContainer"></div>
        </main>
    </div>

    <div class="footer-bar">
        <div class="save-status" id="saveStatus"><i class="fa fa-check-circle"></i> All changes saved</div>
        <div class="footer-actions">
            <button class="btn danger" onclick="resetSettings()"><i class="fa fa-undo"></i> Reset</button>
            <button class="btn secondary" onclick="exportSettings()"><i class="fa fa-download"></i> Export</button>
            <button class="btn secondary" onclick="document.getElementById('importFile').click()"><i class="fa fa-upload"></i> Import</button>
            <input type="file" id="importFile" accept=".json" style="display:none;" onchange="importSettings(event)">
            <button class="btn primary" onclick="saveSettings()"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/admin/bsdk/settings';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let settings = {};
    let isListView = false;
    let activeCategory = null;

    const categories = [
        {
            id: 'theme-colors', title: 'Theme Colors', description: 'Control color variables, interface surfaces, and accent colors', icon: 'fa-palette',
            fields: [
                { key: '--hyper-primary', label: 'Primary Color', type: 'color', def: '#df3050' },
                { key: '--hyper-primary-hover', label: 'Primary Hover', type: 'color', def: '#e44b63' },
                { key: '--hyper-secondary', label: 'Secondary Color', type: 'color', def: '#27272a' },
                { key: '--hyper-accent', label: 'Accent Color', type: 'color', def: '#292524' },
                { key: '--hyper-background', label: 'Background Color', type: 'color', def: '#0c0a09' },
                { key: '--hyper-card', label: 'Card Background', type: 'color', def: '#191919cc' },
                { key: '--hyper-surface', label: 'Surface Color', type: 'color', def: '#1c19177a' },
                { key: '--hyper-sidebar', label: 'Sidebar Color', type: 'color', def: '#191919cc' },
                { key: '--hyper-muted', label: 'Muted Color', type: 'color', def: '#262626' },
                { key: '--hyper-destructive', label: 'Destructive Color', type: 'color', def: '#7f1d1d' },
                { key: '--hyper-destructive-text', label: 'Destructive Text', type: 'color', def: '#ff0000' },
                { key: '--hyper-text-primary', label: 'Text Primary', type: 'color', def: '#ffffff' },
                { key: '--hyper-text-secondary', label: 'Text Secondary', type: 'color', def: '#fafafa' },
                { key: '--hyper-text-muted', label: 'Text Muted', type: 'color', def: '#a1a1aa' },
            ]
        },
        {
            id: 'brand-identity', title: 'Brand Identity & Auth', description: 'Manage logos, authentication page branding, and footer', icon: 'fa-image',
            fields: [
                { key: 'brand_name', label: 'Panel Name', type: 'text', def: 'BSDK Panel' },
                { key: 'brand_tagline', label: 'Panel Tagline', type: 'text', def: 'Game Server Management' },
                { key: 'brand_logo', label: 'Logo URL', type: 'text', def: '' },
                { key: 'brand_dark_logo', label: 'Dark Logo URL', type: 'text', def: '' },
                { key: 'brand_favicon', label: 'Favicon URL', type: 'text', def: '' },
                { key: 'auth_logo', label: 'Auth Page Logo URL', type: 'text', def: '' },
                { key: 'auth_background', label: 'Auth Background URL', type: 'text', def: '' },
                { key: 'brand_footer', label: 'Footer Text', type: 'text', def: '' },
            ]
        },
        {
            id: 'nav-layout', title: 'Navigation & Layout', description: 'Customize floating navigation, sidebar behavior, and layout modes', icon: 'fa-bars',
            fields: [
                { key: 'floating_nav', label: 'Floating Navigation', type: 'toggle', def: false },
                { key: 'nav_blur', label: 'Navigation Blur', type: 'toggle', def: true },
                { key: 'sidebar_collapsed', label: 'Sidebar Collapsed by Default', type: 'toggle', def: false },
                { key: 'sidebar_compact', label: 'Compact Sidebar', type: 'toggle', def: false },
                { key: 'header_style', label: 'Header Style', type: 'select', def: 'default', options: [{l:'Default',v:'default'},{l:'Compact',v:'compact'},{l:'Hidden',v:'hidden'}] },
            ]
        },
        {
            id: 'server-console', title: 'Server Console', description: 'Console appearance, font size, and scrollback settings', icon: 'fa-terminal',
            fields: [
                { key: 'console_font_size', label: 'Font Size', type: 'text', def: '14px' },
                { key: 'console_max_lines', label: 'Max Lines', type: 'number', def: 2000 },
                { key: 'console_font', label: 'Font Family', type: 'text', def: 'JetBrains Mono' },
                { key: 'console_cursor_blink', label: 'Cursor Blink', type: 'toggle', def: true },
            ]
        },
        {
            id: 'background-effects', title: 'Background Media & Effects', description: 'Control background images, videos, blur effects, and winter mode', icon: 'fa-photo',
            fields: [
                { key: 'bg_image', label: 'Background Image URL', type: 'text', def: '' },
                { key: 'bg_video', label: 'Background Video URL', type: 'text', def: '' },
                { key: 'bg_blur', label: 'Background Blur', type: 'number', def: 0 },
                { key: 'bg_overlay', label: 'Background Overlay', type: 'toggle', def: false },
                { key: 'particles', label: 'Particles Effect', type: 'toggle', def: true },
                { key: 'winter_mode', label: 'Winter Mode', type: 'toggle', def: false },
            ]
        },
        {
            id: 'server-artwork', title: 'Server Artwork', description: 'Custom server images, banners, and card artwork', icon: 'fa-server',
            fields: [
                { key: 'server_card_bg', label: 'Default Card Background', type: 'text', def: '' },
                { key: 'server_banner_bg', label: 'Default Banner Background', type: 'text', def: '' },
                { key: 'show_egg_icon', label: 'Show Egg Icon', type: 'toggle', def: true },
                { key: 'server_status_colors', label: 'Status Colors in Cards', type: 'toggle', def: true },
            ]
        },
        {
            id: 'email-branding', title: 'Email Branding', description: 'Customize email templates with your brand', icon: 'fa-envelope',
            fields: [
                { key: 'email_logo', label: 'Email Logo URL', type: 'text', def: '' },
                { key: 'email_footer', label: 'Email Footer Text', type: 'text', def: '' },
                { key: 'email_color', label: 'Email Accent Color', type: 'color', def: '#df3050' },
            ]
        },
        {
            id: 'external-links', title: 'External Links', description: 'Social media, documentation, and support links', icon: 'fa-link',
            fields: [
                { key: 'discord_url', label: 'Discord URL', type: 'text', def: '' },
                { key: 'github_url', label: 'GitHub URL', type: 'text', def: '' },
                { key: 'docs_url', label: 'Documentation URL', type: 'text', def: '' },
                { key: 'support_url', label: 'Support URL', type: 'text', def: '' },
                { key: 'twitter_url', label: 'Twitter/X URL', type: 'text', def: '' },
            ]
        },
        {
            id: 'browser-seo', title: 'Browser Identity & SEO', description: 'SEO metadata, OpenGraph tags, and PWA settings', icon: 'fa-globe',
            fields: [
                { key: 'seo_title', label: 'SEO Title', type: 'text', def: '' },
                { key: 'seo_description', label: 'SEO Description', type: 'textarea', def: '' },
                { key: 'seo_keywords', label: 'SEO Keywords', type: 'text', def: '' },
                { key: 'og_image', label: 'OpenGraph Image URL', type: 'text', def: '' },
                { key: 'theme_color', label: 'Browser Theme Color', type: 'color', def: '#0c0a09' },
                { key: 'pwa_enabled', label: 'Enable PWA', type: 'toggle', def: false },
            ]
        },
        {
            id: 'rendering', title: 'Rendering & System Display', description: 'Control how system information is displayed', icon: 'fa-desktop',
            fields: [
                { key: 'show_node_location', label: 'Show Node Location', type: 'toggle', def: true },
                { key: 'show_server_uptime', label: 'Show Server Uptime', type: 'toggle', def: true },
                { key: 'show_resource_bar', label: 'Show Resource Usage Bar', type: 'toggle', def: true },
                { key: 'display_mode', label: 'Default Display Mode', type: 'select', def: 'grid', options: [{l:'Grid',v:'grid'},{l:'List',v:'list'}] },
            ]
        },
        {
            id: 'user-personalization', title: 'User Personalization', description: 'Allow users to customize their own experience', icon: 'fa-user',
            fields: [
                { key: 'user_theme_toggle', label: 'Allow Theme Toggle', type: 'toggle', def: true },
                { key: 'user_sidebar_toggle', label: 'Allow Sidebar Collapse', type: 'toggle', def: true },
                { key: 'user_table_density', label: 'Table Density Options', type: 'toggle', def: true },
                { key: 'default_language', label: 'Default Language', type: 'select', def: 'en', options: [{l:'English',v:'en'},{l:'Arabic',v:'ar'},{l:'Chinese',v:'cn'},{l:'German',v:'de'},{l:'Spanish',v:'es'},{l:'French',v:'fr'},{l:'Hindi',v:'hi'},{l:'Indonesian',v:'id'},{l:'Italian',v:'it'},{l:'Japanese',v:'ja'},{l:'Polish',v:'pl'},{l:'Portuguese',v:'pt'},{l:'Russian',v:'ru'},{l:'Thai',v:'th'},{l:'Turkish',v:'tr'}] },
            ]
        },
    ];

    async function loadSettings() {
        try {
            const res = await fetch(API, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            settings = await res.json();
        } catch(e) { settings = {}; }
        renderGrid();
    }

    function renderGrid() {
        const grid = document.getElementById('gridView');
        const list = document.getElementById('listView');
        grid.innerHTML = '';
        list.innerHTML = '';
        categories.forEach(cat => {
            const card = document.createElement('div');
            card.className = 'settings-card';
            card.innerHTML = `<div class="settings-card-icon"><i class="fa ${cat.icon}"></i></div><h3>${cat.title}</h3><p>${cat.description}</p><button class="settings-card-btn"><i class="fa fa-cog"></i> Configure</button>`;
            card.onclick = () => openDetail(cat.id);
            grid.appendChild(card);
            list.appendChild(card.cloneNode(true));
        });
        attachDetailEvents();
    }

    function attachDetailEvents() {
        document.querySelectorAll('#gridView .settings-card').forEach(c => {
            c.onclick = () => openDetail(c.dataset.catId || c.querySelector('h3').textContent);
        });
        document.querySelectorAll('#listView .settings-card').forEach(c => {
            c.onclick = () => openDetail(c.dataset.catId || c.querySelector('h3').textContent);
        });
    }

    function openDetail(catId) {
        const cat = categories.find(c => c.id === catId || c.title === catId);
        if (!cat) return;
        activeCategory = cat;
        document.getElementById('gridView').style.display = 'none';
        document.getElementById('listView').style.display = 'none';
        const container = document.getElementById('detailContainer');
        container.innerHTML = '';
        const div = document.createElement('div');
        div.className = 'detail-view active';
        let fieldsHtml = cat.fields.map(f => {
            const val = settings[f.key] ?? f.def;
            if (f.type === 'color') {
                return `<div class="field-group"><label>${f.label}</label><div class="color-row"><input type="color" value="${val}" data-key="${f.key}"><input type="text" value="${val}" data-key="${f.key}"></div></div>`;
            } else if (f.type === 'toggle') {
                const active = val === true || val === 'true';
                return `<div class="field-group"><label>${f.label}</label><button class="toggle-btn ${active ? 'active' : ''}" data-key="${f.key}" data-value="${active}"><div class="toggle-slider"></div></button></div>`;
            } else if (f.type === 'select') {
                const opts = f.options.map(o => `<option value="${o.v}" ${val === o.v ? 'selected' : ''}>${o.l}</option>`).join('');
                return `<div class="field-group"><label>${f.label}</label><select data-key="${f.key}">${opts}</select></div>`;
            } else if (f.type === 'textarea') {
                return `<div class="field-group"><label>${f.label}</label><textarea rows="3" data-key="${f.key}">${val}</textarea></div>`;
            } else {
                return `<div class="field-group"><label>${f.label}</label><input type="${f.type}" value="${val}" data-key="${f.key}"></div>`;
            }
        }).join('');
        div.innerHTML = `<button class="back-btn" onclick="closeDetail()"><i class="fa fa-arrow-left"></i> Back</button><h2><i class="fa ${cat.icon}"></i> ${cat.title}</h2><p class="detail-desc">${cat.description}</p><div class="settings-fields">${fieldsHtml}</div>`;
        container.appendChild(div);
        bindFieldEvents(div);
    }

    function bindFieldEvents(container) {
        container.querySelectorAll('input[type="color"]').forEach(el => {
            el.addEventListener('input', e => {
                settings[e.target.dataset.key] = e.target.value;
                const sibling = e.target.parentElement.querySelector('input[type="text"]');
                if (sibling) sibling.value = e.target.value;
                markUnsaved();
            });
        });
        container.querySelectorAll('input[type="text"], input[type="number"], textarea, select').forEach(el => {
            el.addEventListener('change', e => { settings[e.target.dataset.key] = e.target.value; markUnsaved(); });
        });
        container.querySelectorAll('.toggle-btn').forEach(el => {
            el.addEventListener('click', e => {
                const btn = e.currentTarget;
                const active = btn.dataset.value === 'true';
                btn.dataset.value = (!active).toString();
                btn.classList.toggle('active');
                settings[btn.dataset.key] = (!active).toString();
                markUnsaved();
            });
        });
    }

    function closeDetail() {
        activeCategory = null;
        document.getElementById('detailContainer').innerHTML = '';
        document.getElementById('gridView').style.display = isListView ? 'none' : '';
        document.getElementById('listView').style.display = isListView ? '' : 'none';
    }

    function markUnsaved() {
        document.getElementById('saveStatus').innerHTML = '<i class="fa fa-circle" style="color:#f59e0b;"></i> Unsaved changes';
    }

    async function saveSettings() {
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify(settings)
            });
            if (res.ok) {
                document.getElementById('saveStatus').innerHTML = '<i class="fa fa-check-circle" style="color:#22c55e;"></i> All changes saved';
                showToast('Settings saved successfully', 'success');
            }
        } catch(e) { showToast('Failed to save settings', 'error'); }
    }

    async function resetSettings() {
        if (!confirm('Reset all settings to defaults?')) return;
        try {
            await fetch(API + '/reset', { method: 'POST', headers: { 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN } });
            await loadSettings();
            closeDetail();
            showToast('Settings reset to defaults', 'success');
        } catch(e) { showToast('Failed to reset', 'error'); }
    }

    function exportSettings() {
        const blob = new Blob([JSON.stringify(settings, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = 'hyper-settings.json'; a.click();
        URL.revokeObjectURL(url);
        showToast('Settings exported', 'success');
    }

    function importSettings(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = async ev => {
            try {
                settings = JSON.parse(ev.target.result);
                await saveSettings();
                closeDetail();
                renderGrid();
                showToast('Settings imported', 'success');
            } catch(e) { showToast('Invalid JSON file', 'error'); }
        };
        reader.readAsText(file);
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('viewGrid').onclick = () => { isListView = false; document.getElementById('gridView').style.display = ''; document.getElementById('listView').style.display = 'none'; document.getElementById('viewGrid').classList.add('active'); document.getElementById('viewList').classList.remove('active'); };
    document.getElementById('viewList').onclick = () => { isListView = true; document.getElementById('gridView').style.display = 'none'; document.getElementById('listView').style.display = ''; document.getElementById('viewList').classList.add('active'); document.getElementById('viewGrid').classList.remove('active'); };

    loadSettings();
    </script>
</body>
</html>
