<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Node Management — BSDK V1</title>
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
        .page-header { margin-bottom: 32px; display: flex; align-items: center; justify-content: space-between; }
        .page-header h1 { font-size: 24px; font-weight: 700; color: #fff; }
        .page-header h1 i { margin-right: 8px; color: #df3050; }
        .page-header p { color: #a1a1aa; font-size: 14px; margin-top: 4px; }
        .nodes-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; margin-bottom: 40px; }
        .node-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .node-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #df3050, #ff6b6b); opacity: 0; transition: opacity 0.3s; }
        .node-card:hover { transform: translateY(-2px); border-color: #df3050; }
        .node-card:hover::before { opacity: 1; }
        .node-card-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 16px; }
        .node-card-title { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 4px; }
        .node-card-subtitle { font-size: 12px; color: #a1a1aa; }
        .node-status { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .node-status.online { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .node-status.offline { background: rgba(255, 0, 0, 0.15); color: #ff0000; }
        .node-status .dot { width: 6px; height: 6px; border-radius: 50%; }
        .node-status.online .dot { background: #22c55e; }
        .node-status.offline .dot { background: #ff0000; }
        .node-meta { display: flex; gap: 16px; margin-bottom: 16px; flex-wrap: wrap; }
        .node-meta-item { font-size: 12px; color: #a1a1aa; display: flex; align-items: center; gap: 4px; }
        .node-meta-item i { color: #df3050; width: 14px; text-align: center; }
        .node-resources { display: flex; flex-direction: column; gap: 12px; margin-bottom: 16px; }
        .resource-row { display: flex; align-items: center; gap: 10px; }
        .resource-label { font-size: 11px; font-weight: 600; color: #a1a1aa; text-transform: uppercase; letter-spacing: 0.5px; min-width: 48px; }
        .resource-bar { flex: 1; height: 6px; background: #292524; border-radius: 3px; overflow: hidden; }
        .resource-bar-fill { height: 100%; border-radius: 3px; transition: width 0.6s ease; }
        .resource-bar-fill.low { background: #22c55e; }
        .resource-bar-fill.medium { background: #f59e0b; }
        .resource-bar-fill.high { background: #ff0000; }
        .resource-pct { font-size: 11px; font-weight: 600; color: #fafafa; min-width: 36px; text-align: right; font-family: 'JetBrains Mono', monospace; }
        .node-footer { display: flex; align-items: center; justify-content: space-between; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.08); }
        .node-servers { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; background: rgba(223, 48, 80, 0.1); border-radius: 6px; font-size: 12px; font-weight: 600; color: #df3050; }
        .node-servers i { font-size: 11px; }
        .node-actions { display: flex; gap: 6px; }
        .node-actions .btn-icon { width: 32px; height: 32px; border: 1px solid rgba(255,255,255,0.08); background: #1c19177a; color: #a1a1aa; border-radius: 8px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 13px; transition: all 0.2s; }
        .node-actions .btn-icon:hover { border-color: #df3050; color: #df3050; }
        .node-actions .btn-icon.danger:hover { border-color: #ff0000; color: #ff0000; background: rgba(255,0,0,0.1); }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; }
        .btn.danger:hover { background: #991b1b; }
        .empty-state { text-align: center; padding: 80px 24px; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; color: #a1a1aa; margin-bottom: 24px; }
        .loading-spinner { display: flex; align-items: center; justify-content: center; padding: 80px 24px; }
        .loading-spinner .spinner { width: 32px; height: 32px; border: 3px solid #292524; border-top-color: #df3050; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; max-width: 420px; width: 90%; animation: modalIn 0.2s ease; }
        @keyframes modalIn { from { opacity: 0; transform: scale(0.95); } to { opacity: 1; transform: scale(1); } }
        .modal h3 { font-size: 16px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .modal p { font-size: 13px; color: #a1a1aa; margin-bottom: 20px; line-height: 1.5; }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .nodes-grid { grid-template-columns: 1fr; }
            .page-header { flex-direction: column; align-items: flex-start; gap: 12px; }
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
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Infrastructure</div>
                <a href="/admin/servers" class="nav-item"><i class="fa fa-server"></i> Servers</a>
                <a href="/admin/users" class="nav-item"><i class="fa fa-users"></i> Users</a>
                <a href="/admin/nodes" class="nav-item active"><i class="fa fa-sitemap"></i> Nodes</a>
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
                    <h1><i class="fa fa-sitemap"></i> Node Management</h1>
                    <p>Monitor and manage your infrastructure nodes</p>
                </div>
                <button class="btn primary" onclick="showCreateModal()"><i class="fa fa-plus"></i> Create Node</button>
            </div>

            <div id="nodesContainer">
                <div class="loading-spinner"><div class="spinner"></div></div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal" id="modal">
            <h3 id="modalTitle">Confirm</h3>
            <p id="modalBody">Are you sure?</p>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn danger" id="modalConfirmBtn">Confirm</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/admin/nodes';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let nodes = [];

    async function loadNodes() {
        const container = document.getElementById('nodesContainer');
        try {
            const res = await fetch(API, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Failed to fetch');
            const data = await res.json();
            nodes = data.data || data.nodes || data || [];
            if (!Array.isArray(nodes)) nodes = [];
        } catch (e) {
            nodes = [];
            showToast('Failed to load nodes', 'error');
        }
        renderNodes();
    }

    function renderNodes() {
        const container = document.getElementById('nodesContainer');
        if (nodes.length === 0) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fa fa-sitemap"></i>
                    <h3>No Nodes Found</h3>
                    <p>Create your first node to start managing servers.</p>
                    <button class="btn primary" onclick="showCreateModal()"><i class="fa fa-plus"></i> Create Node</button>
                </div>`;
            return;
        }

        let html = '<div class="nodes-grid">';
        nodes.forEach(node => {
            const cpu = Number(node.cpu || node.cpu_usage || node.cpu_percent || 0);
            const memory = Number(node.memory || node.memory_usage || node.memory_percent || 0);
            const disk = Number(node.disk || node.disk_usage || node.disk_percent || 0);
            const online = node.online !== undefined ? node.online : (node.status === 'online' || node.status === true);
            const serverCount = node.server_count ?? node.servers_count ?? node.servers ?? 0;

            html += `
                <div class="node-card" data-id="${node.id}">
                    <div class="node-card-header">
                        <div>
                            <div class="node-card-title">${esc(node.name || 'Unknown Node')}</div>
                            <div class="node-card-subtitle">${esc(node.fqdn || node.host || '')}</div>
                        </div>
                        <div class="node-status ${online ? 'online' : 'offline'}">
                            <span class="dot"></span>${online ? 'Online' : 'Offline'}
                        </div>
                    </div>
                    <div class="node-meta">
                        <div class="node-meta-item"><i class="fa fa-map-marker"></i> ${esc(node.location || node.datacenter || 'Unknown')}</div>
                        <div class="node-meta-item"><i class="fa fa-globe"></i> ${esc(node.fqdn || node.host || 'N/A')}</div>
                    </div>
                    <div class="node-resources">
                        <div class="resource-row">
                            <span class="resource-label">CPU</span>
                            <div class="resource-bar"><div class="resource-bar-fill ${resourceColor(cpu)}" style="width:${cpu}%"></div></div>
                            <span class="resource-pct">${cpu}%</span>
                        </div>
                        <div class="resource-row">
                            <span class="resource-label">MEM</span>
                            <div class="resource-bar"><div class="resource-bar-fill ${resourceColor(memory)}" style="width:${memory}%"></div></div>
                            <span class="resource-pct">${memory}%</span>
                        </div>
                        <div class="resource-row">
                            <span class="resource-label">DISK</span>
                            <div class="resource-bar"><div class="resource-bar-fill ${resourceColor(disk)}" style="width:${disk}%"></div></div>
                            <span class="resource-pct">${disk}%</span>
                        </div>
                    </div>
                    <div class="node-footer">
                        <div class="node-servers"><i class="fa fa-server"></i> ${serverCount} server${serverCount !== 1 ? 's' : ''}</div>
                        <div class="node-actions">
                            <button class="btn-icon" title="View" onclick="viewNode(${node.id})"><i class="fa fa-eye"></i></button>
                            <button class="btn-icon" title="Configure" onclick="configureNode(${node.id})"><i class="fa fa-cog"></i></button>
                            <button class="btn-icon danger" title="Delete" onclick="confirmDelete(${node.id}, '${esc(node.name || 'Node')}')"><i class="fa fa-trash"></i></button>
                        </div>
                    </div>
                </div>`;
        });
        html += '</div>';
        container.innerHTML = html;
    }

    function resourceColor(pct) {
        if (pct >= 90) return 'high';
        if (pct >= 60) return 'medium';
        return 'low';
    }

    function esc(str) {
        const d = document.createElement('div');
        d.textContent = str;
        return d.innerHTML;
    }

    function viewNode(id) {
        window.location.href = '/admin/nodes/' + id;
    }

    function configureNode(id) {
        window.location.href = '/admin/nodes/' + id + '/edit';
    }

    function confirmDelete(id, name) {
        showModal(
            'Delete Node',
            `Are you sure you want to delete <strong>${esc(name)}</strong>? This action cannot be undone and will remove all associated servers.`,
            () => deleteNode(id)
        );
    }

    async function deleteNode(id) {
        try {
            const res = await fetch(`${API}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': TOKEN
                }
            });
            if (res.ok) {
                showToast('Node deleted successfully', 'success');
                loadNodes();
            } else {
                showToast('Failed to delete node', 'error');
            }
        } catch (e) {
            showToast('Network error while deleting node', 'error');
        }
    }

    function showCreateModal() {
        showModal(
            'Create Node',
            `You will be redirected to the node creation page.`,
            () => { window.location.href = '/admin/nodes/create'; },
            'Continue'
        );
    }

    function showModal(title, body, onConfirm, confirmText) {
        document.getElementById('modalTitle').textContent = title;
        document.getElementById('modalBody').innerHTML = body;
        const btn = document.getElementById('modalConfirmBtn');
        btn.textContent = confirmText || 'Confirm';
        btn.onclick = () => { closeModal(); onConfirm(); };
        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
    }

    document.getElementById('modalOverlay').addEventListener('click', e => {
        if (e.target === e.currentTarget) closeModal();
    });

    document.addEventListener('keydown', e => {
        if (e.key === 'Escape') closeModal();
    });

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    loadNodes();
    </script>
</body>
</html>
