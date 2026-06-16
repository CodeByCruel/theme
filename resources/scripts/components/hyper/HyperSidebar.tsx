import React, { useState, useCallback } from 'react';
import { useLocation, useHistory } from 'react-router-dom';

interface SidebarItem {
    label: string;
    icon: string;
    path?: string;
    children?: SidebarItem[];
    badge?: string;
}

const sidebarItems: SidebarItem[] = [
    { label: 'Dashboard', icon: 'fa-th-large', path: '/dashboard' },
    { label: 'Discord', icon: 'fa-comment', path: '/discord' },
    { label: 'Status', icon: 'fa-heartbeat', path: '/status' },
    {
        label: 'Account Settings', icon: 'fa-user', children: [
            { label: 'Account', icon: 'fa-user', path: '/account' },
            { label: 'Api Credentials', icon: 'fa-key', path: '/account/api' },
            { label: 'Ssh Keys', icon: 'fa-terminal', path: '/account/ssh' },
            { label: 'Activity', icon: 'fa-clock-o', path: '/account/activity' },
        ],
    },
    {
        label: 'Panel Settings', icon: 'fa-cog', children: [
            { label: 'Panel Settings', icon: 'fa-cog', path: '/admin/settings' },
            { label: 'BSD Settings', icon: 'fa-magic', path: '/admin/bsd-settings' },
            { label: 'Addon Settings', icon: 'fa-puzzle-piece', path: '/admin/addon-settings' },
        ],
    },
    { label: 'Staff Request', icon: 'fa-users', path: '/staff-request' },
];

const managementItems: SidebarItem[] = [
    { label: 'File Manager', icon: 'fa-folder', path: '/server/{id}/files' },
    { label: 'Databases', icon: 'fa-database', path: '/server/{id}/databases' },
    { label: 'Backups', icon: 'fa-archive', path: '/server/{id}/backups' },
    { label: 'Network', icon: 'fa-globe', path: '/server/{id}/network' },
    { label: 'Subdomain', icon: 'fa-sitemap', path: '/server/{id}/subdomain' },
    { label: 'Staff Request', icon: 'fa-users', path: '/server/{id}/staff-request' },
    { label: 'Server Importer', icon: 'fa-download', path: '/server/{id}/importer' },
    { label: 'Custom Mod Manager', icon: 'fa-puzzle-piece', path: '/server/{id}/custom-mods' },
    { label: 'Server Splitter', icon: 'fa-code-fork', path: '/server/{id}/splitter' },
    { label: 'Server Wiper', icon: 'fa-trash', path: '/server/{id}/wiper' },
    { label: 'Reverse Proxy', icon: 'fa-exchange', path: '/server/{id}/reverse-proxy' },
];

const configItems: SidebarItem[] = [
    { label: 'Schedules', icon: 'fa-clock-o', path: '/server/{id}/schedules' },
    { label: 'Users', icon: 'fa-users', path: '/server/{id}/users' },
    { label: 'Startup', icon: 'fa-rocket', path: '/server/{id}/startup' },
    { label: 'Config Editor', icon: 'fa-pencil', path: '/server/{id}/config-editor' },
];

const minecraftItems: SidebarItem[] = [
    { label: 'Configuration', icon: 'fa-cog', path: '/server/{id}/mc/config' },
    { label: 'Version Changer', icon: 'fa-refresh', path: '/server/{id}/mc/versions' },
    { label: 'Plugin Installer', icon: 'fa-puzzle-piece', path: '/server/{id}/mc/plugins' },
    { label: 'Mod Installer', icon: 'fa-download', path: '/server/{id}/mc/mods' },
    { label: 'Modpack Installer', icon: 'fa-box', path: '/server/{id}/mc/modpacks' },
    { label: 'World Manager', icon: 'fa-globe', path: '/server/{id}/mc/worlds' },
    { label: 'Bedrock Addon Installer', icon: 'fa-puzzle-piece', path: '/server/{id}/mc/bedrock-addons' },
    { label: 'Bedrock Version Changer', icon: 'fa-refresh', path: '/server/{id}/mc/bedrock-versions' },
    { label: 'Player Manager', icon: 'fa-users', path: '/server/{id}/mc/players' },
    { label: 'Votifier Tester', icon: 'fa-check', path: '/server/{id}/mc/votifier' },
];

