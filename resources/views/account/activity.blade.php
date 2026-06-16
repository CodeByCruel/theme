<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activity Log — BSDK V1</title>
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
        .page-header { margin-bottom: 32px; display: flex; justify-content: space-between; align-items: flex-start; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .filter-bar { display: flex; gap: 6px; margin-bottom: 24px; flex-wrap: wrap; }
        .filter-btn { padding: 7px 14px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; font-size: 13px; font-weight: 500; transition: all 0.2s; font-family: inherit; }
        .filter-btn:hover { border-color: rgba(223, 48, 80, 0.3); color: #fafafa; }
        .filter-btn.active { background: rgba(223, 48, 80, 0.15); color: #df3050; border-color: rgba(223, 48, 80, 0.3); }
        .timeline { position: relative; padding-left: 32px; }
        .timeline::before { content: ''; position: absolute; left: 15px; top: 0; bottom: 0; width: 1px; background: rgba(255,255,255,0.08); }
        .timeline-item { position: relative; margin-bottom: 2px; padding: 16px 20px; background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; transition: all 0.2s; }
        .timeline-item:hover { border-color: rgba(223, 48, 80, 0.2); }
        .timeline-dot { position: absolute; left: -25px; top: 20px; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #0c0a09; z-index: 1; }
        .timeline-dot.login { background: #22c55e; }
        .timeline-dot.server { background: #3b82f6; }
        .timeline-dot.api { background: #f59e0b; }
        .timeline-dot.admin { background: #df3050; }
        .timeline-dot.default { background: #a1a1aa; }
        .timeline-header { display: flex; align-items: center; gap: 10px; margin-bottom: 6px; }
        .timeline-icon { width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 14px; flex-shrink: 0; }
        .timeline-icon.login { background: rgba(34,197,94,0.1); color: #22c55e; }
        .timeline-icon.server { background: rgba(59,130,246,0.1); color: #3b82f6; }
        .timeline-icon.api { background: rgba(245,158,11,0.1); color: #f59e0b; }
        .timeline-icon.admin { background: rgba(223,48,80,0.1); color: #df3050; }
        .timeline-icon.default { background: rgba(161,161,170,0.1); color: #a1a1aa; }
        .timeline-desc { font-size: 14px; color: #fafafa; font-weight: 500; }
        .timeline-meta { display: flex; align-items: center; gap: 16px; font-size: 12px; color: #a1a1aa; margin-top: 4px; }
        .timeline-meta i { margin-right: 4px; }
        .timeline-ip { font-family: 'JetBrains Mono', monospace; background: #0c0a09; padding: 2px 8px; border-radius: 4px; border: 1px solid rgba(255,255,255,0.06); }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; color: #fafafa; margin-bottom: 8px; }
        .empty-state p { color: #a1a1aa; font-size: 14px; }
        .pagination { display: flex; align-items: center; justify-content: center; gap: 8px; margin-top: 32px; }
        .page-btn { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; font-weight: 500; transition: all 0.2s; font-family: inherit; }
        .page-btn:hover { border-color: #df3050; color: #df3050; }
        .page-btn.active { background: #df3050; color: #fff; border-color: #df3050; }
        .page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        .page-info { font-size: 13px; color: #a1a1aa; }
        .footer-bar { position: fixed; bottom: 0; left: 260px; right: 0; height: 64px; background: #191919cc; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; z-index: 100; backdrop-filter: blur(20px); }
        .save-status { font-size: 13px; display: flex; align-items: center; gap: 8px; color: #a1a1aa; }
        .save-status i { color: #22c55e; }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        .loading-spinner { display: inline-block; width: 20px; height: 20px; border: 2px solid rgba(255,255,255,0.1); border-top-color: #df3050; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .footer-bar { left: 0; }
            .filter-bar { gap: 4px; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <nav class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">H</div>
                <div class="brand-text">
                    <h2>Hyper Panel</h2>
                    <span>BSDK V1 Account</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="/dashboard" class="nav-item"><i class="fa fa-th-large"></i> Dashboard</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Account</div>
                <a href="/account" class="nav-item"><i class="fa fa-user"></i> Account</a>
                <a href="/account/api" class="nav-item"><i class="fa fa-key"></i> API Keys</a>
                <a href="/account/ssh" class="nav-item"><i class="fa fa-terminal"></i> SSH Keys</a>
                <a href="/account/activity" class="nav-item active"><i class="fa fa-clock-o"></i> Activity Log</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fa fa-clock-o"></i> Activity Log</h1>
                    <p>View your recent account activity and login history</p>
                </div>
            </div>

            <div class="filter-bar">
                <button class="filter-btn active" data-filter="all" onclick="setFilter('all', this)">All</button>
                <button class="filter-btn" data-filter="login" onclick="setFilter('login', this)">Login</button>
                <button class="filter-btn" data-filter="server" onclick="setFilter('server', this)">Server</button>
                <button class="filter-btn" data-filter="api" onclick="setFilter('api', this)">API</button>
                <button class="filter-btn" data-filter="admin" onclick="setFilter('admin', this)">Admin</button>
            </div>

            <div id="timeline" class="timeline"></div>

            <div id="emptyState" class="empty-state" style="display:none;">
                <i class="fa fa-clock-o"></i>
                <h3>No Activity Found</h3>
                <p>No activity records match your current filter.</p>
            </div>

            <div class="pagination" id="pagination"></div>
        </main>
    </div>

    <div class="footer-bar">
        <div class="save-status"><i class="fa fa-clock-o"></i> Activity Log</div>
        <div class="save-status">Page <span id="currentPage">1</span> of <span id="totalPages">1</span></div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/client/activity';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let activities = [];
    let currentFilter = 'all';
    let currentPage = 1;
    let totalPages = 1;
    let perPage = 20;

    const typeIcons = {
        login: { icon: 'fa-sign-in', label: 'Login' },
        server: { icon: 'fa-server', label: 'Server' },
        api: { icon: 'fa-code', label: 'API' },
        admin: { icon: 'fa-shield', label: 'Admin' }
    };

    async function loadActivities(page = 1) {
        document.getElementById('timeline').innerHTML = '<div style="text-align:center;padding:40px;"><div class="loading-spinner"></div></div>';
        document.getElementById('emptyState').style.display = 'none';
        document.getElementById('pagination').innerHTML = '';
        try {
            let url = `${API}?page=${page}&per_page=${perPage}`;
            if (currentFilter !== 'all') url += `&type=${currentFilter}`;
            const res = await fetch(url, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error();
            const data = await res.json();
            activities = data.data || data.activities || data || [];
            if (data.meta) { totalPages = data.meta.last_page || 1; currentPage = data.meta.current_page || 1; }
            else if (data.current_page) { totalPages = data.last_page || 1; currentPage = data.current_page; }
            else { totalPages = data.total_pages || 1; currentPage = data.page || page; }
            if (data.per_page) perPage = data.per_page;
        } catch(e) { activities = []; showToast('Failed to load activity log', 'error'); }
        renderTimeline();
        renderPagination();
    }

    function renderTimeline() {
        const container = document.getElementById('timeline');
        const empty = document.getElementById('emptyState');
        container.innerHTML = '';
        document.getElementById('currentPage').textContent = currentPage;
        document.getElementById('totalPages').textContent = totalPages;
        if (activities.length === 0) { empty.style.display = ''; return; }
        empty.style.display = 'none';
        activities.forEach(act => {
            const type = (act.type || act.event || 'default').toLowerCase();
            const info = typeIcons[type] || { icon: 'fa-circle', label: type.charAt(0).toUpperCase() + type.slice(1) };
            const desc = escapeHtml(act.description || act.message || act.event || 'Activity recorded');
            const ip = act.ip_address || act.ip || '—';
            const timestamp = formatTime(act.created_at || act.timestamp || act.date);
            const dotClass = typeIcons[type] ? type : 'default';
            const iconClass = typeIcons[type] ? type : 'default';
            const item = document.createElement('div');
            item.className = 'timeline-item';
            item.innerHTML = `
                <div class="timeline-dot ${dotClass}"></div>
                <div class="timeline-header">
                    <div class="timeline-icon ${iconClass}"><i class="fa ${info.icon}"></i></div>
                    <span class="timeline-desc">${desc}</span>
                </div>
                <div class="timeline-meta">
                    <span><i class="fa fa-globe"></i> <span class="timeline-ip">${escapeHtml(ip)}</span></span>
                    <span><i class="fa fa-clock-o"></i> ${timestamp}</span>
                </div>
            `;
            container.appendChild(item);
        });
    }

    function renderPagination() {
        const container = document.getElementById('pagination');
        container.innerHTML = '';
        if (totalPages <= 1) return;
        const prev = document.createElement('button');
        prev.className = 'page-btn';
        prev.innerHTML = '<i class="fa fa-chevron-left"></i>';
        prev.disabled = currentPage <= 1;
        prev.onclick = () => { if (currentPage > 1) loadActivities(currentPage - 1); };
        container.appendChild(prev);
        const range = getPageRange(currentPage, totalPages);
        range.forEach(p => {
            if (p === '...') {
                const dots = document.createElement('span');
                dots.className = 'page-info';
                dots.textContent = '...';
                container.appendChild(dots);
            } else {
                const btn = document.createElement('button');
                btn.className = 'page-btn' + (p === currentPage ? ' active' : '');
                btn.textContent = p;
                btn.onclick = () => loadActivities(p);
                container.appendChild(btn);
            }
        });
        const next = document.createElement('button');
        next.className = 'page-btn';
        next.innerHTML = '<i class="fa fa-chevron-right"></i>';
        next.disabled = currentPage >= totalPages;
        next.onclick = () => { if (currentPage < totalPages) loadActivities(currentPage + 1); };
        container.appendChild(next);
    }

    function getPageRange(current, total) {
        if (total <= 7) return Array.from({ length: total }, (_, i) => i + 1);
        const pages = [];
        if (current <= 4) { for (let i = 1; i <= 5; i++) pages.push(i); pages.push('...', total); }
        else if (current >= total - 3) { pages.push(1, '...'); for (let i = total - 4; i <= total; i++) pages.push(i); }
        else { pages.push(1, '...', current - 1, current, current + 1, '...', total); }
        return pages;
    }

    function setFilter(filter, el) {
        currentFilter = filter;
        currentPage = 1;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        loadActivities(1);
    }

    function formatTime(ts) {
        if (!ts) return '—';
        const d = new Date(ts);
        const now = new Date();
        const diff = now - d;
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
        if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
        if (diff < 604800000) return Math.floor(diff / 86400000) + 'd ago';
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function escapeHtml(str) {
        const d = document.createElement('div'); d.textContent = str; return d.innerHTML;
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    loadActivities(1);
    </script>
</body>
</html>