import React from 'react';
import { Link, useLocation } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';
import { useServerStore } from '@/store/server';

export default function NavigationBar() {
    const { user, logout } = useAuth();
    const location = useLocation();
    const servers = useServerStore((s) => s.servers);

    const isActive = (path: string) => location.pathname.startsWith(path);

    return (
        <nav style={{
            position: 'fixed',
            top: 0,
            left: '260px',
            right: 0,
            height: '64px',
            background: '#111827',
            borderBottom: '1px solid rgba(255,255,255,0.08)',
            display: 'flex',
            alignItems: 'center',
            justifyContent: 'space-between',
            padding: '0 24px',
            zIndex: 30,
            backdropFilter: 'blur(12px)',
        }}>
            {/* Left: Breadcrumb */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '8px' }}>
                <span style={{
                    fontFamily: "'JetBrains Mono', monospace",
                    fontSize: '11px',
                    letterSpacing: '1px',
                    color: '#5a6a7a',
                    textTransform: 'uppercase',
                }}>
                    {location.pathname.split('/').filter(Boolean).join(' / ') || 'dashboard'}
                </span>
            </div>

            {/* Right: User */}
            <div style={{ display: 'flex', alignItems: 'center', gap: '16px' }}>
                {/* Server count */}
                <div style={{
                    display: 'flex',
                    alignItems: 'center',
                    gap: '6px',
                    padding: '6px 12px',
                    background: 'rgba(0, 212, 255, 0.08)',
                    borderRadius: '20px',
                    fontSize: '12px',
                    color: '#00d4ff',
                    fontWeight: 600,
                }}>
                    <span style={{
                        width: '6px',
                        height: '6px',
                        borderRadius: '50%',
                        background: '#00ff88',
                        boxShadow: '0 0 8px rgba(0, 255, 136, 0.5)',
                    }} />
                    {servers.length} server{servers.length !== 1 ? 's' : ''}
                </div>

                {/* User menu */}
                <div style={{ display: 'flex', alignItems: 'center', gap: '10px' }}>
                    <div style={{
                        width: '32px',
                        height: '32px',
                        borderRadius: '50%',
                        background: 'linear-gradient(135deg, #00d4ff, #7b68ee)',
                        display: 'flex',
                        alignItems: 'center',
                        justifyContent: 'center',
                        fontSize: '13px',
                        fontWeight: 700,
                        color: '#000',
                    }}>
                        {user?.username?.charAt(0)?.toUpperCase() || '?'}
                    </div>
                    <div>
                        <div style={{ fontSize: '13px', fontWeight: 600, color: '#e4e8f0' }}>
                            {user?.username || 'User'}
                        </div>
                        <div style={{ fontSize: '11px', color: '#5a6a7a' }}>
                            {user?.root_admin ? 'Admin' : 'User'}
                        </div>
                    </div>
                </div>
            </div>
        </nav>
    );
}
