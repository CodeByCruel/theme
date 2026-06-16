import React, { useState, useEffect, useCallback } from 'react';

interface SettingCategory {
    id: string;
    title: string;
    description: string;
    icon: string;
    settings: SettingField[];
}

interface SettingField {
    key: string;
    label: string;
    type: 'text' | 'color' | 'select' | 'toggle' | 'textarea' | 'number' | 'range' | 'image';
    value: any;
    options?: { label: string; value: string }[];
    description?: string;
    min?: number;
    max?: number;
}

const defaultCategories: SettingCategory[] = [
    {
        id: 'theme-colors',
        title: 'Theme Colors',
        description: 'Control color variables, interface surfaces, and accent colors',
        icon: 'fa-palette',
        settings: [
            { key: '--hyper-primary', label: 'Primary Color', type: 'color', value: '#df3050' },
            { key: '--hyper-primary-hover', label: 'Primary Hover', type: 'color', value: '#e44b63' },
            { key: '--hyper-secondary', label: 'Secondary Color', type: 'color', value: '#27272a' },
            { key: '--hyper-accent', label: 'Accent Color', type: 'color', value: '#292524' },
            { key: '--hyper-background', label: 'Background Color', type: 'color', value: '#0c0a09' },
            { key: '--hyper-card', label: 'Card Background', type: 'color', value: '#191919cc' },
            { key: '--hyper-surface', label: 'Surface Color', type: 'color', value: '#1c19177a' },
            { key: '--hyper-sidebar', label: 'Sidebar Color', type: 'color', value: '#191919cc' },
            { key: '--hyper-muted', label: 'Muted Color', type: 'color', value: '#262626' },
            { key: '--hyper-destructive', label: 'Destructive Color', type: 'color', value: '#7f1d1d' },
            { key: '--hyper-destructive-text', label: 'Destructive Text', type: 'color', value: '#ff0000' },
            { key: '--hyper-text-primary', label: 'Text Primary', type: 'color', value: '#ffffff' },
            { key: '--hyper-text-secondary', label: 'Text Secondary', type: 'color', value: '#fafafa' },
            { key: '--hyper-text-muted', label: 'Text Muted', type: 'color', value: '#a1a1aa' },
        ],
    },
    {
        id: 'brand-identity',
        title: 'Brand Identity & Auth',
        description: 'Manage logos, authentication page branding, and footer',
        icon: 'fa-image',
        settings: [
            { key: 'panel_name', label: 'Panel Name', type: 'text', value: 'BSDK Panel' },
            { key: 'panel_tagline', label: 'Panel Tagline', type: 'text', value: 'Game Server Management' },
            { key: 'logo_path', label: 'Logo Path', type: 'text', value: '/assets/bsdk/logo.svg' },
            { key: 'favicon_path', label: 'Favicon Path', type: 'text', value: '/assets/bsdk/favicon.svg' },
            { key: 'login_bg', label: 'Login Background', type: 'text', value: '/DGEN/background.webp' },
            { key: 'footer_text', label: 'Footer Text', type: 'text', value: '' },
        ],
    },
    {
        id: 'navigation-layout',
        title: 'Navigation & Layout',
        description: 'Organize navigation items, sidebar behavior, and server layout',
        icon: 'fa-bars',
        settings: [
            { key: 'sidebar_style', label: 'Sidebar Style', type: 'select', value: 'modern', options: [
                { label: 'Modern', value: 'modern' },
                { label: 'Classic', value: 'classic' },
                { label: 'Compact', value: 'compact' },
            ]},
            { key: 'compact_mode', label: 'Compact Mode', type: 'toggle', value: 'false' },
            { key: 'card_style', label: 'Card Style', type: 'select', value: 'glass', options: [
                { label: 'Glass', value: 'glass' },
                { label: 'Solid', value: 'solid' },
                { label: 'Minimal', value: 'minimal' },
            ]},
            { key: 'button_style', label: 'Button Style', type: 'select', value: 'rounded', options: [
                { label: 'Rounded', value: 'rounded' },
                { label: 'Square', value: 'square' },
                { label: 'Pill', value: 'pill' },
            ]},
        ],
    },
    {
        id: 'server-console',
        title: 'Server Console',
        description: 'Manage console stat blocks, ordering, visibility, default font',
        icon: 'fa-terminal',
        settings: [
            { key: 'console_font', label: 'Console Font', type: 'select', value: 'JetBrains Mono', options: [
                { label: 'JetBrains Mono', value: 'JetBrains Mono' },
                { label: 'Fira Code', value: 'Fira Code' },
                { label: 'Source Code Pro', value: 'Source Code Pro' },
                { label: 'Cascadia Code', value: 'Cascadia Code' },
            ]},
            { key: 'console_font_size', label: 'Console Font Size', type: 'number', value: 14, min: 10, max: 24 },
        ],
    },
    {
        id: 'background-effects',
        title: 'Background Media & Effects',
        description: 'Configure layered effects, stars, globe visuals, and custom backgrounds',
        icon: 'fa-star',
        settings: [
            { key: 'particle_bg', label: 'Particle Background', type: 'toggle', value: 'true' },
            { key: 'gradient_enabled', label: 'Gradient Effects', type: 'toggle', value: 'true' },
            { key: 'glow_enabled', label: 'Glow Effects', type: 'toggle', value: 'true' },
            { key: 'animations_enabled', label: 'Animations', type: 'toggle', value: 'true' },
        ],
    },
    {
        id: 'server-artwork',
        title: 'Server Artwork',
        description: 'Assign default, egg-specific, and nest-specific images for servers',
        icon: 'fa-paint-brush',
        settings: [
            { key: 'server_banner_minecraft', label: 'Minecraft Banner', type: 'text', value: '/DGEN/themes/Hyperv2/server/banner/minecraft.webp' },
            { key: 'server_banner_csgo', label: 'CS:GO Banner', type: 'text', value: '/DGEN/themes/Hyperv2/server/banner/counter-strike_ global offensive.webp' },
            { key: 'server_banner_default', label: 'Default Banner', type: 'text', value: '/DGEN/themes/Hyperv2/server/banner/default.webp' },
            { key: 'server_card_minecraft', label: 'Minecraft Card', type: 'text', value: '/DGEN/themes/Hyperv2/server/card/paper.webp' },
            { key: 'server_card_default', label: 'Default Card', type: 'text', value: '/DGEN/themes/Hyperv2/server/card/default.webp' },
        ],
    },
    {
        id: 'email-branding',
        title: 'Email Branding',
        description: 'Customize outgoing email logos, text, alignment',
        icon: 'fa-envelope',
        settings: [
            { key: 'email_sender_name', label: 'Sender Name', type: 'text', value: '' },
            { key: 'email_header_text', label: 'Email Header Text', type: 'text', value: '' },
        ],
    },
    {
        id: 'external-links',
        title: 'External Links',
        description: 'Set the support, billing, community, and social links',
        icon: 'fa-link',
        settings: [
            { key: 'link_support', label: 'Support URL', type: 'text', value: '' },
            { key: 'link_billing', label: 'Billing URL', type: 'text', value: '' },
            { key: 'link_community', label: 'Community URL', type: 'text', value: '' },
            { key: 'link_twitter', label: 'Twitter URL', type: 'text', value: '' },
            { key: 'link_discord', label: 'Discord URL', type: 'text', value: '' },
            { key: 'link_github', label: 'GitHub URL', type: 'text', value: '' },
        ],
    },
    {
        id: 'browser-seo',
        title: 'Browser Identity & SEO',
        description: 'Control browser metadata, sharing previews, favicon',
        icon: 'fa-globe',
        settings: [
            { key: 'meta_description', label: 'Meta Description', type: 'textarea', value: '' },
            { key: 'meta_image', label: 'OG Image URL', type: 'text', value: '' },
            { key: 'meta_color', label: 'Theme Color', type: 'color', value: '#df3050' },
        ],
    },
    {
        id: 'rendering-display',
        title: 'Rendering & System Display',
        description: 'Tune blur rendering, storage unit display, and CPU usage',
        icon: 'fa-desktop',
        settings: [
            { key: 'blur_amount', label: 'Blur Amount (px)', type: 'range', value: 16, min: 0, max: 32 },
            { key: 'radius_amount', label: 'Border Radius (px)', type: 'range', value: 12, min: 0, max: 24 },
            { key: 'storage_unit', label: 'Storage Unit', type: 'select', value: 'auto', options: [
                { label: 'Auto', value: 'auto' },
                { label: 'MB', value: 'mb' },
                { label: 'GB', value: 'gb' },
            ]},
        ],
    },
    {
        id: 'user-personalization',
        title: 'User Personalization',
        description: 'Choose which theme controls users can manage from their account',
        icon: 'fa-user-circle',
        settings: [
            { key: 'allow_user_colors', label: 'Allow User Colors', type: 'toggle', value: 'true' },
            { key: 'allow_user_background', label: 'Allow User Background', type: 'toggle', value: 'true' },
            { key: 'allow_user_notifications', label: 'Allow User Notifications', type: 'toggle', value: 'true' },
            { key: 'allow_user_privacy', label: 'Allow User Privacy', type: 'toggle', value: 'true' },
        ],
    },
];

