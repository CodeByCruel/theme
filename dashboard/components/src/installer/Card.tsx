import React from 'react';
import InstallButton from './InstallButton';

interface CardProps {
    plugin: {
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
    };
    installed: boolean;
    onInstall: () => void;
}

export default function Card({ plugin, installed, onInstall }: CardProps) {
    const formatDownloads = (n: number) => {
        if (n >= 1000000) return (n / 1000000).toFixed(1) + 'M';
        if (n >= 1000) return (n / 1000).toFixed(1) + 'K';
        return n.toString();
    };

    const providerColors: Record<string, string> = {
        modrinth: '#1bd96a',
        curseforge: '#f16436',
        spigot: '#f5c518',
        hangar: '#f5c518',
        polymart: '#7b68ee',
    };

    return (
        <div style={{
            background: 'var(--bsdk-bg3)',
            border: '1px solid var(--bsdk-border)',
            borderRadius: 'var(--bsdk-radius-lg, 12px)',
            padding: '20px',
            transition: 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)',
            display: 'flex',
            flexDirection: 'column',
        }}
        onMouseEnter={(e) => {
            e.currentTarget.style.borderColor = 'var(--bsdk-border-hover)';
            e.currentTarget.style.boxShadow = '0 0 20px rgba(var(--bsdk-primary-rgb), 0.15)';
            e.currentTarget.style.transform = 'translateY(-2px)';
        }}
        onMouseLeave={(e) => {
            e.currentTarget.style.borderColor = 'var(--bsdk-border)';
            e.currentTarget.style.boxShadow = 'none';
            e.currentTarget.style.transform = 'translateY(0)';
        }}
        >
            {/* Header */}
            <div style={{
                display: 'flex',
                alignItems: 'flex-start',
                gap: '14px',
                marginBottom: '12px',
            }}>
                <div style={{
                    width: '48px',
                    height: '48px',
                    borderRadius: 'var(--bsdk-radius, 8px)',
                    background: 'var(--bsdk-bg4)',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    flexShrink: 0,
                    fontSize: '24px',
                    overflow: 'hidden',
                }}>
                    {plugin.icon ? (
                        <img
                            src={plugin.icon}
                            alt={plugin.name}
                            style={{ width: '100%', height: '100%', objectFit: 'cover' }}
                        />
                    ) : (
                        '🧩'
                    )}
                </div>
                <div style={{ flex: 1, minWidth: 0 }}>
                    <h3 style={{
                        fontSize: '14px',
                        fontWeight: 600,
                        color: 'var(--bsdk-text)',
                        margin: 0,
                        whiteSpace: 'nowrap',
                        overflow: 'hidden',
                        textOverflow: 'ellipsis',
                    }}>
                        {plugin.name}
                    </h3>
                    <div style={{ display: 'flex', alignItems: 'center', gap: '8px', marginTop: '2px' }}>
                        <span style={{ fontSize: '12px', color: 'var(--bsdk-text3)' }}>
                            by {plugin.author}
                        </span>
                        <span style={{
                            fontSize: '9px',
                            fontFamily: 'var(--bsdk-font-mono)',
                            letterSpacing: '1px',
                            padding: '2px 6px',
                            borderRadius: '4px',
                            background: `${providerColors[plugin.provider] || 'var(--bsdk-primary)'}20`,
                            color: providerColors[plugin.provider] || 'var(--bsdk-primary)',
                            textTransform: 'uppercase',
                        }}>
                            {plugin.provider}
                        </span>
                    </div>
                </div>
            </div>

            {/* Description */}
            <p style={{
                fontSize: '12px',
                color: 'var(--bsdk-text2)',
                lineHeight: 1.5,
                margin: '0 0 14px',
                flex: 1,
                display: '-webkit-box',
                WebkitLineClamp: 3,
                WebkitBoxOrient: 'vertical',
                overflow: 'hidden',
            }}>
                {plugin.description || 'No description available.'}
            </p>

            {/* Meta */}
            <div style={{
                display: 'flex',
                gap: '14px',
                marginBottom: '14px',
                flexWrap: 'wrap',
            }}>
                <div style={{
                    fontFamily: 'var(--bsdk-font-mono)',
                    fontSize: '10px',
                    color: 'var(--bsdk-text3)',
                    display: 'flex',
                    alignItems: 'center',
                    gap: '4px',
                }}>
                    ⬇ <span style={{ color: 'var(--bsdk-primary)', fontWeight: 600 }}>
                        {formatDownloads(plugin.downloads)}
                    </span>
                </div>
                {plugin.versions?.length > 0 && (
                    <div style={{
                        fontFamily: 'var(--bsdk-font-mono)',
                        fontSize: '10px',
                        color: 'var(--bsdk-text3)',
                    }}>
                        MC {plugin.versions.slice(0, 3).join(', ')}
                        {plugin.versions.length > 3 && ` +${plugin.versions.length - 3}`}
                    </div>
                )}
                {plugin.loaders?.length > 0 && (
                    <div style={{
                        fontFamily: 'var(--bsdk-font-mono)',
                        fontSize: '10px',
                        color: 'var(--bsdk-text3)',
                    }}>
                        {plugin.loaders.join(', ')}
                    </div>
                )}
            </div>

            {/* Action */}
            <InstallButton
                installed={installed}
                onClick={onInstall}
            />
        </div>
    );
}
