import React, { useState } from 'react';

interface InstallButtonProps {
    installed: boolean;
    onClick: () => void;
}

export default function InstallButton({ installed, onClick }: InstallButtonProps) {
    const [loading, setLoading] = useState(false);

    const handleClick = async () => {
        setLoading(true);
        try {
            await onClick();
        } finally {
            setLoading(false);
        }
    };

    if (installed) {
        return (
            <div style={{
                display: 'flex',
                alignItems: 'center',
                gap: '8px',
            }}>
                <div style={{
                    flex: 1,
                    padding: '8px 16px',
                    borderRadius: 'var(--bsdk-radius, 8px)',
                    background: 'rgba(var(--bsdk-accent-rgb), 0.12)',
                    color: 'var(--bsdk-accent)',
                    fontSize: '12px',
                    fontWeight: 600,
                    textAlign: 'center',
                    border: '1px solid rgba(var(--bsdk-accent-rgb), 0.2)',
                }}>
                    ✓ Installed
                </div>
            </div>
        );
    }

    return (
        <button
            onClick={handleClick}
            disabled={loading}
            style={{
                flex: 1,
                padding: '8px 16px',
                borderRadius: 'var(--bsdk-radius, 8px)',
                background: loading ? 'var(--bsdk-bg4)' : 'var(--bsdk-primary)',
                color: loading ? 'var(--bsdk-text2)' : '#000',
                fontSize: '12px',
                fontWeight: 600,
                border: 'none',
                cursor: loading ? 'not-allowed' : 'pointer',
                transition: 'all 0.2s',
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                gap: '6px',
            }}
            onMouseEnter={(e) => {
                if (!loading) {
                    e.currentTarget.style.boxShadow = '0 0 20px rgba(var(--bsdk-primary-rgb), 0.3)';
                    e.currentTarget.style.transform = 'translateY(-1px)';
                }
            }}
            onMouseLeave={(e) => {
                e.currentTarget.style.boxShadow = 'none';
                e.currentTarget.style.transform = 'translateY(0)';
            }}
        >
            {loading ? (
                <>
                    <div className="spinner" style={{ width: '14px', height: '14px', borderWidth: '2px' }} />
                    Installing...
                </>
            ) : (
                'Install'
            )}
        </button>
    );
}
