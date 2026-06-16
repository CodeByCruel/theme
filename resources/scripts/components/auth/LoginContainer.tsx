import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';
import { useAuth } from '@/hooks/useAuth';

export default function LoginContainer() {
    const [email, setEmail] = useState('');
    const [password, setPassword] = useState('');
    const [loading, setLoading] = useState(false);
    const [error, setError] = useState('');
    const navigate = useNavigate();
    const { login } = useAuth();

    const handleSubmit = async (e: React.FormEvent) => {
        e.preventDefault();
        setLoading(true);
        setError('');
        try {
            await login(email, password);
            navigate('/dashboard');
        } catch (err) {
            setError('Invalid credentials. Please try again.');
        } finally {
            setLoading(false);
        }
    };

    return (
        <div className="login-container">
            {/* Particle background */}
            <canvas
                id="bsdk-particles"
                style={{
                    position: 'fixed',
                    top: 0,
                    left: 0,
                    width: '100vw',
                    height: '100vh',
                    zIndex: 0,
                    pointerEvents: 'none',
                }}
            />

            <div className="login-box" style={{ zIndex: 1, position: 'relative' }}>
                {/* Gradient top bar */}
                <div style={{
                    position: 'absolute',
                    top: 0,
                    left: 0,
                    right: 0,
                    height: '3px',
                    background: 'linear-gradient(90deg, #00d4ff, #00ff88, #7b68ee)',
                }} />

                {/* Logo */}
                <div style={{ textAlign: 'center', marginBottom: '24px' }}>
                    <img
                        src="/assets/bsdk/logo.svg"
                        alt="BSDK"
                        style={{ height: '48px', marginBottom: '16px' }}
                    />
                    <h1 style={{
                        fontSize: '28px',
                        fontWeight: 800,
                        background: 'linear-gradient(135deg, #00d4ff, #00ff88)',
                        WebkitBackgroundClip: 'text',
                        WebkitTextFillColor: 'transparent',
                        marginBottom: '4px',
                    }}>
                        BSDK Panel
                    </h1>
                    <p style={{ color: '#8899aa', fontSize: '14px' }}>
                        Game Server Management
                    </p>
                </div>

                {error && (
                    <div className="alert alert-danger" style={{ marginBottom: '16px' }}>
                        {error}
                    </div>
                )}

                <form onSubmit={handleSubmit}>
                    <div style={{ marginBottom: '16px' }}>
                        <label style={{
                            display: 'block',
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#8899aa',
                            marginBottom: '6px',
                        }}>
                            Email
                        </label>
                        <input
                            type="email"
                            value={email}
                            onChange={(e) => setEmail(e.target.value)}
                            placeholder="you@example.com"
                            required
                            style={{
                                width: '100%',
                                padding: '12px 14px',
                                background: '#243044',
                                border: '1px solid rgba(255,255,255,0.08)',
                                borderRadius: '8px',
                                color: '#e4e8f0',
                                fontSize: '14px',
                                outline: 'none',
                            }}
                        />
                    </div>

                    <div style={{ marginBottom: '24px' }}>
                        <label style={{
                            display: 'block',
                            fontSize: '12px',
                            fontWeight: 600,
                            color: '#8899aa',
                            marginBottom: '6px',
                        }}>
                            Password
                        </label>
                        <input
                            type="password"
                            value={password}
                            onChange={(e) => setPassword(e.target.value)}
                            placeholder="••••••••"
                            required
                            style={{
                                width: '100%',
                                padding: '12px 14px',
                                background: '#243044',
                                border: '1px solid rgba(255,255,255,0.08)',
                                borderRadius: '8px',
                                color: '#e4e8f0',
                                fontSize: '14px',
                                outline: 'none',
                            }}
                        />
                    </div>

                    <button
                        type="submit"
                        disabled={loading}
                        style={{
                            width: '100%',
                            padding: '12px',
                            background: loading ? '#243044' : '#00d4ff',
                            color: loading ? '#8899aa' : '#000',
                            borderRadius: '8px',
                            fontSize: '14px',
                            fontWeight: 700,
                            border: 'none',
                            cursor: loading ? 'not-allowed' : 'pointer',
                            transition: 'all 0.2s',
                        }}
                    >
                        {loading ? 'Signing in...' : 'Sign In'}
                    </button>
                </form>

                <div style={{
                    textAlign: 'center',
                    marginTop: '24px',
                    paddingTop: '16px',
                    borderTop: '1px solid rgba(255,255,255,0.08)',
                    fontSize: '11px',
                    color: '#5a6a7a',
                }}>
                    BSDK V1 — Made by Akshit
                </div>
            </div>
        </div>
    );
}
