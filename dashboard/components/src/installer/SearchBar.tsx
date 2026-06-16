import React from 'react';

interface SearchBarProps {
    value: string;
    onChange: (value: string) => void;
    placeholder?: string;
}

export default function SearchBar({ value, onChange, placeholder = 'Search plugins, mods, modpacks...' }: SearchBarProps) {
    return (
        <div style={{
            position: 'relative',
            maxWidth: '500px',
            marginBottom: '16px',
        }}>
            <svg
                style={{
                    position: 'absolute',
                    left: '14px',
                    top: '50%',
                    transform: 'translateY(-50%)',
                    width: '18px',
                    height: '18px',
                    color: 'var(--bsdk-text3)',
                    pointerEvents: 'none',
                }}
                fill="none"
                stroke="currentColor"
                viewBox="0 0 24 24"
            >
                <path strokeLinecap="round" strokeLinejoin="round" strokeWidth={2} d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <input
                type="text"
                value={value}
                onChange={(e) => onChange(e.target.value)}
                placeholder={placeholder}
                style={{
                    width: '100%',
                    padding: '12px 16px 12px 42px',
                    background: 'var(--bsdk-bg3)',
                    border: '1px solid var(--bsdk-border)',
                    borderRadius: 'var(--bsdk-radius)',
                    color: 'var(--bsdk-text)',
                    fontSize: '13px',
                    fontFamily: 'var(--bsdk-font)',
                    outline: 'none',
                    transition: 'all 0.2s',
                    boxSizing: 'border-box',
                }}
                onFocus={(e) => {
                    e.target.style.borderColor = 'var(--bsdk-primary)';
                    e.target.style.boxShadow = '0 0 0 3px rgba(var(--bsdk-primary-rgb), 0.12)';
                }}
                onBlur={(e) => {
                    e.target.style.borderColor = 'var(--bsdk-border)';
                    e.target.style.boxShadow = 'none';
                }}
            />
        </div>
    );
}
