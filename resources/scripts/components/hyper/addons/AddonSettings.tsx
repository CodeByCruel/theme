import React, { useState, useEffect, useCallback } from 'react';

interface Addon {
    id: string;
    name: string;
    description: string;
    icon: string;
    enabled: boolean;
    category: string;
}

const defaultAddons: Addon[] = [
    { id: 'manage-addons', name: 'Manage Addons', description: 'Configure addon availability', icon: 'fa-puzzle-piece', enabled: true, category: 'Core' },
    { id: 'account-info-update', name: 'Account Info Update', description: 'Allows users to update their account information', icon: 'fa-user', enabled: true, category: 'Account' },
    { id: 'ads-layout', name: 'Ads Layout', description: 'Manage and display ads on your panel', icon: 'fa-bullhorn', enabled: false, category: 'Layout' },
    { id: 'auto-suspend', name: 'Auto Suspend', description: 'Automatically suspend servers when they expire', icon: 'fa-pause', enabled: false, category: 'Server' },
    { id: 'command-history', name: 'Command History', description: 'Save and recall previously used console commands', icon: 'fa-history', enabled: true, category: 'Console' },
    { id: 'config-editor', name: 'Config Editor', description: 'Edit server configuration files with an intuitive UI', icon: 'fa-pencil', enabled: true, category: 'Server' },
    { id: 'console-log-upload', name: 'Console Log Upload', description: 'Upload and manage console logs', icon: 'fa-upload', enabled: false, category: 'Console' },
    { id: 'custom-mod-manager', name: 'Custom Mod Manager', description: 'Install and manage server mods (themes/configs) pulled directly', icon: 'fa-puzzle-piece', enabled: true, category: 'Minecraft' },
    { id: 'database-manager', name: 'Database Manager', description: 'Advanced database management tools', icon: 'fa-database', enabled: true, category: 'Server' },
    { id: 'ddos-alert', name: 'DDOS Alert', description: 'Account-level DDoS attack overview with charts and stats', icon: 'fa-shield', enabled: false, category: 'Security' },
    { id: 'demo-mode', name: 'Demo Mode', description: 'Enable demo mode with restricted access for demo users', icon: 'fa-eye', enabled: false, category: 'Core' },
    { id: 'direct-folder-upload', name: 'Direct Folder Upload', description: 'Upload folders directly via the file manager', icon: 'fa-folder-open', enabled: true, category: 'Files' },
    { id: 'discord-bot', name: 'Discord Bot', description: 'A Discord bot integration for your panel', icon: 'fa-comment', enabled: false, category: 'Integration' },
    { id: 'fastdl-manager', name: 'FastDL Manager', description: 'Distributed Fast Download for Source engine games', icon: 'fa-bolt', enabled: false, category: 'Gaming' },
    { id: 'firewall-manager', name: 'Firewall Manager', description: 'Manage iptables firewall rules', icon: 'fa-shield', enabled: false, category: 'Security' },
    { id: 'fivem-utils', name: 'FiveM Utils', description: 'FiveM Utilities: Cache, Build, and more', icon: 'fa-wrench', enabled: false, category: 'Gaming' },
    { id: 'github-source-control', name: 'GitHub Source Control', description: 'GitHub repository import, branches, commits, diffs', icon: 'fa-github', enabled: false, category: 'Integration' },
    { id: 'language-translations', name: 'Language Translations', description: 'Multi-language support for the panel', icon: 'fa-globe', enabled: true, category: 'Core' },
    { id: 'login-as-user', name: 'Login As User', description: 'Allows administrators to securely log in as a registered user', icon: 'fa-sign-in', enabled: true, category: 'Admin' },
    { id: 'login-activity', name: 'Login Activity', description: 'View active sessions and login history', icon: 'fa-clock-o', enabled: true, category: 'Security' },
    { id: 'mc-bedrock-addons', name: 'Minecraft Bedrock Addon Installer', description: 'Install and manage Minecraft Bedrock addons', icon: 'fa-puzzle-piece', enabled: false, category: 'Minecraft' },
    { id: 'mc-bedrock-maps', name: 'Minecraft Bedrock Map Manager', description: 'Manage Minecraft Bedrock maps', icon: 'fa-map', enabled: false, category: 'Minecraft' },
    { id: 'mc-bedrock-packs', name: 'Minecraft Bedrock Pack Installer', description: 'Install and manage Minecraft Bedrock packs', icon: 'fa-box', enabled: false, category: 'Minecraft' },
    { id: 'mc-bedrock-scripts', name: 'Minecraft Bedrock Script Installer', description: 'Install and manage Minecraft Bedrock scripts', icon: 'fa-code', enabled: false, category: 'Minecraft' },
    { id: 'mc-bedrock-versions', name: 'Minecraft Bedrock Version Changer', description: 'Change Minecraft Bedrock server versions', icon: 'fa-refresh', enabled: false, category: 'Minecraft' },
    { id: 'mc-configuration', name: 'Minecraft Configuration', description: 'Configure Minecraft server settings', icon: 'fa-cog', enabled: true, category: 'Minecraft' },
    { id: 'mc-icon-changer', name: 'Minecraft Icon Changer', description: 'Allow uploading and changing server icon', icon: 'fa-image', enabled: true, category: 'Minecraft' },
    { id: 'mc-mod-installer', name: 'Minecraft Mod Installer', description: 'Install and manage Minecraft mods', icon: 'fa-download', enabled: true, category: 'Minecraft' },
    { id: 'mc-modpack-installer', name: 'Minecraft Modpack Installer', description: 'Install and manage Minecraft modpacks', icon: 'fa-box', enabled: true, category: 'Minecraft' },
    { id: 'mc-motd-changer', name: 'Minecraft MOTD Changer', description: 'Allow changing server MOTD (Message of the Day)', icon: 'fa-comment', enabled: true, category: 'Minecraft' },
    { id: 'mc-player-manager', name: 'Minecraft Player Manager', description: 'Manage Minecraft players (kick, ban, whitelist, etc)', icon: 'fa-users', enabled: true, category: 'Minecraft' },
    { id: 'mc-plugin-installer', name: 'Minecraft Plugin Installer', description: 'Install and manage Minecraft plugins', icon: 'fa-puzzle-piece', enabled: true, category: 'Minecraft' },
    { id: 'mc-version-changer', name: 'Minecraft Version Changer', description: 'Change Minecraft server versions', icon: 'fa-refresh', enabled: true, category: 'Minecraft' },
    { id: 'mc-world-manager', name: 'Minecraft World Manager', description: 'Manage Minecraft worlds', icon: 'fa-globe', enabled: true, category: 'Minecraft' },
    { id: 'mc-votifier', name: 'Minecraft Votifier Tester', description: 'Test Votifier plugin configuration', icon: 'fa-check', enabled: false, category: 'Minecraft' },
    { id: 'network-statistics', name: 'Network Statistics', description: 'Monitor network usage and statistics', icon: 'fa-line-chart', enabled: false, category: 'Server' },
    { id: 'notifications', name: 'Notifications', description: 'Notification system for panel events', icon: 'fa-bell', enabled: true, category: 'Core' },
    { id: 'player-manager', name: 'Player Manager', description: 'Manage players across servers (kick, ban, whitelist)', icon: 'fa-users', enabled: true, category: 'Gaming' },
    { id: 'recycle-bin', name: 'Recycle Bin', description: 'Recover deleted servers from the recycle bin', icon: 'fa-recycle', enabled: true, category: 'Server' },
    { id: 'reverse-proxy', name: 'Reverse Proxy', description: 'Manage reverse proxy configurations', icon: 'fa-exchange', enabled: false, category: 'Server' },
    { id: 'server-importer', name: 'Server Importer', description: 'Import servers from external panels', icon: 'fa-download', enabled: false, category: 'Server' },
    { id: 'server-splitter', name: 'Server Splitter', description: 'Split servers into multiple instances', icon: 'fa-code-fork', enabled: false, category: 'Server' },
    { id: 'server-wiper', name: 'Server Wiper', description: 'Wipe server data completely', icon: 'fa-trash', enabled: false, category: 'Server' },
    { id: 'staff-request', name: 'Staff Request', description: 'Allow users to submit staff requests', icon: 'fa-users', enabled: true, category: 'Core' },
    { id: 'subdomain-manager', name: 'Subdomain Manager', description: 'Manage server subdomains', icon: 'fa-sitemap', enabled: false, category: 'Server' },
    { id: 'theme-settings', name: 'Theme Settings', description: 'Customize panel theme, colors, and appearance', icon: 'fa-palette', enabled: true, category: 'Layout' },
];

