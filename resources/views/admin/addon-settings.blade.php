<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addon Settings — BSDK V1</title>
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
        .page-header { margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .toolbar { display: flex; align-items: center; gap: 12px; padding: 16px 0; border-bottom: 1px solid rgba(255,255,255,0.08); margin-bottom: 20px; }
        .search-box { position: relative; flex: 1; max-width: 320px; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a1a1aa; font-size: 14px; }
        .search-box input { width: 100%; padding: 10px 14px 10px 36px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; }
        .search-box input:focus { border-color: #df3050; }
        .view-toggle { display: flex; gap: 4px; }
        .view-toggle button { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; transition: all 0.2s; }
        .view-toggle button.active, .view-toggle button:hover { background: #df3050; color: #fff; border-color: #df3050; }
        .addon-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 16px; padding-bottom: 40px; }
        .addon-grid.list { grid-template-columns: 1fr; }
        .addon-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 20px; display: flex; flex-direction: column; gap: 12px; transition: all 0.2s; }
        .addon-card:hover { border-color: #df3050; }
        .addon-card.enabled { border-color: rgba(223, 48, 80, 0.3); }
        .addon-card-top { display: flex; align-items: flex-start; gap: 12px; }
        .addon-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(223, 48, 80, 0.1); display: flex; align-items: center; justify-content: center; font-size: 16px; color: #df3050; flex-shrink: 0; }
        .addon-card.enabled .addon-icon { background: rgba(223, 48, 80, 0.2); }
        .addon-info h3 { font-size: 15px; font-weight: 600; color: #fff; }
        .addon-info p { font-size: 12px; color: #a1a1aa; line-height: 1.4; margin-top: 2px; }
        .addon-category { display: inline-block; font-size: 10px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; color: #a1a1aa; background: rgba(255,255,255,0.05); padding: 2px 8px; border-radius: 4px; margin-top: 4px; }
        .addon-card-actions { display: flex; align-items: center; justify-content: space-between; margin-top: auto; padding-top: 8px; }
        .toggle-btn { position: relative; width: 48px; height: 26px; border: none; background: #292524; border-radius: 13px; cursor: pointer; transition: background 0.3s; }
        .toggle-btn.active { background: #df3050; }
        .toggle-slider { position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: transform 0.3s; }
        .toggle-btn.active .toggle-slider { transform: translateX(22px); }
        .status-text { font-size: 11px; font-weight: 600; text-transform: uppercase; }
        .status-text.enabled { color: #22c55e; }
        .status-text.disabled { color: #a1a1aa; }
        .footer-bar { position: fixed; bottom: 0; left: 260px; right: 0; height: 64px; background: #191919cc; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; z-index: 100; backdrop-filter: blur(20px); }
        .save-status { font-size: 13px; display: flex; align-items: center; gap: 8px; color: #a1a1aa; }
        .footer-actions { display: flex; gap: 8px; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .counter { font-size: 13px; color: #a1a1aa; display: flex; align-items: center; gap: 4px; }
        .counter .on { color: #22c55e; font-weight: 600; }
        .counter .off { color: #a1a1aa; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a1a1aa; }
        .empty-state i { font-size: 48px; margin-bottom: 16px; color: #292524; }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .footer-bar { left: 0; }
            .addon-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <nav class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">H</div>
                <div class="brand-text">
                    <h2>Addon Settings</h2>
                    <span>BSDK V1 Admin</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Navigation</div>
                <a href="/admin/bsdk-theme" class="nav-item"><i class="fa fa-paint-brush"></i> BSDK Theme</a>
                <a href="/admin/bsd-settings" class="nav-item"><i class="fa fa-magic"></i> BSD Settings</a>
                <a href="/admin/addon-settings" class="nav-item active"><i class="fa fa-puzzle-piece"></i> Addon Settings</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Quick Links</div>
                <a href="/admin" class="nav-item"><i class="fa fa-arrow-left"></i> Back to Admin</a>
                <a href="/" class="nav-item"><i class="fa fa-home"></i> Panel Home</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <h1><i class="fa fa-puzzle-piece"></i> Addon Settings</h1>
                <p>Enable or disable addons to extend your panel's functionality</p>
            </div>

            <div class="toolbar">
                <div class="search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search addons...">
                </div>
                <div class="counter">
                    <span class="on" id="countOn">0</span> / <span id="countTotal">0</span> enabled
                </div>
                <div class="view-toggle">
                    <button id="viewGrid" class="active" title="Grid View"><i class="fa fa-th"></i></button>
                    <button id="viewList" title="List View"><i class="fa fa-list"></i></button>
                </div>
            </div>

            <div id="addonGrid" class="addon-grid"></div>
            <div id="addonList" class="addon-grid list" style="display:none;"></div>
            <div id="emptyState" class="empty-state" style="display:none;">
                <i class="fa fa-search"></i>
                <p>No addons found</p>
            </div>
        </main>
    </div>

    <div class="footer-bar">
        <div class="save-status" id="saveStatus"><i class="fa fa-check-circle" style="color:#22c55e;"></i> All changes saved</div>
        <div class="footer-actions">
            <button class="btn secondary" onclick="exportAddons()"><i class="fa fa-download"></i> Export</button>
            <button class="btn secondary" onclick="document.getElementById('importFile').click()"><i class="fa fa-upload"></i> Import</button>
            <input type="file" id="importFile" accept=".json" style="display:none;" onchange="importAddons(event)">
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/admin/bsdk/addons';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let addons = [];
    let searchQuery = '';
    let isListView = false;

    const categoryIcons = {
        'Core': 'fa-cube', 'Account': 'fa-user', 'Layout': 'fa-paint-brush',
        'Server': 'fa-server', 'Console': 'fa-terminal', 'Files': 'fa-folder',
        'Security': 'fa-shield', 'Integration': 'fa-plug', 'Gaming': 'fa-gamepad',
        'Minecraft': 'fa-cube', 'Admin': 'fa-key', 'FiveM': 'fa-car',
    };

    async function loadAddons() {
        try {
            const res = await fetch(API, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            addons = await res.json();
        } catch(e) { addons = []; }
        render();
    }

    function render() {
        const filtered = addons.filter(a => {
            if (!searchQuery) return true;
            return a.name.toLowerCase().includes(searchQuery.toLowerCase()) ||
                   a.description.toLowerCase().includes(searchQuery.toLowerCase()) ||
                   (a.category || '').toLowerCase().includes(searchQuery.toLowerCase());
        });

        const onCount = addons.filter(a => a.enabled).length;
        document.getElementById('countOn').textContent = onCount;
        document.getElementById('countTotal').textContent = addons.length;

        if (filtered.length === 0) {
            document.getElementById('addonGrid').style.display = 'none';
            document.getElementById('addonList').style.display = 'none';
            document.getElementById('emptyState').style.display = '';
            return;
        }
        document.getElementById('emptyState').style.display = 'none';

        const html = filtered.map(a => {
            const icon = categoryIcons[a.category] || 'fa-cube';
            return `<div class="addon-card ${a.enabled ? 'enabled' : ''}" data-id="${a.id}">
                <div class="addon-card-top">
                    <div class="addon-icon"><i class="fa ${a.icon || icon}"></i></div>
                    <div class="addon-info">
                        <h3>${a.name}</h3>
                        <p>${a.description}</p>
                        <span class="addon-category">${a.category || 'General'}</span>
                    </div>
                </div>
                <div class="addon-card-actions">
                    <span class="status-text ${a.enabled ? 'enabled' : 'disabled'}">${a.enabled ? 'Enabled' : 'Disabled'}</span>
                    <button class="toggle-btn ${a.enabled ? 'active' : ''}" onclick="toggleAddon('${a.id}', ${!a.enabled})">
                        <div class="toggle-slider"></div>
                    </button>
                </div>
            </div>`;
        }).join('');

        document.getElementById(isListView ? 'addonList' : 'addonGrid').innerHTML = html;
        document.getElementById(isListView ? 'addonGrid' : 'addonList').innerHTML = '';
    }

    async function toggleAddon(id, enabled) {
        try {
            const res = await fetch(`${API}/${id}/toggle`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ enabled })
            });
            if (res.ok) {
                const addon = addons.find(a => a.id === id);
                if (addon) addon.enabled = enabled;
                render();
                showToast(`${id} ${enabled ? 'enabled' : 'disabled'}`, 'success');
            }
        } catch(e) { showToast('Failed to toggle addon', 'error'); }
    }

    function exportAddons() {
        const data = {};
        addons.forEach(a => { data[a.id] = a.enabled; });
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a'); a.href = url; a.download = 'addon-settings.json'; a.click();
        URL.revokeObjectURL(url);
        showToast('Addons exported', 'success');
    }

    function importAddons(e) {
        const file = e.target.files[0];
        if (!file) return;
        const reader = new FileReader();
        reader.onload = async ev => {
            try {
                const data = JSON.parse(ev.target.result);
                for (const [id, enabled] of Object.entries(data)) {
                    await fetch(`${API}/${id}/toggle`, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                        body: JSON.stringify({ enabled })
                    });
                }
                await loadAddons();
                showToast('Addons imported', 'success');
            } catch(e) { showToast('Invalid JSON', 'error'); }
        };
        reader.readAsText(file);
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('searchInput').addEventListener('input', e => { searchQuery = e.target.value; render(); });
    document.getElementById('viewGrid').onclick = () => { isListView = false; document.getElementById('addonGrid').style.display = ''; document.getElementById('addonList').style.display = 'none'; document.getElementById('viewGrid').classList.add('active'); document.getElementById('viewList').classList.remove('active'); render(); };
    document.getElementById('viewList').onclick = () => { isListView = true; document.getElementById('addonList').style.display = ''; document.getElementById('addonGrid').style.display = 'none'; document.getElementById('viewList').classList.add('active'); document.getElementById('viewGrid').classList.remove('active'); render(); };

    loadAddons();
    </script>
</body>
</html>
