import React, { useState, useEffect, useCallback } from 'react';
import SearchBar from './SearchBar';
import Filters from './Filters';
import Card from './Card';

const PROVIDERS = [
    { id: 'modrinth', name: 'Modrinth', icon: '📦' },
    { id: 'curseforge', name: 'CurseForge', icon: '🔥' },
    { id: 'spigot', name: 'SpigotMC', icon: '⛏️' },
    { id: 'hangar', name: 'Hangar', icon: '📄' },
    { id: 'polymart', name: 'Polymart', icon: '🛒' },
];

const CATEGORIES = [
    { id: 'plugin', name: 'Plugins', icon: '🧩' },
    { id: 'mod', name: 'Mods', icon: '⚙️' },
    { id: 'modpack', name: 'Modpacks', icon: '📦' },
    { id: 'resourcepack', name: 'Resource Packs', icon: '🎨' },
    { id: 'shader', name: 'Shaders', icon: '✨' },
];

const MINECRAFT_VERSIONS = [
    '1.21.4', '1.21.3', '1.21.2', '1.21.1', '1.21',
    '1.20.6', '1.20.4', '1.20.2', '1.20.1', '1.20',
    '1.19.4', '1.19.3', '1.19.2',
];

const LOADERS = {
    plugin: ['Paper', 'Spigot', 'Bukkit', 'Purpur', 'Folia'],
    mod: ['Forge', 'Fabric', 'NeoForge', 'Quilt'],
    modpack: ['Forge', 'Fabric', 'NeoForge'],
    resourcepack: [],
    shader: ['Iris', 'OptiFine', 'Oculus'],
};

interface Plugin {
    id: string;
    name: string;
    slug: string;
    description: string;
    author: string;
    downloads: number;
    icon: string;
    provider: string;
    category: string;
    versions: string[];
    loaders: string[];
    dateUpdated: string;
}

