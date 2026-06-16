<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Settings — BSDK V1</title>
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
        .account-grid { display: grid; grid-template-columns: 340px 1fr; gap: 24px; }
        .avatar-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 32px; text-align: center; position: sticky; top: 32px; height: fit-content; }
        .avatar-circle { width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #df3050, #ff6b6b); display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; font-size: 42px; font-weight: 800; color: #fff; text-transform: uppercase; position: relative; overflow: hidden; }
        .avatar-circle img { width: 100%; height: 100%; object-fit: cover; }
        .avatar-actions { display: flex; gap: 8px; justify-content: center; margin-top: 16px; }
        .avatar-stats { display: flex; justify-content: center; gap: 24px; margin-top: 20px; padding-top: 20px; border-top: 1px solid rgba(255,255,255,0.08); }
        .avatar-stat { text-align: center; }
        .avatar-stat .val { font-size: 18px; font-weight: 700; color: #fff; }
        .avatar-stat .lbl { font-size: 11px; color: #a1a1aa; margin-top: 2px; }
        .form-card { background: #191919cc; border: 1px solid rgba(255,255,255,0.08); border-radius: 12px; padding: 32px; }
        .form-section { margin-bottom: 32px; }
        .form-section:last-child { margin-bottom: 0; }
        .form-section-title { font-size: 16px; font-weight: 600; color: #fff; margin-bottom: 4px; display: flex; align-items: center; gap: 8px; }
        .form-section-title i { color: #df3050; }
        .form-section-desc { font-size: 13px; color: #a1a1aa; margin-bottom: 20px; }
        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
        .field-group { margin-bottom: 16px; }
        .field-group label { display: block; font-size: 13px; font-weight: 600; color: #fff; margin-bottom: 8px; }
        .field-group input[type="text"], .field-group input[type="email"], .field-group input[type="password"], .field-group select {
            width: 100%; padding: 10px 14px; border: 1px solid rgba(255,255,255,0.08); background: #0c0a09; color: #fff; border-radius: 8px; font-size: 13px; outline: none; transition: border-color 0.2s;
        }
        .field-group input:focus, .field-group select:focus { border-color: #df3050; }
        .field-group select option { background: #0c0a09; color: #fff; }
        .toggle-row { display: flex; align-items: center; justify-content: space-between; padding: 16px; background: #0c0a09; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; }
        .toggle-row .toggle-info h4 { font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 2px; }
        .toggle-row .toggle-info p { font-size: 12px; color: #a1a1aa; }
        .toggle-btn { position: relative; width: 48px; height: 26px; border: none; background: #292524; border-radius: 13px; cursor: pointer; transition: background 0.3s; flex-shrink: 0; }
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
        .toast { position: fixed; bottom: 80px; right: 24px; background: #191919; border: 1px solid rgba(255,255,255,0.08); border-radius: 8px; padding: 12px 20px; color: #fff; font-size: 13px; z-index: 200; transform: translateY(100px); opacity: 0; transition: all 0.3s; }
        .toast.show { transform: translateY(0); opacity: 1; }
        .toast.success { border-color: #22c55e; }
        .toast.error { border-color: #ff0000; }
        .loading-overlay { position: fixed; inset: 0; background: #0c0a09; display: flex; align-items: center; justify-content: center; z-index: 300; }
        .loading-overlay .spinner { width: 40px; height: 40px; border: 3px solid rgba(255,255,255,0.08); border-top-color: #df3050; border-radius: 50%; animation: spin 0.8s linear infinite; }
        @keyframes spin { to { transform: rotate(360deg); } }
        @media (max-width: 768px) {
            .sidebar-admin { transform: translateX(-100%); }
            .main-content { margin-left: 0; }
            .footer-bar { left: 0; }
            .account-grid { grid-template-columns: 1fr; }
            .form-row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <div class="loading-overlay" id="loadingOverlay">
        <div class="spinner"></div>
    </div>

    <div class="layout">
        <nav class="sidebar-admin">
            <div class="brand">
                <div class="brand-icon">H</div>
                <div class="brand-text">
                    <h2>Hyper Panel</h2>
                    <span>BSDK V1</span>
                </div>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Main</div>
                <a href="/dashboard" class="nav-item"><i class="fa fa-tachometer"></i> Dashboard</a>
                <a href="/account" class="nav-item active"><i class="fa fa-user"></i> Account</a>
                <a href="/account/api" class="nav-item"><i class="fa fa-key"></i> API Credentials</a>
                <a href="/account/ssh" class="nav-item"><i class="fa fa-terminal"></i> SSH Keys</a>
                <a href="/account/activity" class="nav-item"><i class="fa fa-history"></i> Activity</a>
            </div>
            <div class="nav-section">
                <div class="nav-section-title">Quick Links</div>
                <a href="/dashboard" class="nav-item"><i class="fa fa-arrow-left"></i> Back to Dashboard</a>
                <a href="/" class="nav-item"><i class="fa fa-home"></i> Panel Home</a>
            </div>
        </nav>

        <main class="main-content">
            <div class="page-header">
                <h1><i class="fa fa-user"></i> Account Settings</h1>
                <p>Manage your personal information and security preferences</p>
            </div>

            <div class="account-grid">
                <div class="avatar-card">
                    <div class="avatar-circle" id="avatarCircle">
                        <span id="avatarInitials">--</span>
                    </div>
                    <div class="avatar-actions">
                        <button class="btn secondary" onclick="document.getElementById('avatarInput').click()"><i class="fa fa-camera"></i> Change Avatar</button>
                        <input type="file" id="avatarInput" accept="image/*" style="display:none;" onchange="uploadAvatar(event)">
                        <button class="btn secondary" onclick="removeAvatar()"><i class="fa fa-trash"></i></button>
                    </div>
                    <div class="avatar-stats">
                        <div class="avatar-stat">
                            <div class="val" id="statServers">-</div>
                            <div class="lbl">Servers</div>
                        </div>
                        <div class="avatar-stat">
                            <div class="val" id="statSince">-</div>
                            <div class="lbl">Member Since</div>
                        </div>
                    </div>
                </div>

                <div>
                    <div class="form-card">
                        <div class="form-section">
                            <div class="form-section-title"><i class="fa fa-id-card"></i> Personal Information</div>
                            <div class="form-section-desc">Update your personal details</div>
                            <div class="form-row">
                                <div class="field-group">
                                    <label>Username</label>
                                    <input type="text" id="fieldUsername" placeholder="Username">
                                </div>
                                <div class="field-group">
                                    <label>Email</label>
                                    <input type="email" id="fieldEmail" placeholder="Email address">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="field-group">
                                    <label>First Name</label>
                                    <input type="text" id="fieldFirstName" placeholder="First name">
                                </div>
                                <div class="field-group">
                                    <label>Last Name</label>
                                    <input type="text" id="fieldLastName" placeholder="Last name">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-title"><i class="fa fa-lock"></i> Change Password</div>
                            <div class="form-section-desc">Leave blank to keep your current password</div>
                            <div class="field-group">
                                <label>Current Password</label>
                                <input type="password" id="fieldCurrentPassword" placeholder="Enter current password">
                            </div>
                            <div class="form-row">
                                <div class="field-group">
                                    <label>New Password</label>
                                    <input type="password" id="fieldNewPassword" placeholder="Enter new password">
                                </div>
                                <div class="field-group">
                                    <label>Confirm New Password</label>
                                    <input type="password" id="fieldConfirmPassword" placeholder="Confirm new password">
                                </div>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-title"><i class="fa fa-shield"></i> Security</div>
                            <div class="form-section-desc">Manage two-factor authentication</div>
                            <div class="toggle-row">
                                <div class="toggle-info">
                                    <h4>Two-Factor Authentication</h4>
                                    <p>Require a verification code when signing in</p>
                                </div>
                                <button class="toggle-btn" id="toggle2fa" onclick="toggleTwoFactor()"><div class="toggle-slider"></div></button>
                            </div>
                        </div>

                        <div class="form-section">
                            <div class="form-section-title"><i class="fa fa-globe"></i> Preferences</div>
                            <div class="form-section-desc">Set your language and timezone</div>
                            <div class="form-row">
                                <div class="field-group">
                                    <label>Language</label>
                                    <select id="fieldLanguage">
                                        <option value="en">English</option>
                                        <option value="ar">Arabic</option>
                                        <option value="cn">Chinese</option>
                                        <option value="de">German</option>
                                        <option value="es">Spanish</option>
                                        <option value="fr">French</option>
                                        <option value="hi">Hindi</option>
                                        <option value="id">Indonesian</option>
                                        <option value="it">Italian</option>
                                        <option value="ja">Japanese</option>
                                        <option value="pl">Polish</option>
                                        <option value="pt">Portuguese</option>
                                        <option value="ru">Russian</option>
                                        <option value="th">Thai</option>
                                        <option value="tr">Turkish</option>
                                    </select>
                                </div>
                                <div class="field-group">
                                    <label>Timezone</label>
                                    <select id="fieldTimezone">
                                        <option value="UTC">UTC</option>
                                        <option value="America/New_York">Eastern Time (ET)</option>
                                        <option value="America/Chicago">Central Time (CT)</option>
                                        <option value="America/Denver">Mountain Time (MT)</option>
                                        <option value="America/Los_Angeles">Pacific Time (PT)</option>
                                        <option value="Europe/London">London (GMT)</option>
                                        <option value="Europe/Paris">Paris (CET)</option>
                                        <option value="Europe/Berlin">Berlin (CET)</option>
                                        <option value="Asia/Dubai">Dubai (GST)</option>
                                        <option value="Asia/Kolkata">India (IST)</option>
                                        <option value="Asia/Shanghai">China (CST)</option>
                                        <option value="Asia/Tokyo">Japan (JST)</option>
                                        <option value="Australia/Sydney">Sydney (AEST)</option>
                                        <option value="Pacific/Auckland">Auckland (NZST)</option>
                                        <option value="America/Sao_Paulo">Sao Paulo (BRT)</option>
                                        <option value="Africa/Cairo">Egypt (EET)</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <div class="footer-bar">
        <div class="save-status" id="saveStatus"><i class="fa fa-check-circle"></i> All changes saved</div>
        <div class="footer-actions">
            <button class="btn secondary" onclick="loadAccount()"><i class="fa fa-refresh"></i> Reload</button>
            <button class="btn primary" onclick="saveAccount()"><i class="fa fa-save"></i> Save</button>
        </div>
    </div>

    <div class="toast" id="toast"></div>

    <script>
    const API = '/api/client/account';
    const TOKEN = document.querySelector('meta[name="csrf-token"]')?.content || '';
    let account = {};
    let twoFactorEnabled = false;

    async function loadAccount() {
        try {
            const res = await fetch(API, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' }
            });
            if (!res.ok) throw new Error('Failed');
            account = await res.json();
            populateFields();
        } catch(e) {
            showToast('Failed to load account data', 'error');
        }
        document.getElementById('loadingOverlay').style.display = 'none';
    }

    function populateFields() {
        const u = account.data || account;
        document.getElementById('fieldUsername').value = u.username || '';
        document.getElementById('fieldEmail').value = u.email || '';
        document.getElementById('fieldFirstName').value = u.first_name || '';
        document.getElementById('fieldLastName').value = u.last_name || '';
        document.getElementById('fieldLanguage').value = u.language || 'en';
        document.getElementById('fieldTimezone').value = u.timezone || 'UTC';

        twoFactorEnabled = u.two_factor_enabled || false;
        update2faToggle();

        const name = ((u.first_name || '') + ' ' + (u.last_name || '')).trim();
        const initials = name ? name.split(' ').map(w => w[0]).join('').substring(0, 2).toUpperCase() : (u.username || 'U').substring(0, 2).toUpperCase();
        document.getElementById('avatarInitials').textContent = initials;

        document.getElementById('statServers').textContent = u.server_count || 0;
        const joined = u.created_at ? new Date(u.created_at).toLocaleDateString('en-US', { month: 'short', year: 'numeric' }) : '-';
        document.getElementById('statSince').textContent = joined;

        document.getElementById('fieldCurrentPassword').value = '';
        document.getElementById('fieldNewPassword').value = '';
        document.getElementById('fieldConfirmPassword').value = '';

        markSaved();
    }

    function update2faToggle() {
        const btn = document.getElementById('toggle2fa');
        if (twoFactorEnabled) {
            btn.classList.add('active');
        } else {
            btn.classList.remove('active');
        }
    }

    function toggleTwoFactor() {
        twoFactorEnabled = !twoFactorEnabled;
        update2faToggle();
        markUnsaved();
    }

    async function saveAccount() {
        const newPass = document.getElementById('fieldNewPassword').value;
        const confirmPass = document.getElementById('fieldConfirmPassword').value;
        if (newPass && newPass !== confirmPass) {
            showToast('New passwords do not match', 'error');
            return;
        }

        const payload = {
            username: document.getElementById('fieldUsername').value,
            email: document.getElementById('fieldEmail').value,
            first_name: document.getElementById('fieldFirstName').value,
            last_name: document.getElementById('fieldLastName').value,
            language: document.getElementById('fieldLanguage').value,
            timezone: document.getElementById('fieldTimezone').value,
            two_factor_enabled: twoFactorEnabled,
        };

        if (document.getElementById('fieldCurrentPassword').value) {
            payload.current_password = document.getElementById('fieldCurrentPassword').value;
        }
        if (newPass) {
            payload.password = newPass;
            payload.password_confirmation = confirmPass;
        }

        try {
            const res = await fetch(API, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': TOKEN
                },
                body: JSON.stringify(payload)
            });
            if (res.ok) {
                markSaved();
                showToast('Account updated successfully', 'success');
                loadAccount();
            } else {
                const err = await res.json().catch(() => ({}));
                const msg = err.message || err.errors ? Object.values(err.errors || {}).flat().join(', ') : 'Failed to save';
                showToast(msg, 'error');
            }
        } catch(e) {
            showToast('Failed to save account', 'error');
        }
    }

    function markUnsaved() {
        document.getElementById('saveStatus').innerHTML = '<i class="fa fa-circle" style="color:#f59e0b;"></i> Unsaved changes';
    }

    function markSaved() {
        document.getElementById('saveStatus').innerHTML = '<i class="fa fa-check-circle" style="color:#22c55e;"></i> All changes saved';
    }

    async function uploadAvatar(e) {
        const file = e.target.files[0];
        if (!file) return;
        const formData = new FormData();
        formData.append('avatar', file);
        try {
            const res = await fetch(API + '/avatar', {
                method: 'POST',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN },
                body: formData
            });
            if (res.ok) {
                showToast('Avatar updated', 'success');
                loadAccount();
            } else {
                showToast('Failed to upload avatar', 'error');
            }
        } catch(e) {
            showToast('Failed to upload avatar', 'error');
        }
    }

    async function removeAvatar() {
        try {
            const res = await fetch(API + '/avatar', {
                method: 'DELETE',
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': TOKEN }
            });
            if (res.ok) {
                showToast('Avatar removed', 'success');
                loadAccount();
            }
        } catch(e) {
            showToast('Failed to remove avatar', 'error');
        }
    }

    function showToast(msg, type) {
        const t = document.getElementById('toast');
        t.textContent = msg;
        t.className = 'toast ' + type + ' show';
        setTimeout(() => t.className = 'toast', 3000);
    }

    ['fieldUsername','fieldEmail','fieldFirstName','fieldLastName','fieldCurrentPassword','fieldNewPassword','fieldConfirmPassword','fieldLanguage','fieldTimezone'].forEach(id => {
        document.getElementById(id).addEventListener('input', markUnsaved);
    });

    loadAccount();
    </script>
</body>
</html>
