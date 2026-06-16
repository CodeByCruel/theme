<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API Credentials — BSDK V1</title>
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
        .page-header-row { display: flex; align-items: center; justify-content: space-between; margin-bottom: 32px; }
        .key-list { display: flex; flex-direction: column; gap: 16px; margin-bottom: 40px; }
        .key-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 24px; transition: all 0.3s ease; position: relative; overflow: hidden; }
        .key-card::before { content: ''; position: absolute; top: 0; left: 0; right: 0; height: 3px; background: linear-gradient(90deg, #df3050, #ff6b6b); opacity: 0; transition: opacity 0.3s; }
        .key-card:hover { transform: translateY(-2px); border-color: #df3050; }
        .key-card:hover::before { opacity: 1; }
        .key-card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .key-card-header h3 { font-size: 16px; font-weight: 600; color: #fff; display: flex; align-items: center; gap: 8px; }
        .key-card-header h3 i { color: #df3050; }
        .key-card-body { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 16px; }
        .key-field { display: flex; flex-direction: column; gap: 4px; }
        .key-field-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #a1a1aa; }
        .key-field-value { font-size: 13px; color: #fafafa; font-family: 'JetBrains Mono', monospace; word-break: break-all; }
        .key-field-value.muted { color: #a1a1aa; font-family: 'Inter', sans-serif; }
        .key-card-actions { display: flex; gap: 8px; margin-top: 16px; padding-top: 16px; border-top: 1px solid rgba(255,255,255,0.08); }
        .btn { padding: 8px 16px; border: none; border-radius: 8px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 6px; transition: all 0.2s; }
        .btn.primary { background: #df3050; color: #fff; }
        .btn.primary:hover { background: #e44b63; }
        .btn.secondary { background: #1c19177a; color: #a1a1aa; border: 1px solid rgba(255,255,255,0.08); }
        .btn.secondary:hover { border-color: #df3050; color: #df3050; }
        .btn.danger { background: #7f1d1d; color: #ff0000; }
        .btn.danger:hover { background: #991b1b; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a1a1aa; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; }
        .empty-state h3 { font-size: 18px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; margin-bottom: 24px; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.7); z-index: 200; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; padding: 32px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; }
        .modal h2 { font-size: 20px; font-weight: 700; color: #fff; margin-bottom: 8px; }
        .modal h2 i { margin-right: 8px; color: #df3050; }
        .modal p { color: #a1a1aa; font-size: 14px; margin-bottom: 24px; }
        .modal-close { position: absolute; top: 16px; right: 16px; background: none; border: none; color: #a1a1aa; font-size: 18px; cursor: pointer; transition: color 0.2s; }
        .modal-close:hover { color: #df3050; }
        .field-group { margin-bottom: 20px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .field-group input[type="text"], .field-group textarea {
            width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;
        }
        .field-group input:focus, .field-group textarea:focus { border-color: #df3050; }
        .field-group .hint { font-size: 11px; color: #71717a; margin-top: 6px; }
        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 12px 14px; background: #0c0a09; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; }
        .toggle-row span { font-size: 13px; color: #fff; font-weight: 500; }
        .toggle-btn { position: relative; width: 48px; height: 26px; border: none; background: #292524; border-radius: 13px; cursor: pointer; transition: background 0.3s; flex-shrink: 0; }
        .toggle-btn.active { background: #df3050; }
        .toggle-slider { position: absolute; top: 3px; left: 3px; width: 20px; height: 20px; border-radius: 50%; background: #fff; transition: transform 0.3s; }
        .toggle-btn.active .toggle-slider { transform: translateX(22px); }
        .modal-actions { display: flex; gap: 8px; justify-content: flex-end; margin-top: 28px; }
        .key-reveal { background: #0c0a09; border: 1px solid #22c55e; border-radius: 8px; padding: 16px; margin-bottom: 20px; position: relative; }
        .key-reveal-label { font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 1px; color: #22c55e; margin-bottom: 8px; display: flex; align-items: center; gap: 6px; }
        .key-reveal code { display: block; font-family: 'JetBrains Mono', monospace; font-size: 13px; color: #fff; word-break: break-all; line-height: 1.6; }
        .key-reveal .copy-btn { position: absolute; top: 12px; right: 12px; background: #191919; border: 1px solid rgba(255,255,255,0.08); color: #a1a1aa; border-radius: 6px; padding: 6px 12px; font-size: 12px; cursor: pointer; transition: all 0.2s; display: flex; align-items: center; gap: 4px; }
        .key-reveal .copy-btn:hover { border-color: #22c55e; color: #22c55e; }
        .key-reveal .copy-btn.copied { border-color: #22c55e; color: #22c55e; }
        .key-reveal-warning { background: rgba(245, 158, 11, 0.1); border: 1px solid rgba(245, 158, 11, 0.3); border-radius: 8px; padding: 12px 16px; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
        .key-reveal-warning i { color: #f59e0b; font-size: 16px; }
        .key-reveal-warning span { font-size: 13px; color: #fbbf24; }
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 300; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        .footer-bar { position: fixed; bottom: 0; left: 260px; right: 0; height: 64px; background: #191919cc; border-top: 1px solid rgba(255,255,255,0.08); display: flex; align-items: center; justify-content: space-between; padding: 0 24px; z-index: 100; backdrop-filter: blur(20px); }
        .save-status { font-size: 13px; display: flex; align-items: center; gap: 8px; color: #a1a1aa; }
        .footer-actions { display: flex; gap: 8px; }
        .loading { text-align: center; padding: 40px; color: #a1a1aa; }
        .loading i { animation: spin 1s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .footer-bar { left: 0; }
            .key-card-body { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="layout">
        <nav class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">H</div>
                <div class="brand-text">
                    <h2>BSDK Panel</h2>
                    <span>Account Settings</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Navigation</div>
                <a href="/dashboard" class="nav-item"><i class="fa fa-th-large"></i> Dashboard</a>
                <a href="/account" class="nav-item"><i class="fa fa-user"></i> Account</a>
                <a href="/account/api" class="nav-item active"><i class="fa fa-key"></i> API Credentials</a>
                <a href="/account/ssh" class="nav-item"><i class="fa fa-terminal"></i> SSH Keys</a>
                <a href="/account/activity" class="nav-item"><i class="fa fa-clock-o"></i> Activity</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header-row">
                <div class="page-header">
                    <h1><i class="fa fa-key"></i> API Credentials</h1>
                    <p>Manage your API keys for external access and integrations</p>
                </div>
                <button class="btn primary" onclick="openModal()"><i class="fa fa-plus"></i> Create New API Key</button>
            </div>

            <div id="keyList" class="key-list">
                <div class="loading"><i class="fa fa-spinner"></i> Loading API keys...</div>
            </div>
        </main>
    </div>

    <div class="footer-bar">
        <div class="save-status"><i class="fa fa-key"></i> API Credentials Management</div>
        <div class="footer-actions">
            <button class="btn secondary" onclick="loadKeys()"><i class="fa fa-refresh"></i> Refresh</button>
        </div>
    </div>

    <div class="modal-overlay" id="createModal">
        <div class="modal">
            <h2><i class="fa fa-plus"></i> Create New API Key</h2>
            <p>Generate a new API key for programmatic access to your account.</p>
            <div class="field-group">
                <label>Key Name</label>
                <input type="text" id="keyName" placeholder="e.g. My Integration Key" maxlength="100">
                <div class="hint">A descriptive name to identify this key.</div>
            </div>
            <div class="field-group">
                <label>Memo</label>
                <textarea id="keyMemo" rows="3" placeholder="Optional notes about this key..."></textarea>
                <div class="hint">Optional description of what this key is used for.</div>
            </div>
            <div class="field-group">
                <label>Allowed IPs</label>
                <input type="text" id="keyIps" placeholder="e.g. 192.168.1.1, 10.0.0.0/24">
                <div class="hint">Comma-separated list. Leave blank to allow all IPs.</div>
            </div>
            <div class="field-group">
                <div class="toggle-row">
                    <span>Always Allow (ignore IP restrictions)</span>
                    <button class="toggle-btn" id="alwaysAllow" onclick="this.classList.toggle('active')"><div class="toggle-slider"></div></button>
                </div>
            </div>
            <div class="modal-actions">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn primary" onclick="createKey()"><i class="fa fa-plus"></i> Create Key</button>
            </div>
        </div>
    </div>

    <div class="modal-overlay" id="revealModal">
        <div class="modal" style="position:relative;">
            <button class="modal-close" onclick="closeRevealModal()"><i class="fa fa-times"></i></button>
            <h2><i class="fa fa-check-circle" style="color:#22c55e;"></i> API Key Created</h2>
            <div class="key-reveal-warning">
                <i class="fa fa-exclamation-triangle"></i>
                <span>This is the only time your API key will be shown. Copy it now — you won't be able to see it again.</span>
            </div>
            <div class="key-reveal">
                <div class="key-reveal-label"><i class="fa fa-key"></i> Your API Key</div>
                <code id="revealedKey"></code>
                <button class="copy-btn" onclick="copyKey(this)"><i class="fa fa-copy"></i> Copy</button>
            </div>
            <div class="modal-actions">
                <button class="btn primary" onclick="closeRevealModal()">Done</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';

    async function loadKeys() {
        const container = document.getElementById('keyList');
        container.innerHTML = '<div class="loading"><i class="fa fa-spinner"></i> Loading API keys...</div>';
        try {
            const res = await fetch('/api/client/api-keys', {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Failed to load');
            const data = await res.json();
            const keys = data.data || data;
            if (!Array.isArray(keys) || keys.length === 0) {
                container.innerHTML = `
                    <div class="empty-state">
                        <i class="fa fa-key"></i>
                        <h3>No API Keys</h3>
                        <p>You haven't created any API keys yet. Create one to get started.</p>
                        <button class="btn primary" onclick="openModal()"><i class="fa fa-plus"></i> Create Your First Key</button>
                    </div>`;
                return;
            }
            container.innerHTML = keys.map(key => `
                <div class="key-card" id="key-${key.id}">
                    <div class="key-card-header">
                        <h3><i class="fa fa-key"></i> ${escapeHtml(key.name || 'Unnamed Key')}</h3>
                        <span style="font-size:11px;color:#71717a;font-family:'JetBrains Mono',monospace;">${key.identifier ? key.identifier + '...' : ''}</span>
                    </div>
                    <div class="key-card-body">
                        <div class="key-field">
                            <span class="key-field-label">Memo</span>
                            <span class="key-field-value muted">${escapeHtml(key.memo || '—')}</span>
                        </div>
                        <div class="key-field">
                            <span class="key-field-label">Allowed IPs</span>
                            <span class="key-field-value ${key.allowed_ips ? '' : 'muted'}">${key.allowed_ips || 'All IPs'}</span>
                        </div>
                        <div class="key-field">
                            <span class="key-field-label">Created</span>
                            <span class="key-field-value muted">${formatDate(key.created_at)}</span>
                        </div>
                        <div class="key-field">
                            <span class="key-field-label">Last Used</span>
                            <span class="key-field-value muted">${key.last_used_at ? formatDate(key.last_used_at) : 'Never'}</span>
                        </div>
                    </div>
                    <div class="key-card-actions">
                        <button class="btn danger" onclick="revokeKey(${key.id}, '${escapeHtml(key.name || 'Unnamed Key')}')"><i class="fa fa-trash"></i> Revoke</button>
                    </div>
                </div>
            `).join('');
        } catch (e) {
            container.innerHTML = `
                <div class="empty-state">
                    <i class="fa fa-exclamation-triangle"></i>
                    <h3>Failed to Load</h3>
                    <p>Could not load API keys. Please try again.</p>
                    <button class="btn primary" onclick="loadKeys()"><i class="fa fa-refresh"></i> Retry</button>
                </div>`;
        }
    }

    function openModal() {
        document.getElementById('keyName').value = '';
        document.getElementById('keyMemo').value = '';
        document.getElementById('keyIps').value = '';
        document.getElementById('alwaysAllow').classList.remove('active');
        document.getElementById('createModal').classList.add('active');
    }

    function closeModal() {
        document.getElementById('createModal').classList.remove('active');
    }

    async function createKey() {
        const name = document.getElementById('keyName').value.trim();
        const memo = document.getElementById('keyMemo').value.trim();
        const allowed_ips = document.getElementById('keyIps').value.trim();
        const always_allow = document.getElementById('alwaysAllow').classList.contains('active');

        if (!name) {
            showToast('Please enter a key name', 'error');
            return;
        }

        try {
            const res = await fetch('/api/client/api-keys', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': TOKEN
                },
                body: JSON.stringify({ name, memo, allowed_ips, always_allow })
            });

            const data = await res.json();
            if (!res.ok) throw new Error(data.message || 'Failed to create key');

            closeModal();

            const key = data.key || data.data?.key || data.plainTextKey || data.token;
            if (key) {
                document.getElementById('revealedKey').textContent = key;
                document.getElementById('revealModal').classList.add('active');
            }

            showToast('API key created successfully', 'success');
            loadKeys();
        } catch (e) {
            showToast(e.message || 'Failed to create API key', 'error');
        }
    }

    function closeRevealModal() {
        document.getElementById('revealModal').classList.remove('active');
        document.getElementById('revealedKey').textContent = '';
    }

    function copyKey(btn) {
        const text = document.getElementById('revealedKey').textContent;
        navigator.clipboard.writeText(text).then(() => {
            btn.innerHTML = '<i class="fa fa-check"></i> Copied';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.innerHTML = '<i class="fa fa-copy"></i> Copy';
                btn.classList.remove('copied');
            }, 2000);
        }).catch(() => {
            const textarea = document.createElement('textarea');
            textarea.value = text;
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
            btn.innerHTML = '<i class="fa fa-check"></i> Copied';
            btn.classList.add('copied');
            setTimeout(() => {
                btn.innerHTML = '<i class="fa fa-copy"></i> Copy';
                btn.classList.remove('copied');
            }, 2000);
        });
    }

    async function revokeKey(id, name) {
        if (!confirm(`Are you sure you want to revoke the API key "${name}"? This action cannot be undone.`)) return;

        try {
            const res = await fetch(`/api/client/api-keys/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': TOKEN
                }
            });

            if (!res.ok) throw new Error('Failed to revoke key');

            const card = document.getElementById(`key-${id}`);
            if (card) {
                card.style.transition = 'all 0.3s';
                card.style.opacity = '0';
                card.style.transform = 'translateX(20px)';
                setTimeout(() => card.remove(), 300);
            }

            showToast('API key revoked successfully', 'success');
            setTimeout(loadKeys, 400);
        } catch (e) {
            showToast(e.message || 'Failed to revoke API key', 'error');
        }
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const d = new Date(dateStr);
        return d.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });
    document.getElementById('revealModal').addEventListener('click', function(e) {
        if (e.target === this) closeRevealModal();
    });

    loadKeys();
    </script>
</body>
</html>