export default function PluginBrowser() {
    const [plugins, setPlugins] = useState<Plugin[]>([]);
    const [loading, setLoading] = useState(false);
    const [search, setSearch] = useState('');
    const [provider, setProvider] = useState('modrinth');
    const [category, setCategory] = useState('plugin');
    const [gameVersion, setGameVersion] = useState('');
    const [loader, setLoader] = useState('');
    const [page, setPage] = useState(1);
    const [totalResults, setTotalResults] = useState(0);
    const [installedTab, setInstalledTab] = useState(false);
    const [installed, setInstalled] = useState<string[]>([]);

    const searchPlugins = useCallback(async () => {
        setLoading(true);
        try {
            const serverId = window.location.pathname.split('/')[2];
            const params = new URLSearchParams({
                query: search,
                provider,
                category,
                ...(gameVersion && { game_version: gameVersion }),
                ...(loader && { loader }),
                page: page.toString(),
            });

            const response = await fetch(`/api/client/servers/${serverId}/installer/search?${params}`);
            const data = await response.json();
            setPlugins(data.data || []);
            setTotalResults(data.meta?.total || 0);
        } catch (err) {
            console.error('Search failed:', err);
            setPlugins([]);
        } finally {
            setLoading(false);
        }
    }, [search, provider, category, gameVersion, loader, page]);

    const loadInstalled = useCallback(async () => {
        try {
            const serverId = window.location.pathname.split('/')[2];
            const response = await fetch(`/api/client/servers/${serverId}/installer/installed`);
            const data = await response.json();
            setInstalled(data.map((p: any) => p.filename));
        } catch (err) {
            console.error('Failed to load installed:', err);
        }
    }, []);

    useEffect(() => {
        searchPlugins();
    }, [searchPlugins]);

    useEffect(() => {
        if (installedTab) loadInstalled();
    }, [installedTab, loadInstalled]);

    const handleInstall = async (plugin: Plugin) => {
        try {
            const serverId = window.location.pathname.split('/')[2];
            const response = await fetch(`/api/client/servers/${serverId}/installer/install`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    id: plugin.id,
                    provider: plugin.provider,
                    category: plugin.category,
                }),
            });

            if (response.ok) {
                setInstalled([...installed, plugin.slug]);
                alert(`${plugin.name} installed successfully!`);
            } else {
                const err = await response.json();
                alert(err.message || 'Installation failed');
            }
        } catch (err) {
            alert('Installation failed. Please try again.');
        }
    };

    const handleRemove = async (filename: string) => {
        if (!confirm(`Remove ${filename}?`)) return;
        try {
            const serverId = window.location.pathname.split('/')[2];
            const response = await fetch(`/api/client/servers/${serverId}/installer/remove`, {
                method: 'DELETE',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ filename }),
            });

            if (response.ok) {
                setInstalled(installed.filter(f => f !== filename));
            }
        } catch (err) {
            alert('Removal failed');
        }
    };

    return (
        <div style={{
            padding: '24px',
            fontFamily: 'var(--bsdk-font)',
            color: 'var(--bsdk-text)',
        }}>
            {/* Header */}
            <div style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                marginBottom: '24px',
                flexWrap: 'wrap',
                gap: '16px',
            }}>
                <div>
                    <h2 style={{
                        fontSize: '22px',
                        fontWeight: 700,
                        margin: 0,
                        background: 'linear-gradient(135deg, var(--bsdk-primary), var(--bsdk-accent))',
                        WebkitBackgroundClip: 'text',
                        WebkitTextFillColor: 'transparent',
                    }}>
                        Plugins & Mods
                    </h2>
                    <p style={{ color: 'var(--bsdk-text2)', fontSize: '13px', margin: '4px 0 0' }}>
                        Browse, search, and install from multiple providers
                    </p>
                </div>

                {/* Tab toggle */}
                <div style={{
                    display: 'flex',
                    background: 'var(--bsdk-bg3)',
                    borderRadius: '8px',
                    padding: '3px',
                    border: '1px solid var(--bsdk-border)',
                }}>
                    <button
                        onClick={() => setInstalledTab(false)}
                        style={{
                            padding: '8px 16px',
                            borderRadius: '6px',
                            border: 'none',
                            background: !installedTab ? 'var(--bsdk-primary)' : 'transparent',
                            color: !installedTab ? '#000' : 'var(--bsdk-text2)',
                            fontWeight: 600,
                            fontSize: '12px',
                            cursor: 'pointer',
                            transition: 'all 0.2s',
                        }}
                    >
                        Browse
                    </button>
                    <button
                        onClick={() => setInstalledTab(true)}
                        style={{
                            padding: '8px 16px',
                            borderRadius: '6px',
                            border: 'none',
                            background: installedTab ? 'var(--bsdk-primary)' : 'transparent',
                            color: installedTab ? '#000' : 'var(--bsdk-text2)',
                            fontWeight: 600,
                            fontSize: '12px',
                            cursor: 'pointer',
                            transition: 'all 0.2s',
                        }}
                    >
                        Installed ({installed.length})
                    </button>
                </div>
            </div>

            {!installedTab ? (
                <>
                    {/* Provider selector */}
                    <div style={{
                        display: 'flex',
                        gap: '8px',
                        marginBottom: '16px',
                        flexWrap: 'wrap',
                    }}>
                        {PROVIDERS.map(p => (
                            <button
                                key={p.id}
                                onClick={() => setProvider(p.id)}
                                style={{
                                    padding: '8px 14px',
                                    borderRadius: '20px',
                                    border: `1px solid ${provider === p.id ? 'var(--bsdk-primary)' : 'var(--bsdk-border)'}`,
                                    background: provider === p.id ? 'rgba(var(--bsdk-primary-rgb), 0.12)' : 'var(--bsdk-bg3)',
                                    color: provider === p.id ? 'var(--bsdk-primary)' : 'var(--bsdk-text2)',
                                    fontSize: '12px',
                                    fontWeight: 500,
                                    cursor: 'pointer',
                                    transition: 'all 0.2s',
                                    display: 'flex',
                                    alignItems: 'center',
                                    gap: '6px',
                                }}
                            >
                                <span>{p.icon}</span>
                                {p.name}
                            </button>
                        ))}
                    </div>

                    {/* Search */}
                    <SearchBar value={search} onChange={setSearch} />

                    {/* Filters */}
                    <Filters
                        category={category}
                        onCategoryChange={setCategory}
                        gameVersion={gameVersion}
                        onGameVersionChange={setGameVersion}
                        loader={loader}
                        onLoaderChange={setLoader}
                        categories={CATEGORIES}
                        versions={MINECRAFT_VERSIONS}
                        loaders={LOADERS[category] || []}
                    />

                    {/* Results */}
                    {loading ? (
                        <div style={{
                            display: 'grid',
                            gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))',
                            gap: '16px',
                        }}>
                            {[1, 2, 3, 4, 5, 6].map(i => (
                                <div key={i} className="skeleton" style={{ height: '200px' }} />
                            ))}
                        </div>
                    ) : plugins.length === 0 ? (
                        <div style={{
                            textAlign: 'center',
                            padding: '60px 20px',
                            color: 'var(--bsdk-text3)',
                        }}>
                            <div style={{ fontSize: '48px', marginBottom: '16px' }}>🔍</div>
                            <p>No results found. Try a different search or filter.</p>
                        </div>
                    ) : (
                        <>
                            <div style={{
                                display: 'flex',
                                alignItems: 'center',
                                justifyContent: 'space-between',
                                marginBottom: '16px',
                                color: 'var(--bsdk-text3)',
                                fontSize: '12px',
                            }}>
                                <span>{totalResults} results found</span>
                            </div>
                            <div style={{
                                display: 'grid',
                                gridTemplateColumns: 'repeat(auto-fill, minmax(320px, 1fr))',
                                gap: '16px',
                            }}>
                                {plugins.map(plugin => (
                                    <Card
                                        key={plugin.id}
                                        plugin={plugin}
                                        installed={installed.includes(plugin.slug)}
                                        onInstall={() => handleInstall(plugin)}
                                    />
                                ))}
                            </div>
                            {/* Pagination */}
                            {totalResults > 20 && (
                                <div style={{
                                    display: 'flex',
                                    justifyContent: 'center',
                                    gap: '8px',
                                    marginTop: '24px',
                                }}>
                                    <button
                                        onClick={() => setPage(p => Math.max(1, p - 1))}
                                        disabled={page === 1}
                                        className="btn btn-secondary"
                                    >
                                        Previous
                                    </button>
                                    <span style={{
                                        padding: '8px 16px',
                                        color: 'var(--bsdk-text2)',
                                        fontSize: '13px',
                                    }}>
                                        Page {page} of {Math.ceil(totalResults / 20)}
                                    </span>
                                    <button
                                        onClick={() => setPage(p => p + 1)}
                                        disabled={page >= Math.ceil(totalResults / 20)}
                                        className="btn btn-secondary"
                                    >
                                        Next
                                    </button>
                                </div>
                            )}
                        </>
                    )}
                </>
            ) : (
                /* Installed tab */
                <div>
                    {installed.length === 0 ? (
                        <div style={{
                            textAlign: 'center',
                            padding: '60px 20px',
                            color: 'var(--bsdk-text3)',
                        }}>
                            <div style={{ fontSize: '48px', marginBottom: '16px' }}>📦</div>
                            <p>No plugins installed yet.</p>
                        </div>
                    ) : (
                        <div style={{
                            display: 'grid',
                            gridTemplateColumns: 'repeat(auto-fill, minmax(300px, 1fr))',
                            gap: '12px',
                        }}>
                            {installed.map(filename => (
                                <div
                                    key={filename}
                                    style={{
                                        background: 'var(--bsdk-bg3)',
                                        border: '1px solid var(--bsdk-border)',
                                        borderRadius: 'var(--bsdk-radius)',
                                        padding: '14px 16px',
                                        display: 'flex',
                                        alignItems: 'center',
                                        justifyContent: 'space-between',
                                    }}
                                >
                                    <div>
                                        <div style={{ fontSize: '13px', fontWeight: 500 }}>
                                            {filename}
                                        </div>
                                    </div>
                                    <button
                                        onClick={() => handleRemove(filename)}
                                        style={{
                                            padding: '4px 10px',
                                            borderRadius: '6px',
                                            border: '1px solid var(--bsdk-danger)',
                                            background: 'transparent',
                                            color: 'var(--bsdk-danger)',
                                            fontSize: '11px',
                                            fontWeight: 600,
                                            cursor: 'pointer',
                                        }}
                                    >
                                        Remove
                                    </button>
                                </div>
                            ))}
                        </div>
                    )}
                </div>
            )}
        </div>
    );
}
