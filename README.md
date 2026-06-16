# BSDK V1 — Premium Pterodactyl Theme

Standalone dark gaming theme for Pterodactyl. No Blueprint. Direct panel modification.

Made by **Akshit**.

## Install (One-Line)

```bash
bash <(curl -s https://raw.githubusercontent.com/CodeByCruel/theme/main/install.sh)
```

Or with wget:

```bash
bash <(wget -qO- https://raw.githubusercontent.com/CodeByCruel/theme/main/install.sh)
```

## Alternative Install (Manual)

```bash
git clone https://github.com/CodeByCruel/theme.git
cd theme
sudo bash install.sh
```

## Menu Options

| Option | Description |
|--------|-------------|
| 1 | **Install Theme** — Full install with backup |
| 2 | **Reinstall** — Clean reinstall (restores backup first) |
| 3 | **Rebuild** — Rebuild after config change |
| 4 | **Uninstall** — Remove theme, restore original files |
| 5 | **System Check** — Verify requirements |
| 0 | Exit |

## What It Does

The installer:
1. Backs up your current panel files
2. Replaces `tailwind.config.js` with custom colors
3. Adds `custom.css` + `hyper-components.css` with full theme
4. Replaces login page, navigation, server cards (React components)
5. Replaces master blade layout with DB settings + CSS var injection
6. Installs plugin/mod installer API
7. Installs public assets (logo, background, favicon, DGEN, fonts)
8. Installs 15+ admin/user blade pages
9. Installs 3 email templates
10. Installs PWA manifest
11. Runs database migration
12. Builds frontend assets
13. Clears all caches

## Pages Included

**Admin Panel:**
- `/admin/bsdk-theme` — Theme Settings (10 presets, 12 colors, fonts, features)
- `/admin/bsd-settings` — BSD Settings (11 categories, export/import)
- `/admin/addon-settings` — Addon Settings (46 addons, toggle)
- `/admin/servers` — Server Management
- `/admin/users` — User Management
- `/admin/nodes` — Node Management

**User Account:**
- `/account` — Account Settings (profile, password, 2FA, language, timezone)
- `/account/api` — API Credentials (create, revoke)
- `/account/ssh` — SSH Keys (add, delete)
- `/account/activity` — Activity Log (timeline, filters)

**User Pages:**
- `/staff-request` — Staff Request System
- `/notifications` — Notifications

**Standalone:**
- `/login` — Login Page (particles, glassmorphism)
- `/manifest.json` — PWA Manifest

**Email Templates:**
- Password Reset
- Server Created
- Server Suspended

## Features

**Theme:**
- Dark gaming aesthetic with Hyper-inspired design
- Glassmorphism cards
- Neon glow effects
- Gradient text and buttons
- Particle background on login
- 30+ CSS animations
- Custom scrollbar
- Responsive mobile support
- DGEN assets (server banners, cards, backgrounds)

**Plugin/Mod Installer:**
- Modrinth, CurseForge, SpigotMC, Hangar
- Plugins, Mods, Resource Packs, Shaders
- One-click install to server

**Settings API:**
- GET/POST `/api/admin/bsdk/settings` — BSD Settings
- POST `/api/admin/bsdk/settings/reset` — Reset to defaults
- GET `/api/admin/bsdk/addons` — Addon list
- POST `/api/admin/bsdk/addons/{id}/toggle` — Toggle addon

## Customization

Edit `storage/app/bsdk-theme.json` then rebuild:
```bash
sudo bash install.sh --rebuild
```

Or use the admin UI at `/admin/bsdk-theme` for runtime changes (no rebuild needed).

## Files Modified

| Original File | Change |
|---------------|--------|
| `tailwind.config.js` | New color palette |
| `resources/scripts/index.tsx` | Import custom.css |
| `resources/scripts/custom.css` | Full theme |
| `resources/scripts/hyper-components.css` | Hyper component styles |
| `resources/scripts/components/auth/LoginContainer.tsx` | Custom login |
| `resources/scripts/components/NavigationBar.tsx` | Custom nav |
| `resources/scripts/components/server/ServerCard.tsx` | Custom cards |
| `resources/views/layouts/master.blade.php` | Custom layout |
| `resources/views/admin/*.blade.php` | 6 admin pages |
| `resources/views/account/*.blade.php` | 4 user pages |
| `resources/views/emails/*.blade.php` | 3 email templates |
| `resources/views/auth/login.blade.php` | Standalone login |
| `app/Http/Controllers/Admin/BsdkThemeController.php` | Page controllers |
| `app/Http/Controllers/Api/Admin/BsdkSettingsController.php` | Settings API |
| `app/Http/Controllers/Api/Admin/BsdkAddonController.php` | Addons API |
| `routes/bsdk-routes.php` | All routes |

## Uninstall

```bash
sudo bash install.sh --uninstall
```

Or run the installer and select option 4 from the menu.

Restores from backup.

## System Requirements

- Pterodactyl Panel (latest)
- PHP 8.1+
- Node.js 18+
- Yarn
- 2GB+ free disk space

## Made by Akshit
