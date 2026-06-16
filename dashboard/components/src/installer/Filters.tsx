import React from 'react';

interface FiltersProps {
    category: string;
    onCategoryChange: (cat: string) => void;
    gameVersion: string;
    onGameVersionChange: (ver: string) => void;
    loader: string;
    onLoaderChange: (loader: string) => void;
    categories: { id: string; name: string; icon: string }[];
    versions: string[];
    loaders: string[];
}

export default function Filters({
    category,
    onCategoryChange,
    gameVersion,
    onGameVersionChange,
    loader,
    onLoaderChange,
    categories,
    versions,
    loaders,
}: FiltersProps) {
    return (
        <div style={{ marginBottom: '20px' }}>
            {/* Category chips */}
            <div style={{
                display: 'flex',
                gap: '8px',
                marginBottom: '12px',
                flexWrap: 'wrap',
            }}>
                {categories.map(cat => (
                    <button
                        key={cat.id}
                        onClick={() => onCategoryChange(cat.id)}
                        style={{
                            padding: '6px 14px',
                            borderRadius: '20px',
                            border: `1px solid ${category === cat.id ? 'var(--bsdk-primary)' : 'var(--bsdk-border)'}`,
                            background: category === cat.id ? 'rgba(var(--bsdk-primary-rgb), 0.12)' : 'var(--bsdk-bg3)',
                            color: category === cat.id ? 'var(--bsdk-primary)' : 'var(--bsdk-text2)',
                            fontSize: '12px',
                            fontWeight: 500,
                            cursor: 'pointer',
                            transition: 'all 0.2s',
                            display: 'flex',
                            alignItems: 'center',
                            gap: '6px',
                        }}
                    >
                        <span>{cat.icon}</span>
                        {cat.name}
                    </button>
                ))}
            </div>

            {/* Version & loader selectors */}
            <div style={{
                display: 'flex',
                gap: '12px',
                flexWrap: 'wrap',
            }}>
                <select
                    value={gameVersion}
                    onChange={(e) => onGameVersionChange(e.target.value)}
                    style={{
                        padding: '8px 32px 8px 12px',
                        background: 'var(--bsdk-bg3)',
                        border: '1px solid var(--bsdk-border)',
                        borderRadius: 'var(--bsdk-radius)',
                        color: 'var(--bsdk-text)',
                        fontSize: '12px',
                        fontFamily: 'var(--bsdk-font)',
                        outline: 'none',
                        cursor: 'pointer',
                        appearance: 'none',
                        backgroundImage: `url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%238899aa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E")`,
                        backgroundRepeat: 'no-repeat',
                        backgroundPosition: 'right 10px center',
                        minWidth: '140px',
                    }}
                >
                    <option value="">All Versions</option>
                    {versions.map(v => (
                        <option key={v} value={v}>{v}</option>
                    ))}
                </select>

                {loaders.length > 0 && (
                    <select
                        value={loader}
                        onChange={(e) => onLoaderChange(e.target.value)}
                        style={{
                            padding: '8px 32px 8px 12px',
                            background: 'var(--bsdk-bg3)',
                            border: '1px solid var(--bsdk-border)',
                            borderRadius: 'var(--bsdk-radius)',
                            color: 'var(--bsdk-text)',
                            fontSize: '12px',
                            fontFamily: 'var(--bsdk-font)',
                            outline: 'none',
                            cursor: 'pointer',
                            appearance: 'none',
                            backgroundImage: `url("data:image/svg+xml,%3Csvg width='10' height='6' viewBox='0 0 10 6' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L5 5L9 1' stroke='%238899aa' stroke-width='1.5' stroke-linecap='round' stroke-linejoin='round'/%3E%3C/svg%3E")`,
                            backgroundRepeat: 'no-repeat',
                            backgroundPosition: 'right 10px center',
                            minWidth: '140px',
                        }}
                    >
                        <option value="">All Loaders</option>
                        {loaders.map(l => (
                            <option key={l} value={l.toLowerCase()}>{l}</option>
                        ))}
                    </select>
                )}
            </div>
        </div>
    );
}
