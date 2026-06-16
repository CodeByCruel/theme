<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications — BSDK V1</title>
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
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; }
        .btn.danger:hover { background: #991b1b; }
        .btn.sm { padding: 6px 12px; font-size: 12px; }
        .filter-tabs { display: flex; gap: 4px; margin-bottom: 20px; background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 4px; width: fit-content; }
        .filter-tab { padding: 8px 16px; border: none; background: transparent; color: #a1a1aa; border-radius: 8px; font-size: 13px; font-weight: 500; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 6px; }
        .filter-tab:hover { color: #fff; background: rgba(255,255,255,0.04); }
        .filter-tab.active { background: #df3050; color: #fff; }
        .filter-tab .count { background: rgba(255,255,255,0.15); padding: 1px 7px; border-radius: 10px; font-size: 11px; font-weight: 600; }
        .filter-tab.active .count { background: rgba(255,255,255,0.25); }
        .actions-bar { display: flex; gap: 8px; margin-bottom: 20px; }
        .notification-list { display: flex; flex-direction: column; gap: 4px; }
        .notification-item { display: flex; align-items: flex-start; gap: 14px; padding: 16px 20px; background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; cursor: pointer; transition: all 0.2s; position: relative; }
        .notification-item:hover { border-color: rgba(223, 48, 80, 0.3); background: #1c19177a; }
        .notification-item.unread { border-left: 3px solid #df3050; }
        .notification-item.unread::after { content: ''; position: absolute; top: 18px; right: 16px; width: 8px; height: 8px; border-radius: 50%; background: #df3050; }
        .notif-icon { width: 40px; height: 40px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 16px; flex-shrink: 0; }
        .notif-icon.server { background: rgba(59, 130, 246, 0.12); color: #3b82f6; }
        .notif-icon.account { background: rgba(168, 85, 247, 0.12); color: #a855f7; }
        .notif-icon.system { background: rgba(245, 158, 11, 0.12); color: #f59e0b; }
        .notif-icon.alert { background: rgba(239, 68, 68, 0.12); color: #ef4444; }
        .notif-icon.info { background: rgba(34, 197, 94, 0.12); color: #22c55e; }
        .notif-content { flex: 1; min-width: 0; }
        .notif-content h4 { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .notif-content p { font-size: 13px; color: #a1a1aa; line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
        .notif-time { font-size: 12px; color: #52525b; white-space: nowrap; flex-shrink: 0; margin-top: 2px; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a1a1aa; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; display: block; }
        .empty-state h3 { font-size: 18px; color: #fff; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; max-width: 360px; margin: 0 auto; }
        .loading { text-align: center; padding: 40px; color: #a1a1aa; }
        .loading i { animation: spin 1s linear infinite; display: inline-block; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .toast { position: fixed; bottom: 24px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 400; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
            .filter-tabs { overflow-x: auto; width: 100%; }
            .actions-bar { flex-wrap: wrap; }
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
                    <span>BSDK V1 Client</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Navigation</div>
                <a href="/dashboard" class="nav-item"><i class="fa fa-th-large"></i> Dashboard</a>
                <a href="/notifications" class="nav-item active"><i class="fa fa-bell"></i> Notifications</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Quick Links</div>
                <a href="/staff-request" class="nav-item"><i class="fa fa-life-ring"></i> Staff Request</a>
                <a href="/" class="nav-item"><i class="fa fa-home"></i> Panel Home</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fa fa-bell"></i> Notifications</h1>
                    <p>Stay updated with server alerts, account activity, and system messages</p>
                </div>
            </div>

            <div class="filter-tabs" id="filterTabs">
                <button class="filter-tab active" data-filter="all">All <span class="count" id="countAll">0</span></button>
                <button class="filter-tab" data-filter="unread">Unread <span class="count" id="countUnread">0</span></button>
                <button class="filter-tab" data-filter="server">Server <span class="count" id="countServer">0</span></button>
                <button class="filter-tab" data-filter="account">Account <span class="count" id="countAccount">0</span></button>
                <button class="filter-tab" data-filter="system">System <span class="count" id="countSystem">0</span></button>
            </div>

            <div class="actions-bar">
                <button class="btn secondary sm" onclick="markAllRead()"><i class="fa fa-check-double"></i> Mark All as Read</button>
                <button class="btn danger sm" onclick="clearAll()"><i class="fa fa-trash"></i> Clear All</button>
            </div>

            <div id="notificationList" class="notification-list">
                <div class="loading"><i class="fa fa-spinner"></i> Loading notifications...</div>
            </div>
        </main>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/client/notifications';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let notifications = [];
    let activeFilter = 'all';

    const typeIcons = {
        server: { icon: 'fa-server', cls: 'server' },
        account: { icon: 'fa-user', cls: 'account' },
        system: { icon: 'fa-cog', cls: 'system' },
        alert: { icon: 'fa-exclamation-triangle', cls: 'alert' },
        info: { icon: 'fa-info-circle', cls: 'info' },
    };

    async function loadNotifications() {
        const el = document.getElementById('notificationList');
        el.innerHTML = '<div class="loading"><i class="fa fa-spinner"></i> Loading notifications...</div>';
        try {
            const res = await fetch(API, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            notifications = Array.isArray(data) ? data : (data.data || []);
        } catch(e) { notifications = []; }
        updateCounts();
        renderNotifications();
    }

    function updateCounts() {
        document.getElementById('countAll').textContent = notifications.length;
        document.getElementById('countUnread').textContent = notifications.filter(n => !n.read && !n.is_read).length;
        document.getElementById('countServer').textContent = notifications.filter(n => n.type === 'server').length;
        document.getElementById('countAccount').textContent = notifications.filter(n => n.type === 'account').length;
        document.getElementById('countSystem').textContent = notifications.filter(n => n.type === 'system').length;
    }

    function renderNotifications() {
        const el = document.getElementById('notificationList');
        let filtered = notifications;
        if (activeFilter === 'unread') {
            filtered = notifications.filter(n => !n.read && !n.is_read);
        } else if (['server', 'account', 'system'].includes(activeFilter)) {
            filtered = notifications.filter(n => n.type === activeFilter);
        }

        if (filtered.length === 0) {
            el.innerHTML = `<div class="empty-state"><i class="fa fa-bell-slash"></i><h3>${activeFilter === 'all' ? 'No Notifications' : 'Nothing here'}</h3><p>${activeFilter === 'all' ? 'You\'re all caught up! Notifications will appear here.' : 'No notifications match this filter.'}</p></div>`;
            return;
        }

        el.innerHTML = filtered.map(n => {
            const isUnread = !n.read && !n.is_read;
            const t = typeIcons[n.type] || typeIcons.info;
            return `
            <div class="notification-item ${isUnread ? 'unread' : ''}" onclick="markRead('${n.id}')">
                <div class="notif-icon ${t.cls}"><i class="fa ${t.icon}"></i></div>
                <div class="notif-content">
                    <h4>${esc(n.title || n.subject || 'Notification')}</h4>
                    <p>${esc(n.message || n.body || n.description || '')}</p>
                </div>
                <span class="notif-time">${formatDate(n.created_at)}</span>
            </div>`;
        }).join('');
    }

    async function markRead(id) {
        const n = notifications.find(x => String(x.id) === String(id));
        if (n && !n.read && !n.is_read) {
            n.read = true;
            n.is_read = true;
            updateCounts();
            renderNotifications();
            try {
                await fetch(API + '/' + id + '/read', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
                });
            } catch(e) {}
        }
    }

    async function markAllRead() {
        notifications.forEach(n => { n.read = true; n.is_read = true; });
        updateCounts();
        renderNotifications();
        try {
            await fetch(API + '/read-all', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            showToast('All notifications marked as read', 'success');
        } catch(e) { showToast('Failed to mark as read', 'error'); }
    }

    async function clearAll() {
        if (!confirm('Clear all notifications? This cannot be undone.')) return;
        notifications = [];
        updateCounts();
        renderNotifications();
        try {
            await fetch(API + '/clear', {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            showToast('All notifications cleared', 'success');
        } catch(e) { showToast('Failed to clear notifications', 'error'); }
    }

    document.getElementById('filterTabs').addEventListener('click', e => {
        const tab = e.target.closest('.filter-tab');
        if (!tab) return;
        document.querySelectorAll('.filter-tab').forEach(t => t.classList.remove('active'));
        tab.classList.add('active');
        activeFilter = tab.dataset.filter;
        renderNotifications();
    });

    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function formatDate(d) {
        if (!d) return '';
        const dt = new Date(d);
        if (isNaN(dt)) return d;
        const now = new Date();
        const diff = now - dt;
        if (diff < 60000) return 'Just now';
        if (diff < 3600000) return Math.floor(diff / 60000) + 'm ago';
        if (diff < 86400000) return Math.floor(diff / 3600000) + 'h ago';
        if (diff < 604800000) return Math.floor(diff / 86400000) + 'd ago';
        return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    loadNotifications();
    </script>
</body>
</html>
