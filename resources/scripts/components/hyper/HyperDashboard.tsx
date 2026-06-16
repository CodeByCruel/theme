import React, { useState, useEffect } from 'react';

interface Server {
    id: number;
    name: string;
    node: string;
    status: 'running' | 'stopped' | 'starting' | 'stopping';
    game: string;
    egg: string;
    image: string;
    cpu: number;
    memory: number;
    disk: number;
    uptime: string;
    ip: string;
    port: number;
}

const gameImages: Record<string, string> = {
    'minecraft': '/DGEN/themes/Hyperv2/server/card/paper.webp',
    'paper': '/DGEN/themes/Hyperv2/server/card/paper.webp',
    'bungeecord': '/DGEN/themes/Hyperv2/server/card/bungeecord.webp',
    'vanilla': '/DGEN/themes/Hyperv2/server/card/vanilla bedrock.webp',
    'bedrock': '/DGEN/themes/Hyperv2/server/card/vanilla bedrock.webp',
    'counter-strike': '/DGEN/themes/Hyperv2/server/card/counter-strike_ global offensive.webp',
    'default': '/DGEN/themes/Hyperv2/server/card/default.webp',
};

function getServerImage(server: Server): string {
    const egg = (server.egg || '').toLowerCase();
    const game = (server.game || '').toLowerCase();
    for (const [key, img] of Object.entries(gameImages)) {
        if (egg.includes(key) || game.includes(key)) return img;
    }
    return gameImages['default'];
}

export default function HyperDashboard() {
    const [servers, setServers] = useState<Server[]>([]);
    const [filter, setFilter] = useState<'all' | 'online' | 'offline'>('all');
    const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');
    const [sortBy, setSortBy] = useState<'name' | 'status'>('name');

    useEffect(() => {
        fetch('/api/client/servers', {
            headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
        })
            .then(r => r.json())
            .then(data => {
                if (data.data) {
                    setServers(data.data.map((s: any) => ({
                        id: s.attributes.id,
                        name: s.attributes.name,
                        node: s.attributes.node_name || '',
                        status: s.attributes.status || 'stopped',
                        game: s.attributes.game || '',
                        egg: s.attributes.egg_name || '',
                        image: s.attributes.container?.image || '',
                        cpu: s.attributes.cpu || 0,
                        memory: s.attributes.memory || 0,
                        disk: s.attributes.disk || 0,
                        uptime: s.attributes.uptime || '',
                        ip: s.attributes.host || '',
                        port: s.attributes.port || 0,
                    })));
                }
            })
            .catch(() => {});
    }, []);

    const onlineCount = servers.filter(s => s.status === 'running').length;
    const offlineCount = servers.filter(s => s.status !== 'running').length;

    const filteredServers = servers
        .filter(s => {
            if (filter === 'online') return s.status === 'running';
            if (filter === 'offline') return s.status !== 'running';
            return true;
        })
        .sort((a, b) => {
            if (sortBy === 'status') {
                if (a.status === 'running' && b.status !== 'running') return -1;
                if (a.status !== 'running' && b.status === 'running') return 1;
            }
            return a.name.localeCompare(b.name);
        });

    return (
        <div className="hyper-dashboard">
            <div className="hyper-dashboard-header">
                <div>
                    <h1><i className="fa fa-th-large" /> Dashboard</h1>
                    <p>Manage your game servers</p>
                </div>
            </div>

            <div className="hyper-server-sorter">
                <div className="hyper-sorter-header">
                    <span className="hyper-sorter-title">Server Sorter <i className="fa fa-chevron-down" /></span>
                    <button className="hyper-btn secondary small" onClick={() => setFilter('all')}>Clear</button>
                    <button className="hyper-btn primary small">Showing Your Servers</button>
                </div>
                <div className="hyper-sorter-filters">
                    <button className={`hyper-filter-btn ${filter === 'all' ? 'active' : ''}`} onClick={() => setFilter('all')}>
                        <i className="fa fa-th-large" /> All Server : {servers.length}
                    </button>
                    <button className={`hyper-filter-btn offline ${filter === 'offline' ? 'active' : ''}`} onClick={() => setFilter('offline')}>
                        <span className="hyper-status-dot offline" /> Offline : {offlineCount}
                    </button>
                </div>
                <div className="hyper-sorter-controls">
                    <button className="hyper-sort-btn active"><i className="fa fa-sort-amount-asc" /></button>
                    <button className="hyper-sort-btn"><i className="fa fa-sort-amount-desc" /></button>
                    <div className="hyper-view-toggle">
                        <button className={viewMode === 'grid' ? 'active' : ''} onClick={() => setViewMode('grid')}><i className="fa fa-th-large" /></button>
                        <button className={viewMode === 'list' ? 'active' : ''} onClick={() => setViewMode('list')}><i className="fa fa-minus" /></button>
                        <button className={viewMode === 'compact' as any ? 'active' : ''} onClick={() => setViewMode('list')}><i className="fa fa-minus" /></button>
                    </div>
                </div>
            </div>

            <div className={`hyper-server-grid ${viewMode}`}>
                {filteredServers.map(server => (
                    <a key={server.id} href={`/server/${server.id}`} className="hyper-server-card">
                        <div className="hyper-server-card-bg">
                            <img src={getServerImage(server)} alt="" />
                            <div className="hyper-server-card-overlay" />
                        </div>
                        <div className="hyper-server-card-content">
                            <div className="hyper-server-card-header">
                                <span className="hyper-server-egg">{server.egg || server.game}</span>
                                <span className={`hyper-server-status ${server.status}`}>
                                    <span className="hyper-status-dot" /> {server.status === 'running' ? 'Online' : 'Offline'}
                                </span>
                            </div>
                            <h3 className="hyper-server-name">{server.name}</h3>
                            <div className="hyper-server-stats">
                                <span>CPU: {Math.round(server.cpu)}%</span>
                                <span>RAM: {server.memory}MB</span>
                            </div>
                        </div>
                    </a>
                ))}
            </div>

            <div className="hyper-dashboard-footer">
                <span>{window.BSDK?.name || 'BSDK Panel'}&reg; {new Date().getFullYear()} - {new Date().getFullYear() + 1}</span>
            </div>
        </div>
    );
}
