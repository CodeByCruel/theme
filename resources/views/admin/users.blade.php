<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management — BSDK V1</title>
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
        .page-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 24px; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .toolbar { display: flex; align-items: center; gap: 12px; margin-bottom: 24px; flex-wrap: wrap; }
        .search-box { display: flex; align-items: center; gap: 8px; background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 0 14px; height: 40px; flex: 1; max-width: 360px; }
        .search-box i { color: #a1a1aa; font-size: 14px; }
        .search-box input { background: none; border: none; color: #fff; font-size: 13px; width: 100%; outline: none; }
        .search-box input::placeholder { color: #52525b; }
        .filter-group { display: flex; gap: 4px; background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 3px; }
        .filter-btn { padding: 6px 14px; border: none; border-radius: 6px; background: transparent; color: #a1a1aa; font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s; white-space: nowrap; }
        .filter-btn.active { background: #df3050; color: #fff; }
        .filter-btn:hover:not(.active) { color: #fff; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; white-space: nowrap; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: rgba(127, 29, 29, 0.4); color: #fca5a5; border: 1px solid rgba(239, 68, 68, 0.2); }
        .btn.danger:hover { background: rgba(185, 28, 28, 0.4); color: #ef4444; border-color: #ef4444; }
        .btn.sm { padding: 5px 10px; font-size: 12px; }
        .stats-row { display: flex; gap: 16px; margin-bottom: 24px; }
        .stat-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 10px; padding: 16px 20px; flex: 1; }
        .stat-card .label { font-size: 12px; color: #a1a1aa; font-weight: 500; margin-bottom: 4px; }
        .stat-card .value { font-size: 24px; font-weight: 700; color: #fff; }
        .stat-card .value.accent { color: #df3050; }
        .table-container { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; }
        .table-container table { width: 100%; border-collapse: collapse; }
        .table-container thead th { padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 600; letter-spacing: 1px; text-transform: uppercase; color: #a1a1aa; background: rgba(0,0,0,0.2); border-bottom: 1px solid rgba(255,255,255,0.08); }
        .table-container tbody tr { border-bottom: 1px solid rgba(255,255,255,0.04); transition: background 0.15s; }
        .table-container tbody tr:hover { background: rgba(223, 48, 80, 0.05); }
        .table-container tbody tr:last-child { border-bottom: none; }
        .table-container tbody td { padding: 12px 16px; font-size: 13px; color: #e4e4e7; vertical-align: middle; }
        .user-cell { display: flex; align-items: center; gap: 12px; }
        .avatar { width: 36px; height: 36px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 13px; color: #fff; flex-shrink: 0; }
        .avatar.admin-avatar { background: linear-gradient(135deg, #df3050, #ff6b6b); }
        .avatar.user-avatar { background: linear-gradient(135deg, #3b82f6, #60a5fa); }
        .user-name { font-weight: 600; color: #fff; }
        .user-email { color: #a1a1aa; font-size: 12px; }
        .role-badge { padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; }
        .role-badge.admin { background: rgba(223, 48, 80, 0.15); color: #df3050; }
        .role-badge.user { background: rgba(59, 130, 246, 0.15); color: #3b82f6; }
        .actions-cell { display: flex; gap: 6px; }
        .icon-btn { width: 32px; height: 32px; border: 1px solid rgba(255,255,255,0.08); background: transparent; color: #a1a1aa; border-radius: 6px; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; font-size: 13px; }
        .icon-btn:hover { border-color: #df3050; color: #df3050; }
        .icon-btn.danger:hover { border-color: #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.1); }
        .pagination { display: flex; align-items: center; justify-content: space-between; padding: 16px 20px; border-top: 1px solid rgba(255,255,255,0.08); }
        .pagination-info { font-size: 13px; color: #a1a1aa; }
        .pagination-btns { display: flex; gap: 4px; }
        .page-btn { width: 34px; height: 34px; border: 1px solid rgba(255,255,255,0.08); background: transparent; color: #a1a1aa; border-radius: 6px; cursor: pointer; font-size: 13px; font-weight: 500; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .page-btn:hover { border-color: #df3050; color: #df3050; }
        .page-btn.active { background: #df3050; border-color: #df3050; color: #fff; }
        .page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; }
        .empty-state h3 { font-size: 16px; color: #a1a1aa; margin-bottom: 8px; }
        .empty-state p { font-size: 13px; color: #52525b; }
        .loading { text-align: center; padding: 40px; color: #a1a1aa; }
        .loading i { animation: spin 1s linear infinite; display: inline-block; margin-right: 8px; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        .toast { position: fixed; bottom: 24px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); backdrop-filter: blur(4px); z-index: 300; display: flex; align-items: center; justify-content: center; opacity: 0; pointer-events: none; transition: opacity 0.2s; }
        .modal-overlay.active { opacity: 1; pointer-events: all; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.1); border-radius: 14px; padding: 28px; width: 420px; max-width: 90vw; transform: scale(0.95); transition: transform 0.2s; }
        .modal-overlay.active .modal { transform: scale(1); }
        .modal h3 { font-size: 17px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .modal p { font-size: 13px; color: #a1a1aa; margin-bottom: 24px; line-height: 1.6; }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 600; color: #a1a1aa; margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .form-group input, .form-group select { width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; }
        .form-group input:focus, .form-group select:focus { border-color: #df3050; }
        .form-row { display: flex; gap: 12px; }
        .form-row .form-group { flex: 1; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .stats-row { flex-direction: column; }
            .toolbar { flex-direction: column; align-items: stretch; }
            .search-box { max-width: 100%; }
            .table-container { overflow-x: auto; }
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
                <div class="nav-section-title">Administration</div>
                <a href="/admin/bsdk-theme" class="nav-item"><i class="fa fa-paint-brush"></i> BSDK Theme</a>
                <a href="/admin/bsd-settings" class="nav-item"><i class="fa fa-magic"></i> BSD Settings</a>
                <a href="/admin/addon-settings" class="nav-item"><i class="fa fa-puzzle-piece"></i> Addon Settings</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Infrastructure</div>
                <a href="/admin/servers" class="nav-item"><i class="fa fa-server"></i> Servers</a>
                <a href="/admin/users" class="nav-item active"><i class="fa fa-users"></i> Users</a>
                <a href="/admin/nodes" class="nav-item"><i class="fa fa-sitemap"></i> Nodes</a>
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
                    <h1><i class="fa fa-users"></i> User Management</h1>
                    <p>Manage panel users, roles, and access permissions</p>
                </div>
                <button class="btn primary" onclick="openCreateModal()"><i class="fa fa-plus"></i> Create User</button>
            </div>

            <div class="stats-row" id="statsRow">
                <div class="stat-card">
                    <div class="label">Total Users</div>
                    <div class="value" id="statTotal">—</div>
                </div>
                <div class="stat-card">
                    <div class="label">Administrators</div>
                    <div class="value accent" id="statAdmins">—</div>
                </div>
                <div class="stat-card">
                    <div class="label">Normal Users</div>
                    <div class="value" id="statUsers">—</div>
                </div>
                <div class="stat-card">
                    <div class="label">Last 24h</div>
                    <div class="value" id="statRecent">—</div>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search by name or email..." oninput="debounceSearch()">
                </div>
                <div class="filter-group">
                    <button class="filter-btn active" data-filter="all" onclick="setFilter('all', this)">All</button>
                    <button class="filter-btn" data-filter="admin" onclick="setFilter('admin', this)">Admin</button>
                    <button class="filter-btn" data-filter="user" onclick="setFilter('user', this)">Normal User</button>
                </div>
            </div>

            <div class="table-container">
                <div id="tableBody">
                    <div class="loading"><i class="fa fa-spinner"></i> Loading users...</div>
                </div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalOverlay" onclick="closeModal(event)">
        <div class="modal" onclick="event.stopPropagation()">
            <div id="modalContent"></div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/admin/users';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let allUsers = [];
    let filteredUsers = [];
    let currentFilter = 'all';
    let currentPage = 1;
    let perPage = 15;
    let searchQuery = '';
    let searchTimer = null;

    async function loadUsers() {
        document.getElementById('tableBody').innerHTML = '<div class="loading"><i class="fa fa-spinner"></i> Loading users...</div>';
        try {
            const res = await fetch(API, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            const data = await res.json();
            allUsers = Array.isArray(data) ? data : (data.data || data.users || []);
            applyFilters();
            updateStats();
        } catch (e) {
            document.getElementById('tableBody').innerHTML = '<div class="empty-state"><i class="fa fa-exclamation-triangle"></i><h3>Failed to load users</h3><p>Please check the API endpoint or try again later.</p></div>';
        }
    }

    function updateStats() {
        const total = allUsers.length;
        const admins = allUsers.filter(u => u.role === 'admin' || u.root_admin === 1).length;
        const users = total - admins;
        const dayAgo = Date.now() - 86400000;
        const recent = allUsers.filter(u => new Date(u.created_at).getTime() > dayAgo).length;
        document.getElementById('statTotal').textContent = total;
        document.getElementById('statAdmins').textContent = admins;
        document.getElementById('statUsers').textContent = users;
        document.getElementById('statRecent').textContent = recent;
    }

    function setFilter(filter, el) {
        currentFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
        el.classList.add('active');
        currentPage = 1;
        applyFilters();
    }

    function debounceSearch() {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => {
            searchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            currentPage = 1;
            applyFilters();
        }, 250);
    }

    function applyFilters() {
        filteredUsers = allUsers.filter(u => {
            if (currentFilter === 'admin' && !(u.role === 'admin' || u.root_admin === 1)) return false;
            if (currentFilter === 'user' && (u.role === 'admin' || u.root_admin === 1)) return false;
            if (searchQuery) {
                const name = (u.name || u.username || '').toLowerCase();
                const email = (u.email || '').toLowerCase();
                if (!name.includes(searchQuery) && !email.includes(searchQuery)) return false;
            }
            return true;
        });
        renderTable();
    }

    function renderTable() {
        const container = document.getElementById('tableBody');
        if (filteredUsers.length === 0) {
            container.innerHTML = '<div class="empty-state"><i class="fa fa-users"></i><h3>No users found</h3><p>Try adjusting your search or filter criteria.</p></div>';
            return;
        }
        const totalPages = Math.ceil(filteredUsers.length / perPage);
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * perPage;
        const page = filteredUsers.slice(start, start + perPage);
        let html = '<table><thead><tr><th>User</th><th>Email</th><th>Role</th><th>Servers</th><th>Created</th><th style="text-align:right">Actions</th></tr></thead><tbody>';
        page.forEach(u => {
            const isAdmin = u.role === 'admin' || u.root_admin === 1;
            const name = u.name || u.username || 'Unknown';
            const initials = name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase();
            const created = u.created_at ? new Date(u.created_at).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' }) : '—';
            const serverCount = u.server_count ?? u.servers_count ?? '—';
            html += `<tr>
                <td><div class="user-cell"><div class="avatar ${isAdmin ? 'admin-avatar' : 'user-avatar'}">${initials}</div><span class="user-name">${escHtml(name)}</span></div></td>
                <td><span class="user-email">${escHtml(u.email || '—')}</span></td>
                <td><span class="role-badge ${isAdmin ? 'admin' : 'user'}">${isAdmin ? 'Admin' : 'User'}</span></td>
                <td>${escHtml(String(serverCount))}</td>
                <td style="color:#a1a1aa;font-size:12px;">${created}</td>
                <td><div class="actions-cell" style="justify-content:flex-end">
                    <button class="icon-btn" title="Edit" onclick="openEditModal(${u.id})"><i class="fa fa-pencil"></i></button>
                    <button class="icon-btn" title="Login As" onclick="loginAs(${u.id})"><i class="fa fa-sign-in"></i></button>
                    <button class="icon-btn danger" title="Delete" onclick="confirmDelete(${u.id}, '${escHtml(name)}')"><i class="fa fa-trash"></i></button>
                </div></td>
            </tr>`;
        });
        html += '</tbody></table>';
        if (totalPages > 1) {
            html += `<div class="pagination"><span class="pagination-info">Showing ${start + 1}–${Math.min(start + perPage, filteredUsers.length)} of ${filteredUsers.length}</span><div class="pagination-btns">`;
            html += `<button class="page-btn" ${currentPage === 1 ? 'disabled' : ''} onclick="goPage(${currentPage - 1})"><i class="fa fa-chevron-left"></i></button>`;
            for (let i = 1; i <= totalPages; i++) {
                if (totalPages > 7 && i > 2 && i < totalPages - 1 && Math.abs(i - currentPage) > 1) {
                    if (i === 3 || i === totalPages - 2) html += '<button class="page-btn" disabled>…</button>';
                    continue;
                }
                html += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goPage(${i})">${i}</button>`;
            }
            html += `<button class="page-btn" ${currentPage === totalPages ? 'disabled' : ''} onclick="goPage(${currentPage + 1})"><i class="fa fa-chevron-right"></i></button>`;
            html += '</div></div>';
        }
        container.innerHTML = html;
    }

    function goPage(p) { currentPage = p; renderTable(); }

    function escHtml(s) {
        const d = document.createElement('div');
        d.textContent = s;
        return d.innerHTML;
    }

    function openModal(html) {
        document.getElementById('modalContent').innerHTML = html;
        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal(e) {
        if (!e || e.target === document.getElementById('modalOverlay')) {
            document.getElementById('modalOverlay').classList.remove('active');
        }
    }

    function confirmDelete(id, name) {
        openModal(`
            <h3>Delete User</h3>
            <p>Are you sure you want to delete <strong>${escHtml(name)}</strong>? This action cannot be undone and will remove all associated data.</p>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn danger" onclick="deleteUser(${id})"><i class="fa fa-trash"></i> Delete</button>
            </div>
        `);
    }

    async function deleteUser(id) {
        try {
            const res = await fetch(`${API}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            if (res.ok) {
                closeModal();
                showToast('User deleted successfully', 'success');
                loadUsers();
            } else {
                const err = await res.json().catch(() => ({}));
                showToast(err.message || 'Failed to delete user', 'error');
            }
        } catch (e) { showToast('Network error', 'error'); }
    }

    async function loginAs(id) {
        try {
            const res = await fetch(`${API}/${id}/login`, {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            if (res.ok) {
                const data = await res.json().catch(() => ({}));
                showToast('Impersonating user...', 'success');
                if (data.redirect) window.location.href = data.redirect;
                else window.location.href = '/dashboard';
            } else {
                showToast('Failed to impersonate user', 'error');
            }
        } catch (e) { showToast('Network error', 'error'); }
    }

    function openCreateModal() {
        openModal(`
            <h3>Create New User</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="createName" placeholder="John Doe">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="createEmail" placeholder="john@example.com">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" id="createPassword" placeholder="Minimum 8 characters">
                </div>
                <div class="form-group">
                    <label>Role</label>
                    <select id="createRole">
                        <option value="user">Normal User</option>
                        <option value="admin">Administrator</option>
                    </select>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn primary" onclick="createUser()"><i class="fa fa-plus"></i> Create</button>
            </div>
        `);
    }

    async function createUser() {
        const name = document.getElementById('createName').value.trim();
        const email = document.getElementById('createEmail').value.trim();
        const password = document.getElementById('createPassword').value;
        const role = document.getElementById('createRole').value;
        if (!name || !email || !password) { showToast('Please fill in all fields', 'error'); return; }
        if (password.length < 8) { showToast('Password must be at least 8 characters', 'error'); return; }
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ name, email, password, role })
            });
            if (res.ok) {
                closeModal();
                showToast('User created successfully', 'success');
                loadUsers();
            } else {
                const err = await res.json().catch(() => ({}));
                showToast(err.message || 'Failed to create user', 'error');
            }
        } catch (e) { showToast('Network error', 'error'); }
    }

    function openEditModal(id) {
        const u = allUsers.find(x => x.id === id);
        if (!u) return;
        const isAdmin = u.role === 'admin' || u.root_admin === 1;
        openModal(`
            <h3>Edit User</h3>
            <div class="form-row">
                <div class="form-group">
                    <label>Name</label>
                    <input type="text" id="editName" value="${escHtml(u.name || u.username || '')}">
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" id="editEmail" value="${escHtml(u.email || '')}">
                </div>
            </div>
            <div class="form-group">
                <label>Role</label>
                <select id="editRole">
                    <option value="user" ${!isAdmin ? 'selected' : ''}>Normal User</option>
                    <option value="admin" ${isAdmin ? 'selected' : ''}>Administrator</option>
                </select>
            </div>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn primary" onclick="updateUser(${id})"><i class="fa fa-save"></i> Save</button>
            </div>
        `);
    }

    async function updateUser(id) {
        const name = document.getElementById('editName').value.trim();
        const email = document.getElementById('editEmail').value.trim();
        const role = document.getElementById('editRole').value;
        if (!name || !email) { showToast('Name and email are required', 'error'); return; }
        try {
            const res = await fetch(`${API}/${id}`, {
                method: 'PATCH',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ name, email, role })
            });
            if (res.ok) {
                closeModal();
                showToast('User updated successfully', 'success');
                loadUsers();
            } else {
                const err = await res.json().catch(() => ({}));
                showToast(err.message || 'Failed to update user', 'error');
            }
        } catch (e) { showToast('Network error', 'error'); }
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.addEventListener('keydown', e => { if (e.key === 'Escape') closeModal(); });
    loadUsers();
    </script>
</body>
</html>
