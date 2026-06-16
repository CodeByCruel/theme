<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSH Keys — BSDK V1</title>
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
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; border: 1px solid rgba(255,0,0,0.2); }
        .btn.danger:hover { background: #991b1b; }
        .ssh-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(340px, 1fr)); gap: 16px; }
        .ssh-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .ssh-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #df3050, #ff6b6b); opacity: 0; transition: opacity 0.3s; }
        .ssh-card:hover { border-color: rgba(223, 48, 80, 0.3); }
        .ssh-card:hover::before { opacity: 1; }
        .ssh-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .ssh-card-name { display: flex; align-items: center; gap: 10px; }
        .ssh-card-icon { width: 40px; height: 40px; border-radius: 10px; background: rgba(223, 48, 80, 0.1); display: flex; align-items: center; justify-content: center; font-size: 16px; color: #df3050; }
        .ssh-card-name h3 { font-size: 15px; font-weight: 600; color: #fff; }
        .ssh-card-details { display: flex; flex-direction: column; gap: 10px; margin-bottom: 16px; }
        .ssh-detail { display: flex; align-items: center; gap: 8px; font-size: 13px; }
        .ssh-detail-label { color: #a1a1aa; min-width: 80px; }
        .ssh-detail-value { color: #fafafa; font-family: 'JetBrains Mono', monospace; word-break: break-all; }
        .ssh-card-actions { display: flex; gap: 8px; }
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; color: #fafafa; margin-bottom: 8px; }
        .empty-state p { color: #a1a1aa; font-size: 14px; margin-bottom: 24px; }
        .modal-overlay { position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 500; display: none; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px; width: 100%; max-width: 520px; position: relative; }
        .modal h2 { font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 4px; }
        .modal h2 i { margin-right: 8px; color: #df3050; }
        .modal .modal-desc { color: #a1a1aa; font-size: 13px; margin-bottom: 24px; }
        .modal-close { position: absolute; top: 16px; right: 16px; width: 32px; height: 32px; border-radius: 8px; border: 1px solid rgba(255,255,255,0.08); background: transparent; color: #a1a1aa; cursor: pointer; display: flex; align-items: center; justify-content: center; transition: all 0.2s; }
        .modal-close:hover { border-color: #df3050; color: #df3050; }
        .field-group { margin-bottom: 16px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .field-group input[type="text"], .field-group textarea {
            width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: inherit;
        }
        .field-group textarea { font-family: 'JetBrains Mono', monospace; resize: vertical; min-height: 120px; }
        .field-group input:focus, .field-group textarea:focus { border-color: #df3050; }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 24px; }
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
            .ssh-grid { grid-template-columns: 1fr; }
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
                <a href="/account/ssh" class="nav-item active"><i class="fa fa-terminal"></i> SSH Keys</a>
                <a href="/account/activity" class="nav-item"><i class="fa fa-clock-o"></i> Activity Log</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fa fa-terminal"></i> SSH Keys</h1>
                    <p>Manage your SSH public keys for server access</p>
                </div>
                <button class="btn primary" onclick="openModal()"><i class="fa fa-plus"></i> Add SSH Key</button>
            </div>

            <div id="sshGrid" class="ssh-grid"></div>

            <div id="emptyState" class="empty-state" style="display:none;">
                <i class="fa fa-key"></i>
                <h3>No SSH Keys</h3>
                <p>You haven't added any SSH keys yet. Add one to access your servers.</p>
                <button class="btn primary" onclick="openModal()"><i class="fa fa-plus"></i> Add SSH Key</button>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modal">
        <div class="modal">
            <button class="modal-close" onclick="closeModal()"><i class="fa fa-times"></i></button>
            <h2><i class="fa fa-plus"></i> Add SSH Key</h2>
            <p class="modal-desc">Paste your public SSH key below to add it to your account.</p>
            <div class="field-group">
                <label>Key Name</label>
                <input type="text" id="keyName" placeholder="e.g. My Laptop">
            </div>
            <div class="field-group">
                <label>Public Key</label>
                <textarea id="keyPublic" placeholder="ssh-rsa AAAA... user@host"></textarea>
            </div>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn primary" id="submitBtn" onclick="addKey()"><i class="fa fa-plus"></i> Add Key</button>
            </div>
        </div>
    </div>

    <div class="footer-bar">
        <div class="save-status"><i class="fa fa-key"></i> <span id="keyCount">0</span> SSH keys registered</div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/client/ssh-keys';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let keys = [];

    async function loadKeys() {
        document.getElementById('sshGrid').innerHTML = '<div style="grid-column:1/-1;text-align:center;padding:40px;"><div class="loading-spinner"></div></div>';
        document.getElementById('emptyState').style.display = 'none';
        try {
            const res = await fetch(API, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            if (!res.ok) throw new Error();
            const data = await res.json();
            keys = data.data || data || [];
        } catch(e) { keys = []; showToast('Failed to load SSH keys', 'error'); }
        renderKeys();
    }

    function renderKeys() {
        const grid = document.getElementById('sshGrid');
        const empty = document.getElementById('emptyState');
        grid.innerHTML = '';
        document.getElementById('keyCount').textContent = keys.length;
        if (keys.length === 0) { empty.style.display = ''; return; }
        empty.style.display = 'none';
        keys.forEach(key => {
            const created = new Date(key.created_at || key.createdAt).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
            const fingerprint = key.fingerprint || key.fingerprint_sha256 || '—';
            const keyType = key.key_type || key.type || inferType(key.public_key || key.publicKey);
            const name = escapeHtml(key.name || 'Unnamed Key');
            const card = document.createElement('div');
            card.className = 'ssh-card';
            card.innerHTML = `
                <div class="ssh-card-header">
                    <div class="ssh-card-name">
                        <div class="ssh-card-icon"><i class="fa fa-key"></i></div>
                        <h3>${name}</h3>
                    </div>
                </div>
                <div class="ssh-card-details">
                    <div class="ssh-detail">
                        <span class="ssh-detail-label">Fingerprint</span>
                        <span class="ssh-detail-value">${escapeHtml(fingerprint)}</span>
                    </div>
                    <div class="ssh-detail">
                        <span class="ssh-detail-label">Key Type</span>
                        <span class="ssh-detail-value">${escapeHtml(keyType)}</span>
                    </div>
                    <div class="ssh-detail">
                        <span class="ssh-detail-label">Created</span>
                        <span class="ssh-detail-value">${created}</span>
                    </div>
                </div>
                <div class="ssh-card-actions">
                    <button class="btn danger" onclick="deleteKey('${key.id || key.identifier}', this)"><i class="fa fa-trash"></i> Delete</button>
                </div>
            `;
            grid.appendChild(card);
        });
    }

    function inferType(pub) {
        if (!pub) return 'Unknown';
        if (pub.startsWith('ssh-rsa')) return 'RSA';
        if (pub.startsWith('ssh-ed25519')) return 'Ed25519';
        if (pub.startsWith('ecdsa-sha2')) return 'ECDSA';
        if (pub.startsWith('ssh-dss')) return 'DSA';
        return 'Unknown';
    }

    function escapeHtml(str) {
        const d = document.createElement('div'); d.textContent = str; return d.innerHTML;
    }

    function openModal() {
        document.getElementById('modal').classList.add('active');
        document.getElementById('keyName').value = '';
        document.getElementById('keyPublic').value = '';
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('active');
    }

    async function addKey() {
        const name = document.getElementById('keyName').value.trim();
        const publicKey = document.getElementById('keyPublic').value.trim();
        if (!name) { showToast('Please enter a key name', 'error'); return; }
        if (!publicKey) { showToast('Please paste your public key', 'error'); return; }
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = '<span class="loading-spinner"></span> Adding...';
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ name, public_key: publicKey })
            });
            if (res.ok) {
                showToast('SSH key added successfully', 'success');
                closeModal();
                await loadKeys();
            } else {
                const err = await res.json().catch(() => ({}));
                showToast(err.message || 'Failed to add SSH key', 'error');
            }
        } catch(e) { showToast('Failed to add SSH key', 'error'); }
        btn.disabled = false;
        btn.innerHTML = '<i class="fa fa-plus"></i> Add Key';
    }

    async function deleteKey(id, el) {
        if (!confirm('Are you sure you want to delete this SSH key?')) return;
        const card = el.closest('.ssh-card');
        el.disabled = true;
        el.innerHTML = '<span class="loading-spinner"></span>';
        try {
            const res = await fetch(`${API}/${id}`, {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            if (res.ok) {
                card.style.opacity = '0';
                card.style.transform = 'scale(0.95)';
                card.style.transition = 'all 0.3s';
                setTimeout(() => loadKeys(), 300);
                showToast('SSH key deleted', 'success');
            } else {
                showToast('Failed to delete SSH key', 'error');
                el.disabled = false;
                el.innerHTML = '<i class="fa fa-trash"></i> Delete';
            }
        } catch(e) { showToast('Failed to delete SSH key', 'error'); el.disabled = false; el.innerHTML = '<i class="fa fa-trash"></i> Delete'; }
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('modal').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });

    loadKeys();
    </script>
</body>
</html>