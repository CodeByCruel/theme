<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class BsdkAddonController extends Controller
{
    private function getAddonDefinitions(): array
    {
        return [
            ['id' => 'manage-addons', 'name' => 'Manage Addons', 'description' => 'Install, configure, or uninstall addons from the BSDK Store', 'icon' => 'fa-puzzle-piece', 'category' => 'core'],
            ['id' => 'account-info', 'name' => 'Account Info Update', 'description' => 'Allow users to update their account information', 'icon' => 'fa-user', 'category' => 'user'],
            ['id' => 'ads-layout', 'name' => 'Ads Layout', 'description' => 'Display advertisements on the panel layout', 'icon' => 'fa-bullhorn', 'category' => 'layout'],
            ['id' => 'auto-suspend', 'name' => 'Auto Suspend', 'description' => 'Automatically suspend inactive servers', 'icon' => 'fa-clock-o', 'category' => 'server'],
            ['id' => 'cmd-history', 'name' => 'Command History', 'description' => 'Track and display command history on servers', 'icon' => 'fa-history', 'category' => 'server'],
            ['id' => 'config-editor', 'name' => 'Config Editor', 'description' => 'Enhanced configuration file editor', 'icon' => 'fa-code', 'category' => 'server'],
            ['id' => 'console-log', 'name' => 'Console Log Upload', 'description' => 'Upload console logs to external services', 'icon' => 'fa-cloud-upload', 'category' => 'server'],
            ['id' => 'custom-mod', 'name' => 'Custom Mod Manager', 'description' => 'Install and manage custom mods from multiple sources', 'icon' => 'fa-cube', 'category' => 'minecraft'],
            ['id' => 'database-manager', 'name' => 'Database Manager', 'description' => 'Advanced database management tools', 'icon' => 'fa-database', 'category' => 'server'],
            ['id' => 'ddos-alert', 'name' => 'DDOS Alert', 'description' => 'Receive alerts when DDoS is detected', 'icon' => 'fa-shield', 'category' => 'security'],
            ['id' => 'demo-mode', 'name' => 'Demo Mode', 'description' => 'Enable demo mode for the panel', 'icon' => 'fa-eye', 'category' => 'layout'],
            ['id' => 'direct-upload', 'name' => 'Direct Folder Upload', 'description' => 'Upload entire folders directly to servers', 'icon' => 'fa-folder-open', 'category' => 'server'],
            ['id' => 'discord-bot', 'name' => 'Discord Bot', 'description' => 'Integrate Discord bot for server management', 'icon' => 'fa-comment', 'category' => 'integration'],
            ['id' => 'fastdl', 'name' => 'FastDL Manager', 'description' => 'Manage FastDL for fast downloads', 'icon' => 'fa-download', 'category' => 'server'],
            ['id' => 'firewall', 'name' => 'Firewall Manager', 'description' => 'Manage server firewall rules', 'icon' => 'fa-lock', 'category' => 'security'],
            ['id' => 'fivem-utils', 'name' => 'FiveM Utils', 'description' => 'FiveM server management utilities', 'icon' => 'fa-car', 'category' => 'fivem'],
            ['id' => 'github-scm', 'name' => 'GitHub Source Control', 'description' => 'Connect servers to GitHub repositories', 'icon' => 'fa-github', 'category' => 'integration'],
            ['id' => 'i18n', 'name' => 'Language Translations', 'description' => 'Multi-language support for the panel', 'icon' => 'fa-globe', 'category' => 'layout'],
            ['id' => 'login-as', 'name' => 'Login As User', 'description' => 'Admin can log in as any user', 'icon' => 'fa-sign-in', 'category' => 'admin'],
            ['id' => 'login-activity', 'name' => 'Login Activity', 'description' => 'Track user login activity', 'icon' => 'fa-list', 'category' => 'security'],
            ['id' => 'mc-bedrock-addons', 'name' => 'Minecraft Bedrock Addons', 'description' => 'Install Bedrock addons', 'icon' => 'fa-cube', 'category' => 'minecraft'],
            ['id' => 'mc-bedrock-maps', 'name' => 'Minecraft Bedrock Maps', 'description' => 'Install Bedrock maps', 'icon' => 'fa-map', 'category' => 'minecraft'],
            ['id' => 'mc-bedrock-packs', 'name' => 'Minecraft Bedrock Packs', 'description' => 'Install Bedrock resource/behavior packs', 'icon' => 'fa-archive', 'category' => 'minecraft'],
            ['id' => 'mc-bedrock-scripts', 'name' => 'Minecraft Bedrock Scripts', 'description' => 'Install Bedrock script extensions', 'icon' => 'fa-file-code-o', 'category' => 'minecraft'],
            ['id' => 'mc-bedrock-versions', 'name' => 'Minecraft Bedrock Versions', 'description' => 'Switch Bedrock server versions', 'icon' => 'fa-code-fork', 'category' => 'minecraft'],
            ['id' => 'mc-config', 'name' => 'Minecraft Configuration', 'description' => 'Enhanced Minecraft server configuration', 'icon' => 'fa-wrench', 'category' => 'minecraft'],
            ['id' => 'mc-icon', 'name' => 'Minecraft Server Icon', 'description' => 'Custom server icon manager', 'icon' => 'fa-picture-o', 'category' => 'minecraft'],
            ['id' => 'mc-mod', 'name' => 'Minecraft Mod Installer', 'description' => 'Install Minecraft mods from Modrinth/CurseForge', 'icon' => 'fa-cube', 'category' => 'minecraft'],
            ['id' => 'mc-modpack', 'name' => 'Minecraft Modpack Installer', 'description' => 'Install modpacks from CurseForge/Modrinth', 'icon' => 'fa-archive', 'category' => 'minecraft'],
            ['id' => 'mc-motd', 'name' => 'Minecraft MOTD', 'description' => 'Customize server MOTD', 'icon' => 'fa-commenting', 'category' => 'minecraft'],
            ['id' => 'mc-player', 'name' => 'Minecraft Player Manager', 'description' => 'Manage players, whitelist, OP', 'icon' => 'fa-users', 'category' => 'minecraft'],
            ['id' => 'mc-plugin', 'name' => 'Minecraft Plugin Installer', 'description' => 'Install plugins from SpigotMC/PaperMC', 'icon' => 'fa-puzzle-piece', 'category' => 'minecraft'],
            ['id' => 'mc-version', 'name' => 'Minecraft Version Changer', 'description' => 'Switch Minecraft server versions', 'icon' => 'fa-exchange', 'category' => 'minecraft'],
            ['id' => 'mc-world', 'name' => 'Minecraft World Manager', 'description' => 'Upload, download, and manage worlds', 'icon' => 'fa-globe', 'category' => 'minecraft'],
            ['id' => 'mc-votifier', 'name' => 'Minecraft Votifier', 'description' => 'Votifier integration for vote rewards', 'icon' => 'fa-thumbs-up', 'category' => 'minecraft'],
            ['id' => 'network-stats', 'name' => 'Network Statistics', 'description' => 'View network-wide server statistics', 'icon' => 'fa-bar-chart', 'category' => 'admin'],
            ['id' => 'notifications', 'name' => 'Notifications', 'description' => 'System notifications for admins and users', 'icon' => 'fa-bell', 'category' => 'core'],
            ['id' => 'player-manager', 'name' => 'Player Manager', 'description' => 'General player management', 'icon' => 'fa-user', 'category' => 'server'],
            ['id' => 'recycle-bin', 'name' => 'Recycle Bin', 'description' => 'Recover deleted servers and users', 'icon' => 'fa-trash', 'category' => 'admin'],
            ['id' => 'reverse-proxy', 'name' => 'Reverse Proxy', 'description' => 'Manage reverse proxy configurations', 'icon' => 'fa-random', 'category' => 'server'],
            ['id' => 'server-import', 'name' => 'Server Importer', 'description' => 'Import external servers', 'icon' => 'fa-upload', 'category' => 'admin'],
            ['id' => 'server-split', 'name' => 'Server Splitter', 'description' => 'Split servers into multiple nodes', 'icon' => 'fa-split', 'category' => 'admin'],
            ['id' => 'server-wipe', 'name' => 'Server Wiper', 'description' => 'Wipe server data completely', 'icon' => 'fa-eraser', 'category' => 'admin'],
            ['id' => 'staff-request', 'name' => 'Staff Request', 'description' => 'Allow users to submit staff requests', 'icon' => 'fa-life-ring', 'category' => 'core'],
            ['id' => 'subdomain', 'name' => 'Subdomain Manager', 'description' => 'Create and manage subdomains', 'icon' => 'fa-link', 'category' => 'server'],
            ['id' => 'theme-settings', 'name' => 'Theme Settings', 'description' => 'Customize the panel theme and appearance', 'icon' => 'fa-paint-brush', 'category' => 'layout'],
            ['id' => 'pwa', 'name' => 'Progressive Web App', 'description' => 'Enable PWA support for mobile devices', 'icon' => 'fa-mobile', 'category' => 'layout'],
            ['id' => 'arma-reforger', 'name' => 'ARMA Reforger', 'description' => 'ARMA Reforger server management', 'icon' => 'fa-shield', 'category' => 'games'],
            ['id' => 'privacy-blur', 'name' => 'Privacy Blur', 'description' => 'Blur sensitive information on screenshots', 'icon' => 'fa-eye-slash', 'category' => 'security'],
            ['id' => 'winter-mode', 'name' => 'Winter Mode', 'description' => 'Seasonal winter theme with snow effects', 'icon' => 'fa-snowflake-o', 'category' => 'layout'],
            ['id' => 'nav-bar', 'name' => 'Navigation Bar', 'description' => 'Custom navigation bar with floating mode', 'icon' => 'fa-bars', 'category' => 'layout'],
            ['id' => 'branding', 'name' => 'Branding Customization', 'description' => 'Full branding control: name, tagline, logos', 'icon' => 'fa-tag', 'category' => 'layout'],
        ];
    }

    public function index()
    {
        $addons = $this->getAddonDefinitions();

        try {
            if (Schema::hasTable('bsdkv1_settings')) {
                $saved = DB::table('bsdkv1_settings')
                    ->where('setting_key', 'like', 'addon_%')
                    ->get();
                $enabledMap = [];
                foreach ($saved as $row) {
                    $addonId = str_replace('addon_', '', $row->setting_key);
                    $enabledMap[$addonId] = $row->setting_value === 'true';
                }
            } else {
                $enabledMap = [];
            }
        } catch (\Exception $e) {
            $enabledMap = [];
        }

        foreach ($addons as &$addon) {
            $addon['enabled'] = $enabledMap[$addon['id']] ?? false;
        }

        return response()->json($addons);
    }

    public function toggle(Request $request, string $id)
    {
        $enabled = $request->input('enabled', true);

        DB::table('bsdkv1_settings')->updateOrInsert(
            ['setting_key' => "addon_{$id}"],
            ['setting_value' => $enabled ? 'true' : 'false', 'updated_at' => now()]
        );

        return response()->json(['success' => true, 'id' => $id, 'enabled' => $enabled]);
    }

    public function bulkToggle(Request $request)
    {
        $addons = $request->input('addons', []);

        foreach ($addons as $id => $enabled) {
            DB::table('bsdkv1_settings')->updateOrInsert(
                ['setting_key' => "addon_{$id}"],
                ['setting_value' => $enabled ? 'true' : 'false', 'updated_at' => now()]
            );
        }

        return response()->json(['success' => true, 'message' => 'Addons updated']);
    }
}