export default function HyperSettings() {
    const [categories, setCategories] = useState<SettingCategory[]>(defaultCategories);
    const [activeCategory, setActiveCategory] = useState<string | null>(null);
    const [hasChanges, setHasChanges] = useState(false);
    const [saving, setSaving] = useState(false);
    const [viewMode, setViewMode] = useState<'grid' | 'list'>('grid');

    useEffect(() => {
        fetch('/api/admin/bsdk/settings')
            .then(r => r.json())
            .then(data => {
                if (data.settings) {
                    setCategories(prev => prev.map(cat => ({
                        ...cat,
                        settings: cat.settings.map(s => ({
                            ...s,
                            value: data.settings[s.key] ?? s.value,
                        })),
                    })));
                }
            })
            .catch(() => {});
    }, []);

    const updateSetting = useCallback((catId: string, key: string, value: any) => {
        setCategories(prev => prev.map(cat =>
            cat.id === catId
                ? { ...cat, settings: cat.settings.map(s => s.key === key ? { ...s, value } : s) }
                : cat
        ));
        setHasChanges(true);
    }, []);

    const saveSettings = async () => {
        setSaving(true);
        const settings: Record<string, string> = {};
        categories.forEach(cat => cat.settings.forEach(s => { settings[s.key] = String(s.value); }));
        try {
            await fetch('/api/admin/bsdk/settings', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': (document.querySelector('meta[name="csrf-token"]') as HTMLMetaElement)?.content || '' },
                body: JSON.stringify({ settings }),
            });
            setHasChanges(false);
        } catch (e) { console.error(e); }
        setSaving(false);
    };

    const resetSettings = () => {
        if (confirm('Reset all settings to defaults?')) {
            setCategories(defaultCategories);
            setHasChanges(true);
        }
    };

    const exportSettings = () => {
        const settings: Record<string, string> = {};
        categories.forEach(cat => cat.settings.forEach(s => { settings[s.key] = String(s.value); }));
        const blob = new Blob([JSON.stringify(settings, null, 2)], { type: 'application/json' });
        const url = URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url; a.download = 'bsdk-hyper-settings.json'; a.click();
        URL.revokeObjectURL(url);
    };

    const importSettings = () => {
        const input = document.createElement('input');
        input.type = 'file'; input.accept = '.json';
        input.onchange = (e: any) => {
            const file = e.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (ev) => {
                try {
                    const imported = JSON.parse(ev.target?.result as string);
                    setCategories(prev => prev.map(cat => ({
                        ...cat,
                        settings: cat.settings.map(s => ({
                            ...s,
                            value: imported[s.key] ?? s.value,
                        })),
                    })));
                    setHasChanges(true);
                } catch (err) { alert('Invalid JSON file'); }
            };
            reader.readAsText(file);
        };
        input.click();
    };

    const renderSettingField = (cat: SettingCategory, field: SettingField) => {
        switch (field.type) {
            case 'color':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <div className="hyper-color-input">
                            <input type="color" value={String(field.value)} onChange={e => updateSetting(cat.id, field.key, e.target.value)} />
                            <input type="text" value={String(field.value)} onChange={e => updateSetting(cat.id, field.key, e.target.value)} />
                        </div>
                    </div>
                );
            case 'toggle':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <button
                            className={`hyper-toggle ${field.value === 'true' ? 'active' : ''}`}
                            onClick={() => updateSetting(cat.id, field.key, field.value === 'true' ? 'false' : 'true')}
                        >
                            <span className="hyper-toggle-slider" />
                        </button>
                    </div>
                );
            case 'select':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <select value={String(field.value)} onChange={e => updateSetting(cat.id, field.key, e.target.value)}>
                            {field.options?.map(opt => <option key={opt.value} value={opt.value}>{opt.label}</option>)}
                        </select>
                    </div>
                );
            case 'range':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}: {field.value}px</label>
                        <input type="range" min={field.min} max={field.max} value={Number(field.value)}
                            onChange={e => updateSetting(cat.id, field.key, Number(e.target.value))} />
                    </div>
                );
            case 'textarea':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <textarea value={String(field.value)} rows={3}
                            onChange={e => updateSetting(cat.id, field.key, e.target.value)} />
                    </div>
                );
            case 'number':
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <input type="number" min={field.min} max={field.max} value={Number(field.value)}
                            onChange={e => updateSetting(cat.id, field.key, Number(e.target.value))} />
                    </div>
                );
            default:
                return (
                    <div key={field.key} className="hyper-setting-field">
                        <label>{field.label}</label>
                        <input type="text" value={String(field.value)}
                            onChange={e => updateSetting(cat.id, field.key, e.target.value)} />
                    </div>
                );
        }
    };

    return (
        <div className="hyper-settings">
            <div className="hyper-settings-header">
                <div>
                    <h1><i className="fa fa-magic" /> Hyper Settings</h1>
                    <p>Customize every aspect of your panel</p>
                </div>
                <div className="hyper-settings-view-toggle">
                    <button className={viewMode === 'grid' ? 'active' : ''} onClick={() => setViewMode('grid')}><i className="fa fa-th-large" /></button>
                    <button className={viewMode === 'list' ? 'active' : ''} onClick={() => setViewMode('list')}><i className="fa fa-list" /></button>
                </div>
            </div>

            {activeCategory ? (
                <div className="hyper-settings-detail">
                    <button className="hyper-back-btn" onClick={() => setActiveCategory(null)}>
                        <i className="fa fa-arrow-left" /> Back to Settings
                    </button>
                    <h2><i className={`fa ${categories.find(c => c.id === activeCategory)?.icon}`} /> {categories.find(c => c.id === activeCategory)?.title}</h2>
                    <p className="hyper-settings-detail-desc">{categories.find(c => c.id === activeCategory)?.description}</p>
                    <div className="hyper-settings-fields">
                        {categories.find(c => c.id === activeCategory)?.settings.map(s => renderSettingField(categories.find(c => c.id === activeCategory)!, s))}
                    </div>
                </div>
            ) : (
                <div className={`hyper-settings-grid ${viewMode}`}>
                    {categories.map(cat => (
                        <div key={cat.id} className="hyper-settings-card" onClick={() => setActiveCategory(cat.id)}>
                            <div className="hyper-settings-card-icon"><i className={`fa ${cat.icon}`} /></div>
                            <h3>{cat.title}</h3>
                            <p>{cat.description}</p>
                            <button className="hyper-settings-card-btn"><i className="fa fa-cog" /> Manage</button>
                        </div>
                    ))}
                </div>
            )}

            <div className="hyper-settings-footer">
                <span className={`hyper-save-status ${hasChanges ? 'unsaved' : ''}`}>
                    <i className={`fa ${hasChanges ? 'fa-exclamation-circle' : 'fa-check-circle'}`} />
                    {hasChanges ? 'Unsaved Changes' : 'All Changes Saved'}
                </span>
                <div className="hyper-settings-actions">
                    <button className="hyper-btn danger" onClick={resetSettings}><i className="fa fa-undo" /> Reset</button>
                    <button className="hyper-btn secondary" onClick={() => setHasChanges(false)}><i className="fa fa-times" /> Discard</button>
                    <button className="hyper-btn secondary" onClick={exportSettings}><i className="fa fa-download" /> Export</button>
                    <button className="hyper-btn secondary" onClick={importSettings}><i className="fa fa-upload" /> Import</button>
                    <button className="hyper-btn primary" onClick={saveSettings} disabled={!hasChanges || saving}>
                        <i className={`fa ${saving ? 'fa-spinner fa-spin' : 'fa-save'}`} /> Save
                    </button>
                </div>
            </div>
        </div>
    );
}