const armaItems: SidebarItem[] = [
    { label: 'Mod Manager', icon: 'fa-download', path: '/server/{id}/arma/mods' },
    { label: 'Config Editor', icon: 'fa-pencil', path: '/server/{id}/arma/config' },
    { label: 'Admin Tools', icon: 'fa-wrench', path: '/server/{id}/arma/admin' },
];

const fivemItems: SidebarItem[] = [
    { label: 'FiveM Utils', icon: 'fa-wrench', path: '/server/{id}/fivem/utils' },
];

interface HyperSidebarProps {
    collapsed?: boolean;
    onToggle?: () => void;
    serverMode?: boolean;
    serverId?: string;
}

export default function HyperSidebar({ collapsed = false, onToggle, serverMode = false, serverId }: HyperSidebarProps) {
    const location = useLocation();
    const history = useHistory();
    const [openSections, setOpenSections] = useState<Record<string, boolean>>({
        'Account Settings': false,
        'Panel Settings': true,
        'Management': true,
        'Configuration': false,
        'Minecraft': false,
        'Arma Reforger': false,
        'FiveM': false,
    });

    const toggleSection = useCallback((label: string) => {
        setOpenSections(prev => ({ ...prev, [label]: !prev[label] }));
    }, []);

    const isActive = (path?: string) => {
        if (!path) return false;
        return location.pathname === path || location.pathname.startsWith(path + '/');
    };

    const renderItem = (item: SidebarItem, depth = 0) => {
        const hasChildren = item.children && item.children.length > 0;
        const isOpen = openSections[item.label];
        const active = isActive(item.path);

        if (hasChildren) {
            return (
                <div key={item.label}>
                    <button
                        onClick={() => toggleSection(item.label)}
                        className={`hyper-sidebar-item ${isOpen ? 'open' : ''}`}
                        style={{ paddingLeft: `${12 + depth * 16}px` }}
                    >
                        <i className={`fa ${item.icon}`} />
                        {!collapsed && <span>{item.label}</span>}
                        {!collapsed && (
                            <i className={`fa fa-chevron-${isOpen ? 'down' : 'right'} hyper-sidebar-arrow`} />
                        )}
                    </button>
                    {isOpen && !collapsed && (
                        <div className="hyper-sidebar-children">
                            {item.children!.map(child => renderItem(child, depth + 1))}
                        </div>
                    )}
                </div>
            );
        }

        return (
            <button
                key={item.label}
                onClick={() => item.path && history.push(item.path)}
                className={`hyper-sidebar-item ${active ? 'active' : ''}`}
                style={{ paddingLeft: `${12 + depth * 16}px` }}
            >
                <i className={`fa ${item.icon}`} />
                {!collapsed && <span>{item.label}</span>}
                {item.badge && !collapsed && <span className="hyper-sidebar-badge">{item.badge}</span>}
            </button>
        );
    };

    const items = serverMode ? [...managementItems, ...configItems, ...minecraftItems, ...armaItems, ...fivemItems] : sidebarItems;

    return (
        <div className={`hyper-sidebar ${collapsed ? 'collapsed' : 'expanded'}`}>
            <div className="hyper-sidebar-brand">
                {!collapsed && <span className="hyper-sidebar-brand-text">{window.BSDK?.name || 'BSDK Panel'}</span>}
            </div>
            <div className="hyper-sidebar-menu">
                {items.map(item => renderItem(item))}
            </div>
        </div>
    );
}
