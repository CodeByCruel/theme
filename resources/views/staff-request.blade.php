<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Request — BSDK V1</title>
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
        .request-list { display: flex; flex-direction: column; gap: 12px; }
        .request-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; overflow: hidden; transition: all 0.3s; }
        .request-card:hover { border-color: rgba(223, 48, 80, 0.3); }
        .request-card-header { padding: 20px 24px; cursor: pointer; display: flex; align-items: center; gap: 16px; }
        .request-card-header:hover { background: rgba(223, 48, 80, 0.03); }
        .request-icon { width: 44px; height: 44px; border-radius: 10px; background: rgba(223, 48, 80, 0.1); display: flex; align-items: center; justify-content: center; font-size: 18px; color: #df3050; flex-shrink: 0; }
        .request-info { flex: 1; min-width: 0; }
        .request-info h3 { font-size: 15px; font-weight: 600; color: #fff; margin-bottom: 4px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .request-meta { display: flex; align-items: center; gap: 12px; flex-wrap: wrap; }
        .request-meta span { font-size: 12px; color: #a1a1aa; display: flex; align-items: center; gap: 4px; }
        .request-actions { display: flex; align-items: center; gap: 12px; flex-shrink: 0; }
        .badge { padding: 4px 10px; border-radius: 20px; font-size: 11px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge.open { background: rgba(34, 197, 94, 0.15); color: #22c55e; }
        .badge.pending { background: rgba(245, 158, 11, 0.15); color: #f59e0b; }
        .badge.closed { background: rgba(161, 161, 170, 0.15); color: #a1a1aa; }
        .expand-icon { color: #a1a1aa; transition: transform 0.3s; font-size: 14px; }
        .request-card.expanded .expand-icon { transform: rotate(180deg); }
        .request-card-body { display: none; padding: 0 24px 20px; border-top: 1px solid rgba(255,255,255,0.06); }
        .request-card.expanded .request-card-body { display: block; }
        .request-message { padding: 16px 0; color: #d4d4d8; font-size: 14px; line-height: 1.7; white-space: pre-wrap; }
        .reply-thread { margin-top: 12px; }
        .reply-thread h4 { font-size: 13px; font-weight: 600; color: #a1a1aa; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px; }
        .reply-item { background: #0c0a09; border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 14px 16px; margin-bottom: 8px; }
        .reply-item.staff { border-left: 3px solid #df3050; }
        .reply-item-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 6px; }
        .reply-item-author { font-size: 12px; font-weight: 600; color: #fff; }
        .reply-item-author span { color: #df3050; }
        .reply-item-date { font-size: 11px; color: #a1a1aa; }
        .reply-item-text { font-size: 13px; color: #d4d4d8; line-height: 1.6; }
        .reply-input-wrap { display: flex; gap: 8px; margin-top: 16px; }
        .reply-input-wrap input { flex: 1; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; }
        .reply-input-wrap input:focus { border-color: #df3050; }
        .empty-state { text-align: center; padding: 60px 20px; color: #a1a1aa; }
        .empty-state i { font-size: 48px; color: #292524; margin-bottom: 16px; display: block; }
        .empty-state h3 { font-size: 18px; color: #fff; margin-bottom: 8px; }
        .empty-state p { font-size: 14px; max-width: 360px; margin: 0 auto; }
        .modal-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); z-index: 300; align-items: center; justify-content: center; backdrop-filter: blur(4px); }
        .modal-overlay.active { display: flex; }
        .modal { background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 16px; width: 100%; max-width: 520px; max-height: 90vh; overflow-y: auto; }
        .modal-header { display: flex; align-items: center; justify-content: space-between; padding: 20px 24px; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .modal-header h2 { font-size: 18px; font-weight: 700; color: #fff; }
        .modal-header h2 i { margin-right: 8px; color: #df3050; }
        .modal-close { width: 32px; height: 32px; border: none; background: rgba(255,255,255,0.06); color: #a1a1aa; border-radius: 8px; cursor: pointer; font-size: 16px; transition: all 0.2s; }
        .modal-close:hover { background: #df3050; color: #fff; }
        .modal-body { padding: 24px; }
        .field-group { margin-bottom: 16px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .field-group input, .field-group textarea, .field-group select {
            width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s; font-family: inherit;
        }
        .field-group input:focus, .field-group textarea:focus, .field-group select:focus { border-color: #df3050; }
        .field-group textarea { resize: vertical; min-height: 100px; }
        .modal-footer { display: flex; justify-content: flex-end; gap: 8px; padding: 16px 24px; border-top: 1px solid rgba(255,255,255,0.06); }
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
                <a href="/staff-request" class="nav-item active"><i class="fa fa-life-ring"></i> Staff Request</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Quick Links</div>
                <a href="/notifications" class="nav-item"><i class="fa fa-bell"></i> Notifications</a>
                <a href="/" class="nav-item"><i class="fa fa-home"></i> Panel Home</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <div>
                    <h1><i class="fa fa-life-ring"></i> Staff Request</h1>
                    <p>Submit and manage support requests to our staff team</p>
                </div>
                <button class="btn primary" onclick="openModal()"><i class="fa fa-plus"></i> New Request</button>
            </div>

            <div id="requestList" class="request-list">
                <div class="loading"><i class="fa fa-spinner"></i> Loading requests...</div>
            </div>
        </main>
    </div>

    <div class="modal-overlay" id="modalOverlay">
        <div class="modal">
            <div class="modal-header">
                <h2><i class="fa fa-life-ring"></i> New Staff Request</h2>
                <button class="modal-close" onclick="closeModal()"><i class="fa fa-times"></i></button>
            </div>
            <div class="modal-body">
                <div class="field-group">
                    <label>Subject</label>
                    <input type="text" id="reqSubject" placeholder="Brief summary of your issue">
                </div>
                <div class="field-group">
                    <label>Category</label>
                    <select id="reqCategory">
                        <option value="General">General</option>
                        <option value="Billing">Billing</option>
                        <option value="Technical">Technical</option>
                        <option value="Report">Report</option>
                    </select>
                </div>
                <div class="field-group">
                    <label>Priority</label>
                    <select id="reqPriority">
                        <option value="Low">Low</option>
                        <option value="Medium">Medium</option>
                        <option value="High">High</option>
                    </select>
                </div>
                <div class="field-group">
                    <label>Message</label>
                    <textarea id="reqMessage" rows="5" placeholder="Describe your issue in detail..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn secondary" onclick="closeModal()">Cancel</button>
                <button class="btn primary" onclick="submitRequest()"><i class="fa fa-paper-plane"></i> Submit</button>
            </div>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/client/staff-request';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let requests = [];

    async function loadRequests() {
        const el = document.getElementById('requestList');
        el.innerHTML = '<div class="loading"><i class="fa fa-spinner"></i> Loading requests...</div>';
        try {
            const res = await fetch(API, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' } });
            const data = await res.json();
            requests = Array.isArray(data) ? data : (data.data || []);
        } catch(e) { requests = []; }
        renderRequests();
    }

    function renderRequests() {
        const el = document.getElementById('requestList');
        if (requests.length === 0) {
            el.innerHTML = `<div class="empty-state"><i class="fa fa-life-ring"></i><h3>No Requests Yet</h3><p>Submit your first staff request using the button above.</p></div>`;
            return;
        }
        el.innerHTML = requests.map((r, i) => {
            const status = (r.status || 'open').toLowerCase();
            const replies = r.replies || r.messages || [];
            const repliesHtml = replies.length > 0 ? replies.map(rep => `
                <div class="reply-item ${rep.is_staff ? 'staff' : ''}">
                    <div class="reply-item-header">
                        <span class="reply-item-author">${rep.is_staff ? '<span>Staff</span> ' : ''}${esc(rep.author || rep.user || 'You')}</span>
                        <span class="reply-item-date">${formatDate(rep.created_at)}</span>
                    </div>
                    <div class="reply-item-text">${esc(rep.message || rep.body || '')}</div>
                </div>
            `).join('') : '<p style="font-size:13px;color:#a1a1aa;">No replies yet.</p>';

            return `
            <div class="request-card" id="req-${i}">
                <div class="request-card-header" onclick="toggleCard(${i})">
                    <div class="request-icon"><i class="fa fa-life-ring"></i></div>
                    <div class="request-info">
                        <h3>${esc(r.subject || 'Untitled')}</h3>
                        <div class="request-meta">
                            <span><i class="fa fa-tag"></i> ${esc(r.category || 'General')}</span>
                            <span><i class="fa fa-calendar"></i> ${formatDate(r.created_at)}</span>
                            <span><i class="fa fa-flag"></i> ${esc(r.priority || 'Medium')}</span>
                        </div>
                    </div>
                    <div class="request-actions">
                        <span class="badge ${status}">${status}</span>
                        <i class="fa fa-chevron-down expand-icon"></i>
                    </div>
                </div>
                <div class="request-card-body">
                    <div class="request-message">${esc(r.message || r.body || 'No message content.')}</div>
                    <div class="reply-thread">
                        <h4>Replies (${replies.length})</h4>
                        ${repliesHtml}
                    </div>
                    <div class="reply-input-wrap">
                        <input type="text" id="replyInput-${i}" placeholder="Write a reply..." onkeydown="if(event.key==='Enter')sendReply(${i})">
                        <button class="btn primary sm" onclick="sendReply(${i})"><i class="fa fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>`;
        }).join('');
    }

    function toggleCard(i) {
        document.getElementById('req-' + i)?.classList.toggle('expanded');
    }

    async function sendReply(i) {
        const input = document.getElementById('replyInput-' + i);
        const msg = input?.value?.trim();
        if (!msg) return;
        const r = requests[i];
        input.value = '';
        try {
            const res = await fetch(API + '/' + (r.id || i) + '/reply', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ message: msg })
            });
            if (res.ok) {
                showToast('Reply sent', 'success');
                loadRequests();
            } else {
                showToast('Failed to send reply', 'error');
            }
        } catch(e) { showToast('Network error', 'error'); }
    }

    function openModal() {
        document.getElementById('modalOverlay').classList.add('active');
    }

    function closeModal() {
        document.getElementById('modalOverlay').classList.remove('active');
        document.getElementById('reqSubject').value = '';
        document.getElementById('reqMessage').value = '';
        document.getElementById('reqCategory').value = 'General';
        document.getElementById('reqPriority').value = 'Medium';
    }

    async function submitRequest() {
        const subject = document.getElementById('reqSubject').value.trim();
        const category = document.getElementById('reqCategory').value;
        const priority = document.getElementById('reqPriority').value;
        const message = document.getElementById('reqMessage').value.trim();
        if (!subject || !message) { showToast('Subject and message are required', 'error'); return; }
        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: JSON.stringify({ subject, category, priority, message })
            });
            if (res.ok) {
                closeModal();
                showToast('Request submitted', 'success');
                loadRequests();
            } else {
                showToast('Failed to submit request', 'error');
            }
        } catch(e) { showToast('Network error', 'error'); }
    }

    function esc(s) { const d = document.createElement('div'); d.textContent = s; return d.innerHTML; }

    function formatDate(d) {
        if (!d) return '';
        const dt = new Date(d);
        if (isNaN(dt)) return d;
        return dt.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric', hour: '2-digit', minute: '2-digit' });
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    document.getElementById('modalOverlay').addEventListener('click', e => { if (e.target === e.currentTarget) closeModal(); });

    loadRequests();
    </script>
</body>
</html>
