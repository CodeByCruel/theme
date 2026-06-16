<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Server Management — BSDK V1</title>
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
        .header-actions { display: flex; gap: 8px; align-items: center; }
        .toolbar { display: flex; gap: 12px; margin-bottom: 20px; align-items: center; flex-wrap: wrap; }
        .search-box { position: relative; flex: 1; max-width: 320px; }
        .search-box input { width: 100%; padding: 10px 14px 10px 38px; border: 1px solid rgba(255,255,255,0.08); background: #191919cc; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: 'Inter', system-ui, sans-serif; }
        .search-box input:focus { border-color: #df3050; }
        .search-box input::placeholder { color: #52525b; }
        .search-box i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #52525b; font-size: 14px; }
        .filter-select { padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #191919cc; color: #fff; border-radius: 8px; font-size: 13px; outline: none; cursor: pointer; font-family: 'Inter', system-ui, sans-serif; transition: border-color 0.2s; }
        .filter-select:focus { border-color: #df3050; }
        .filter-select option { background: #191919; color: #fff; }
        .table-wrapper { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; margin-bottom: 20px; }
        .data-table { width: 100%; border-collapse: collapse; }
        .data-table thead { background: rgba(255,255,255,0.03); }
        .data-table th { padding: 14px 16px; text-align: left; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #a1a1aa; border-bottom: 1px solid rgba(255,255,255,0.06); white-space: nowrap; }
        .data-table td { padding: 14px 16px; font-size: 13px; color: #e4e4e7; border-bottom: 1px solid rgba(255,255,255,0.04); }
        .data-table tbody tr { transition: background 0.15s; }
        .data-table tbody tr:hover { background: rgba(223, 48, 80, 0.04); }
        .data-table tbody tr:last-child td { border-bottom: none; }
        .status-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; margin-right: 8px; vertical-align: middle; }
        .status-dot.running { background: #22c55e; box-shadow: 0 0 6px rgba(34, 197, 94, 0.4); }
        .status-dot.stopped { background: #ef4444; box-shadow: 0 0 6px rgba(239, 68, 68, 0.4); }
        .status-dot.suspended { background: #f59e0b; box-shadow: 0 0 6px rgba(245, 158, 11, 0.4); }
        .status-dot.installing { background: #3b82f6; box-shadow: 0 0 6px rgba(59, 130, 246, 0.4); }
        .status-label { font-weight: 500; }
        .status-label.running { color: #22c55e; }
        .status-label.stopped { color: #ef4444; }
        .status-label.suspended { color: #f59e0b; }
        .status-label.installing { color: #3b82f6; }
        .resource-bar { display: flex; align-items: center; gap: 8px; }
        .resource-bar-track { flex: 1; height: 6px; background: #27272a; border-radius: 3px; overflow: hidden; max-width: 80px; }
        .resource-bar-fill { height: 100%; border-radius: 3px; transition: width 0.3s; }
        .resource-bar-fill.low { background: #22c55e; }
        .resource-bar-fill.medium { background: #f59e0b; }
        .resource-bar-fill.high { background: #ef4444; }
        .resource-text { font-size: 12px; color: #a1a1aa; min-width: 40px; }
        .owner-cell { display: flex; align-items: center; gap: 8px; }
        .owner-avatar { width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #df3050, #ff6b6b); display: flex; align-items: center; justify-content: center; font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0; }
        .actions-cell { display: flex; gap: 6px; }
        .action-btn { padding: 6px 10px; border: 1px solid rgba(255,255,255,0.08); background: transparent; color: #a1a1aa; border-radius: 6px; cursor: pointer; font-size: 12px; transition: all 0.2s; font-family: 'Inter', system-ui, sans-serif; display: inline-flex; align-items: center; gap: 4px; }
        .action-btn:hover { border-color: #df3050; color: #df3050; }
        .action-btn.danger:hover { border-color: #ef4444; color: #ef4444; background: rgba(239, 68, 68, 0.1); }
        .action-btn.warning:hover { border-color: #f59e0b; color: #f59e0b; background: rgba(245, 158, 11, 0.1); }
        .pagination { display: flex; align-items: center; justify-content: space-between; padding: 16px 0; }
        .pagination-info { font-size: 13px; color: #a1a1aa; }
        .pagination-controls { display: flex; gap: 4px; }
        .page-btn { width: 36px; height: 36px; border: 1px solid rgba(255,255,255,0.08); background: transparent; color: #a1a1aa; border-radius: 8px; cursor: pointer; font-size: 13px; transition: all 0.2s; display: flex; align-items: center; justify-content: center; font-family: 'Inter', system-ui, sans-serif; }
        .page-btn:hover { border-color: #df3050; color: #df3050; }
        .page-btn.active { background: #df3050; color: #fff; border-color: #df3050; }
        .page-btn:disabled { opacity: 0.3; cursor: not-allowed; }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; font-family: 'Inter', system-ui, sans-serif; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; }
        .btn.danger:hover { background: #991b1b; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.show { display: flex; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px; max-width: 440px; width: 90%; position: relative; }
        .modal-icon { width: 56px; height: 56px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 24px; margin: 0 auto 20px; }
        .modal-icon.danger { background: rgba(239, 68, 68, 0.1); color: #ef4444; }
        .modal-icon.warning { background: rgba(245, 158, 11, 0.1); color: #f59e0b; }
        .modal h3 { text-align: center; font-size: 18px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .modal p { text-align: center; font-size: 14px; color: #a1a1aa; margin-bottom: 24px; line-height: 1.5; }
        .modal-actions { display: flex; gap: 8px; justify-content: center; }
        .modal-actions .btn { min-width: 100px; justify-content: center; }
        .empty-state { text-align: center; padding: 60px 20px; color: #52525b; }
        .empty-state i { font-size: 48px; margin-bottom: 16px; color: #27272a; }
        .empty-state h3 { font-size: 16px; font-weight: 600; color: #a1a1aa; margin-bottom: 8px; }
        .empty-state p { font-size: 13px; }
        .loading-spinner { display: inline-block; width: 20px; height: 20px; border: 2px solid rgba(255,255,255,0.1); border-top-color: #df3050; border-radius: 50%; animation: spin 0.6s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; display: flex; align-items: center; gap: 8px; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ef4444; }
        .toast.warning { border-color: #f59e0b; }
        .server-name { font-weight: 600; color: #fff; }
        .node-badge { padding: 3px 8px; background: rgba(223, 48, 80, 0.1); color: #df3050; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .tag { display: inline-flex; align-items: center; gap: 4px; padding: 3px 8px; border-radius: 4px; font-size: 12px; font-weight: 500; }
        .tag.id { background: rgba(255,255,255,0.05); color: #a1a1aa; font-family: 'JetBrains Mono', monospace; font-size: 11px; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .data-table { font-size: 12px; }
            .data-table th, .data-table td { padding: 10px 8px; }
            .actions-cell { flex-direction: column; }
            .toolbar { flex-direction: column; }
            .search-box { max-width: 100%; }
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
                <a href="/admin/bsd-settings" class="nav-item"><i class="fa fa-magic"></i> BSD Settings</a>
                <a href="/admin/addon-settings" class="nav-item"><i class="fa fa-puzzle-piece"></i> Addon Settings</a>
                <a href="/admin/servers" class="nav-item active"><i class="fa fa-server"></i> Servers</a>
                <a href="/admin/users" class="nav-item"><i class="fa fa-users"></i> Users</a>
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
                    <h1><i class="fa fa-server"></i> Server Management</h1>
                    <p>Monitor, configure, and manage all game servers across your nodes</p>
                </div>
                <div class="header-actions">
                    <button class="btn secondary" onclick="loadServers()"><i class="fa fa-refresh"></i> Refresh</button>
                    <button class="btn primary" onclick="openCreateModal()"><i class="fa fa-plus"></i> Create Server</button>
                </div>
            </div>

            <div class="toolbar">
                <div class="search-box">
                    <i class="fa fa-search"></i>
                    <input type="text" id="searchInput" placeholder="Search servers by name, owner, or node..." oninput="filterServers()">
                </div>
                <select class="filter-select" id="statusFilter" onchange="filterServers()">
                    <option value="all">All Status</option>
                    <option value="running">Online</option>
                    <option value="stopped">Offline</option>
                    <option value="suspended">Suspended</option>
                    <option value="installing">Installing</option>
                </select>
                <select class="filter-select" id="perPageSelect" onchange="changePerPage()">
                    <option value="15">15 per page</option>
                    <option value="25">25 per page</option>
                    <option value="50">50 per page</option>
                    <option value="100">100 per page</option>
                </select>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Node</th>
                            <th>Owner</th>
                            <th>Status</th>
                            <th>CPU</th>
                            <th>Memory</th>
                            <th>Disk</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="serverTableBody">
                        <tr>
                            <td colspan="8">
                                <div class="empty-state">
                                    <div class="loading-spinner"></div>
                                    <h3 style="margin-top:12px;">Loading servers...</h3>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="pagination" id="pagination"></div>
        </main>
    </div>

    <div class="modal-overlay" id="confirmModal">
        <div class="modal">
            <div class="modal-icon danger" id="modalIcon">
                <i class="fa" id="modalIconEl"></i>
            </div>
            <h3 id="modalTitle">Confirm Action</h3>
            <p id="modalMessage">Are you sure you want to proceed?</p>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn danger" id="modalConfirmBtn" onclick="executeModalAction()">Confirm</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/admin/servers';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let allServers = [];
    let filteredServers = [];
    let currentPage = 1;
    let perPage = 15;
    let totalPages = 1;
    let modalAction = null;
    let modalTarget = null;

    async function loadServers() {
        const tbody = document.getElementById('serverTableBody');
        tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><div class="loading-spinner"></div><h3 style="margin-top:12px;">Loading servers...</h3></div></td></tr>';
        try {
            const res = await fetch(API, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Failed to load');
            const data = await res.json();
            allServers = Array.isArray(data) ? data : (data.data || data.servers || []);
        } catch (e) {
            allServers = [];
            showToast('Failed to load servers', 'error');
        }
        filteredServers = [...allServers];
        currentPage = 1;
        renderTable();
    }

    function filterServers() {
        const search = document.getElementById('searchInput').value.toLowerCase().trim();
        const status = document.getElementById('statusFilter').value;
        filteredServers = allServers.filter(s => {
            const matchSearch = !search ||
                (s.name || '').toLowerCase().includes(search) ||
                (s.username || s.owner_name || '').toLowerCase().includes(search) ||
                (s.node_name || s.node || '').toLowerCase().includes(search);
            const matchStatus = status === 'all' || s.status === status;
            return matchSearch && matchStatus;
        });
        currentPage = 1;
        renderTable();
    }

    function changePerPage() {
        perPage = parseInt(document.getElementById('perPageSelect').value);
        currentPage = 1;
        renderTable();
    }

    function renderTable() {
        const tbody = document.getElementById('serverTableBody');
        totalPages = Math.max(1, Math.ceil(filteredServers.length / perPage));
        if (currentPage > totalPages) currentPage = totalPages;
        const start = (currentPage - 1) * perPage;
        const pageServers = filteredServers.slice(start, start + perPage);

        if (pageServers.length === 0) {
            tbody.innerHTML = '<tr><td colspan="8"><div class="empty-state"><i class="fa fa-server"></i><h3>No servers found</h3><p>Try adjusting your search or filter criteria</p></div></td></tr>';
            renderPagination();
            return;
        }

        tbody.innerHTML = pageServers.map(s => {
            const status = s.status || 'stopped';
            const cpu = parseFloat(s.cpu || 0);
            const memory = parseFloat(s.memory || 0);
            const disk = parseFloat(s.disk || 0);
            const ownerName = s.username || s.owner_name || 'Unknown';
            const ownerInitials = ownerName.split(' ').map(w => w[0]).join('').toUpperCase().slice(0, 2);
            const nodeName = s.node_name || s.node || 'N/A';
            const cpuClass = cpu > 80 ? 'high' : cpu > 50 ? 'medium' : 'low';
            const memClass = memory > 80 ? 'high' : memory > 50 ? 'medium' : 'low';
            const diskClass = disk > 80 ? 'high' : disk > 50 ? 'medium' : 'low';

            return `<tr data-id="${s.id}">
                <td>
                    <div class="server-name">${escapeHtml(s.name || 'Unnamed')}</div>
                    <span class="tag id">#${escapeHtml(String(s.id))}</span>
                </td>
                <td><span class="node-badge">${escapeHtml(nodeName)}</span></td>
                <td>
                    <div class="owner-cell">
                        <div class="owner-avatar">${ownerInitials}</div>
                        <span>${escapeHtml(ownerName)}</span>
                    </div>
                </td>
                <td>
                    <span class="status-dot ${status}"></span>
                    <span class="status-label ${status}">${capitalize(status)}</span>
                </td>
                <td>
                    <div class="resource-bar">
                        <div class="resource-bar-track"><div class="resource-bar-fill ${cpuClass}" style="width:${Math.min(cpu, 100)}%"></div></div>
                        <span class="resource-text">${cpu.toFixed(1)}%</span>
                    </div>
                </td>
                <td>
                    <div class="resource-bar">
                        <div class="resource-bar-track"><div class="resource-bar-fill ${memClass}" style="width:${Math.min(memory, 100)}%"></div></div>
                        <span class="resource-text">${memory.toFixed(1)}%</span>
                    </div>
                </td>
                <td>
                    <div class="resource-bar">
                        <div class="resource-bar-track"><div class="resource-bar-fill ${diskClass}" style="width:${Math.min(disk, 100)}%"></div></div>
                        <span class="resource-text">${disk.toFixed(1)}%</span>
                    </div>
                </td>
                <td>
                    <div class="actions-cell">
                        <button class="action-btn" onclick="viewServer(${s.id})" title="View Details"><i class="fa fa-eye"></i> View</button>
                        ${status === 'suspended'
                            ? `<button class="action-btn" onclick="unsuspendServer(${s.id}, '${escapeHtml(s.name)}')" title="Unsuspend Server"><i class="fa fa-play"></i></button>`
                            : `<button class="action-btn warning" onclick="openConfirm('suspend', ${s.id}, '${escapeHtml(s.name)}')" title="Suspend Server"><i class="fa fa-pause"></i></button>`
                        }
                        <button class="action-btn danger" onclick="openConfirm('delete', ${s.id}, '${escapeHtml(s.name)}')" title="Delete Server"><i class="fa fa-trash"></i></button>
                    </div>
                </td>
            </tr>`;
        }).join('');

        renderPagination();
    }

    function renderPagination() {
        const container = document.getElementById('pagination');
        const total = filteredServers.length;
        const start = (currentPage - 1) * perPage + 1;
        const end = Math.min(currentPage * perPage, total);

        let pagesHtml = '';
        const maxVisible = 5;
        let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
        let endPage = Math.min(totalPages, startPage + maxVisible - 1);
        if (endPage - startPage < maxVisible - 1) {
            startPage = Math.max(1, endPage - maxVisible + 1);
        }

        pagesHtml += `<button class="page-btn" onclick="goToPage(${currentPage - 1})" ${currentPage <= 1 ? 'disabled' : ''}><i class="fa fa-chevron-left"></i></button>`;
        for (let i = startPage; i <= endPage; i++) {
            pagesHtml += `<button class="page-btn ${i === currentPage ? 'active' : ''}" onclick="goToPage(${i})">${i}</button>`;
        }
        pagesHtml += `<button class="page-btn" onclick="goToPage(${currentPage + 1})" ${currentPage >= totalPages ? 'disabled' : ''}><i class="fa fa-chevron-right"></i></button>`;

        container.innerHTML = `
            <div class="pagination-info">Showing ${total > 0 ? start : 0}–${end} of ${total} servers</div>
            <div class="pagination-controls">${pagesHtml}</div>
        `;
    }

    function goToPage(page) {
        if (page < 1 || page > totalPages) return;
        currentPage = page;
        renderTable();
        document.querySelector('.table-wrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

    function viewServer(id) {
        window.location.href = `/admin/servers/${id}`;
    }

    function unsuspendServer(id, name) {
        openConfirm('unsuspend', id, name);
    }

    function openConfirm(action, id, name) {
        modalAction = action;
        modalTarget = { id, name };
        const modal = document.getElementById('confirmModal');
        const icon = document.getElementById('modalIcon');
        const iconEl = document.getElementById('modalIconEl');
        const title = document.getElementById('modalTitle');
        const message = document.getElementById('modalMessage');
        const confirmBtn = document.getElementById('modalConfirmBtn');

        if (action === 'suspend') {
            icon.className = 'modal-icon warning';
            iconEl.className = 'fa fa-pause';
            title.textContent = 'Suspend Server';
            message.textContent = `Are you sure you want to suspend "${name}"? The server will be stopped and the owner will lose access until unsuspended.`;
            confirmBtn.className = 'btn';
            confirmBtn.style.background = '#92400e';
            confirmBtn.style.color = '#fbbf24';
            confirmBtn.textContent = 'Suspend';
        } else if (action === 'unsuspend') {
            icon.className = 'modal-icon warning';
            iconEl.className = 'fa fa-play';
            title.textContent = 'Unsuspend Server';
            message.textContent = `Are you sure you want to unsuspend "${name}"? The server will be started and the owner will regain access.`;
            confirmBtn.className = 'btn';
            confirmBtn.style.background = '#065f46';
            confirmBtn.style.color = '#34d399';
            confirmBtn.textContent = 'Unsuspend';
        } else if (action === 'delete') {
            icon.className = 'modal-icon danger';
            iconEl.className = 'fa fa-trash';
            title.textContent = 'Delete Server';
            message.textContent = `Are you sure you want to permanently delete "${name}"? This action cannot be undone. All data will be lost.`;
            confirmBtn.className = 'btn danger';
            confirmBtn.style.background = '';
            confirmBtn.style.color = '';
            confirmBtn.textContent = 'Delete';
        }
        modal.classList.add('show');
    }

    function closeModal() {
        document.getElementById('confirmModal').classList.remove('show');
        modalAction = null;
        modalTarget = null;
    }

    async function executeModalAction() {
        if (!modalAction || !modalTarget) return;
        const { id, name } = modalTarget;
        const action = modalAction;
        closeModal();

        let endpoint = '';
        let method = 'POST';
        if (action === 'suspend') endpoint = `${API}/${id}/suspend`;
        else if (action === 'unsuspend') endpoint = `${API}/${id}/unsuspend`;
        else if (action === 'delete') endpoint = `${API}/${id}`;

        try {
            const opts = {
                method,
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': TOKEN
                }
            };
            if (action === 'delete') {
                opts.method = 'DELETE';
            }
            const res = await fetch(endpoint, opts);
            if (res.ok) {
                showToast(`Server "${name}" ${action === 'delete' ? 'deleted' : action + 'd'} successfully`, 'success');
                loadServers();
            } else {
                const data = await res.json().catch(() => ({}));
                showToast(data.message || `Failed to ${action} server`, 'error');
            }
        } catch (e) {
            showToast(`Failed to ${action} server`, 'error');
        }
    }

    function openCreateModal() {
        window.location.href = '/admin/servers/create';
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        const icons = { success: 'check-circle', error: 'times-circle', warning: 'exclamation-circle' };
        t.innerHTML = `<i class="fa fa-${icons[type] || 'info-circle'}"></i> ${msg}`;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('confirmModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeModal();
    });

    loadServers();
    </script>
</body>
</html>
