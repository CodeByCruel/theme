# BSDK V1 — Premium Pterodactyl Theme

Standalone dark gaming theme for Pterodactyl. No Blueprint. Direct panel modification.

Made by **Akshit**.

## Install

```bash
git clone https://github.com/CodeByCruel/theme.git
cd theme
sudo bash install.sh
```

## What It Does

The installer:
1. Backs up your current panel files
2. Replaces `tailwind.config.js` with custom colors
3. Adds `custom.css` with full theme
4. Replaces login page, navigation, server cards
5. Replaces master blade layout
6. Adds plugin/mod installer API
7. Installs public assets (logo, background, favicon)
8. Builds frontend assets
9. Clears caches

## Features

**Theme:**
- Dark gaming aesthetic
- Glassmorphism cards
- Neon glow effects
- Gradient text and buttons
- Particle background on login
- 30+ CSS animations
- Custom scrollbar
- Responsive mobile support

**Plugin/Mod Installer:**
- Modrinth, CurseForge, SpigotMC, Hangar
- Plugins, Mods, Resource Packs, Shaders
- One-click install to server

**Customization:**
Edit `storage/app/bsdk-theme.json` then rebuild:
```bash
sudo bash install.sh --rebuild
```

## Files Modified

| Original File | Change |
|---------------|--------|
| `tailwind.config.js` | New color palette |
| `resources/scripts/index.tsx` | Import custom.css |
| `resources/scripts/custom.css` | Full theme |
| `resources/scripts/components/auth/LoginContainer.tsx` | Custom login |
| `resources/scripts/components/NavigationBar.tsx` | Custom nav |
| `resources/scripts/components/server/ServerCard.tsx` | Custom cards |
| `resources/views/layouts/master.blade.php` | Custom layout |

## Uninstall

```bash
sudo bash install.sh --uninstall
```

Restores from backup.

## Made by Akshit
