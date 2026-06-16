# BSDK V1 — Premium Pterodactyl Theme

Full dark gaming theme for Pterodactyl Panel with plugin/mod installer.

![BSDK V1](https://img.shields.io/badge/BSDK-V1-00d4ff?style=for-the-badge) ![Made by Akshit](https://img.shields.io/badge/Made%20by-Akshit-00ff88?style=for-the-badge)

## Quick Install

```bash
git clone https://github.com/prplwtf/Nebula.git
cd Nebula
chmod +x install.sh
sudo bash install.sh
```

The installer gives you a menu:

```
╔════════════════════════════════════════════════════════╗
║                   MAIN MENU                           ║
╠════════════════════════════════════════════════════════╣
║  1) Full Install      Blueprint + BSDK V1 Theme      ║
║  2) Theme Only        Install theme on existing BP    ║
║  3) Reinstall         Clean reinstall of theme        ║
║  4) Update Theme      Fetch & apply latest            ║
║  5) Update Panel      Update Pterodactyl + rebuild    ║
║  6) Uninstall         Remove theme, keep panel        ║
║  7) System Check      Verify requirements             ║
║  0) Exit                                                ║
╚════════════════════════════════════════════════════════╝
```

## Features

### Theme
- Dark gaming aesthetic with glassmorphism
- CSS variable system — every color/font/spacing customizable
- Admin settings panel with 7 tabs
- Theme export/import as JSON
- Particle background on login
- Custom CSS/JS injection
- 30+ animations (fade, slide, glow, float, scale)
- Neon glow effects on hover
- Gradient text and buttons
- Glassmorphism cards
- Responsive mobile support

### Plugin/Mod Installer
- **5 providers:** Modrinth, CurseForge, SpigotMC, Hangar, Polymart
- **6 categories:** Plugins, Mods, Modpacks, Resource Packs, Shaders
- One-click install to correct server directories
- View and remove installed plugins
- Search with filters (version, loader, provider)

### Admin Settings (7 Tabs)
| Tab | Settings |
|-----|----------|
| Colors | Primary, secondary, accent, danger, warning, text, borders |
| Backgrounds | Page, card, elevated, login background |
| Typography | 10 font families, 5 monospace fonts |
| Layout | Border radius, sidebar, card style, button style |
| Branding | Panel name, tagline, logo, favicon |
| Effects | Glow, animations, gradients, particles |
| Advanced | Custom CSS, custom JS, export/import |

## API

| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/client/servers/{id}/installer/search` | Search plugins |
| POST | `/api/client/servers/{id}/installer/install` | Install plugin |
| GET | `/api/client/servers/{id}/installer/installed` | List installed |
| DELETE | `/api/client/servers/{id}/installer/remove` | Remove plugin |

## File Structure

```
BSDK-V1/
├── install.sh                        # Installer
├── conf.yml                          # Blueprint manifest
├── admin/
│   ├── Controller.php                # Settings controller
│   ├── view.blade.php                # Admin UI (7 tabs)
│   ├── admin.css                     # Admin overrides
│   └── wrapper.blade.php             # Admin CSS vars
├── dashboard/
│   ├── theme.css                     # Full theme
│   ├── wrapper.blade.php             # Dashboard CSS vars
│   └── components/                   # React plugin browser
├── app/                              # Backend controllers
├── routes/                           # API routes
├── database/migrations/              # Settings table
├── data/public/assets/               # Logo, backgrounds
├── icons/                            # 10 icon font sets
└── modules/                          # JSFrame, marked.js
```

## Made by Akshit