export default function AddonSettings() {
    const [addons, setAddons] = useState<Addon[]>(defaultAddons);
    const [search, setSearch] = useState('');
    const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');

    useEffect(() => {
        fetch('/api/admin/bsdk/addons')
            .then(r => r.json())
            .then(data => {
                if (data.addons) {
                    setAddons(prev => prev.map(addon => ({
                        ...addon,
                        enabled: data.addons[addon.id] ?? addon.enabled,
                    })));
                }
            })
            .catch(() => {});
    }, []);

    const toggleAddon = useCallback(async (id: string) => {
        setAddons(prev => prev.map(a => a.id === id ? { ...a, enabled: !a.enabled } : a));
        try {
            await fetch(`/api/admin/bsdk/addons/${id}/toggle`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '' },
            });
        } catch (e) { console.error(e); }
    }, []);

    const filteredAddons = addons.filter(a =>
        a.name.toLowerCase().includes(search.toLowerCase()) ||
        a.description.toLowerCase().includes(search.toLowerCase())
    );

    const exportAddons = () => {
        const data: Record<string, boolean> = {};
        addons.forEach(a => { data[a.id] = a.enabled; });
        const blob = new Blob([JSON.stringify(data, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'bsdk-addons.json'; a.click();
        URL.revokeObjectURL(url);
    };

    const importAddons = () => {
        const input = document.createElement('input');
        input.type = 'file'; input.accept = '.json';
        input.onchange = (e: any) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                try {
                    const imported = JSON.parse(ev.target?.result as string);
                    setAddons(prev => prev.map(a => ({
                        ...a,
                        enabled: imported[a.id] ?? a.enabled,
                    })));
                } catch (err) { alert('Invalid JSON file'); }
            };
            reader.readAsText(file);
        };
        input.click();
    };

    return (
        <div className="hyper-addon-settings">
            <div className="hyper-addon-header">
                <div>
                    <h1><i className="fa fa-puzzle-piece" /> Addon Settings</h1>
                    <p>Manage addon availability and settings</p>
                </div>
            </div>

            <div className="hyper-addon-toolbar">
                <div className="hyper-addon-search">
                    <i className="fa fa-search" />
                    <input type="text" placeholder="Search..." value={search} onChange={e => setSearch(e.target.value)} />
                </div>
                <div className="hyper-addon-view-toggle">
                    <button className={viewMode === 'grid' ? 'active' : ''} onClick={() => setViewMode('grid')}><i className="fa fa-th-large" /></button>
                    <button className={viewMode === 'list' ? 'active' : ''} onClick={() => setViewMode('list')}><i className="fa fa-list" /></button>
                </div>
                <button className="hyper-btn secondary" onClick={exportAddons}><i className="fa fa-download" /> Export</button>
                <button className="hyper-btn secondary" onClick={importAddons}><i className="fa fa-upload" /> Import</button>
            </div>

            <div className={`hyper-addon-grid ${viewMode}`}>
                {filteredAddons.map(addon => (
                    <div key={addon.id} className={`hyper-addon-card ${addon.enabled ? 'enabled' : ''}`}>
                        <div className="hyper-addon-card-icon"><i className={`fa ${addon.icon}`} /></div>
                        <div className="hyper-addon-card-info">
                            <h3>{addon.name}</h3>
                            <p>{addon.description}</p>
                        </div>
                        <div className="hyper-addon-card-actions">
                            <button className="hyper-btn secondary small"><i className="fa fa-cog" /> Manage</button>
                            <button
                                className={`hyper-toggle ${addon.enabled ? 'active' : ''}`}
                                onClick={() => toggleAddon(addon.id)}
                            >
                                <span className="hyper-toggle-slider" />
                            </button>
                        </div>
                    </div>
                ))}
            </div>
        </div>
    );
}
