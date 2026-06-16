import React from 'react';
import { Link } from 'react-router-dom';
import { Server } from '@/typer/server';

interface ServerCardProps {
    server: Server;
}

export default function ServerCard({ server }: ServerCardProps) {
    const statusColor = {
        running: '#00ff88',
        starting: '#ffaa00',
        stopped: '#ff4466',
        offline: '#ff4466',
    }[server.status] || '#ff4466';

    return (
        <Link
            to={`/server/${server.id}`}
            style={{ textDecoration: 'none' }}
        >
            <div style={{
                background: '#1a2332',
                border: '1px solid rgba(255,255,255,0.08)',
                borderLeft: `3px solid ${statusColor}`,
                borderRadius: '12px',
                padding: '16px 20px',
                transition: 'all 0.2s cubic-bezier(0.4, 0, 0.2, 1)',
                cursor: 'pointer',
                position: 'relative',
                overflow: 'hidden',
            }}
            onMouseEnter={(e) => {
                e.currentTarget.style.borderColor = statusColor;
                e.currentTarget.style.boxShadow = `0 0 20px ${statusColor}20`;
                e.currentTarget.style.transform = 'translateY(-3px)';
            }}
            onMouseLeave={(e) => {
                e.currentTarget.style.borderColor = 'rgba(255,255,255,0.08)';
                e.currentTarget.style.boxShadow = 'none';
                e.currentTarget.style.transform = 'translateY(0)';
            }}
            >
                {/* Top gradient line */}
                <div style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: 0,
                    height: '1px',
                    background: `linear-gradient(90deg, transparent, ${statusColor}40, transparent)`,
                }} />

                {/* Header */}
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '10px' }}>
                    <div>
                        <h3 style={{
                            fontSize: '14px',
                            fontWeight: 600,
                            color: '#e4e8f0',
                            margin: 0,
                        }}>
                            {server.name}
                        </h3>
                        <div style={{
                            fontFamily: "'JetBrains Mono', monospace",
                            fontSize: '11px',
                            color: '#5a6a7a',
                            marginTop: '2px',
                        }}>
                            {server.node}
                        </div>
                    </div>

                    {/* Status dot */}
                    <div style={{
                        width: '8px',
                        height: '8px',
                        borderRadius: '50%',
                        background: statusColor,
                        boxShadow: `0 0 8px ${statusColor}80`,
                        animation: server.status === 'starting' ? 'pulseDot 1s infinite' : undefined,
                    }} />
                </div>

                {/* Specs */}
                <div style={{ display: 'flex', gap: '16px', flexWrap: 'wrap' }}>
                    <div style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#8899aa',
                    }}>
                        CPU <span style={{ color: '#00d4ff', fontWeight: 600 }}>
                            {server.cpu}%
                        </span>
                    </div>
                    <div style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#8899aa',
                    }}>
                        RAM <span style={{ color: '#00d4ff', fontWeight: 600 }}>
                            {server.memory ? `${(server.memory / 1024).toFixed(0)}GB` : '?'}
                        </span>
                    </div>
                    <div style={{
                        fontFamily: "'JetBrains Mono', monospace",
                        fontSize: '11px',
                        color: '#8899aa',
                    }}>
                        Disk <span style={{ color: '#00d4ff', fontWeight: 600 }}>
                            {server.disk ? `${server.disk}GB` : '?'}
                        </span>
                    </div>
                </div>

                {/* Status badge */}
                <div style={{
                    marginTop: '10px',
                    display: 'inline-flex',
                    alignItems: 'center',
                    gap: '4px',
                    padding: '3px 8px',
                    borderRadius: '12px',
                    background: `${statusColor}15`,
                    border: `1px solid ${statusColor}30`,
                    fontSize: '10px',
                    fontFamily: "'JetBrains Mono', monospace",
                    fontWeight: 600,
                    color: statusColor,
                    letterSpacing: '0.5px',
                    textTransform: 'uppercase',
                }}>
                    {server.status}
                </div>
            </div>
        </Link>
    );
}
