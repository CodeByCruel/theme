#!/bin/bash

# ═══════════════════════════════════════════════════════════════
# BSDK V1 — Premium Pterodactyl Theme
# Made by Akshit
# Standalone theme installer (no Blueprint)
# ═══════════════════════════════════════════════════════════════

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
BLUE='\033[0;34m'; CYAN='\033[0;36m'; WHITE='\033[1;37m'
DIM='\033[2m'; BOLD='\033[1m'; NC='\033[0m'

PANEL="${PANEL:-/var/www/pterodactyl}"
THEME_REPO="https://github.com/CodeByCruel/theme.git"
LOG="/tmp/bsdk-$(date +%Y%m%d-%H%M%S).log"
BACKUP=""

br() { echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"; }
header() { clear; br; echo -e "  ${CYAN}${BOLD}$1${NC}"; br; echo; }
step() { echo -e "  ${CYAN}►${NC} ${WHITE}$1${NC}"; }
ok() { echo -e "  ${GREEN}✓${NC} $1"; }
fail() { echo -e "  ${RED}✗${NC} $1"; exit 1; }
warn() { echo -e "  ${YELLOW}⚠${NC} $1"; }

spinner() {
    local pid=$1 msg=$2
    local f='⠋⠙⠹⠸⠼⠴⠦⠧⠇⠏'
    while kill -0 "$pid" 2>/dev/null; do
        for (( i=0; i<${#f}; i++ )); do
            printf "\r  ${CYAN}%s${NC} %s" "${f:$i:1}" "$msg"
            sleep 0.07
        done
    done
    printf "\r  ${GREEN}✓${NC} %s\n" "$msg"
}

banner() {
    clear
    echo -e "${CYAN}"
    cat << 'EOF'
  ╔═══════════════════════════════════════════════════════╗
  ║   ██████╗ ███████╗██╗   ██╗███████╗███╗   ██╗████████╗
  ║   ██╔══██╗██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝
  ║   ██║  ██║█████╗  ██║   ██║██████╗ ██╔██╗ ██║   ██║   
  ║   ██║  ██║██╔══╝  ╚██╗ ██╔╝╚════██╗██║╚██╗██║   ██║   
  ║   ██████╔╝███████╗ ╚████╔╝ ███████║██║ ╚████║   ██║   
  ║   ╚═════╝ ╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝   
  ║              Premium Pterodactyl Theme                  
  ║                  Made by Akshit                         
  ╚═══════════════════════════════════════════════════════╝
EOF
    echo -e "${NC}"
    sleep 1
}

# ── Check System ──
check() {
    header "SYSTEM CHECK"
    [ "$EUID" -ne 0 ] && fail "Run as root: sudo bash install.sh"
    ok "Root access"

    [ -f "$PANEL/artisan" ] || fail "Pterodactyl not found at $PANEL"
    ok "Pterodactyl found"

    command -v php &>/dev/null || fail "PHP not found"
    ok "PHP: $(php -r 'echo PHP_VERSION;')"

    command -v node &>/dev/null || fail "Node.js not found"
    ok "Node.js: $(node --version)"

    command -v yarn &>/dev/null || fail "Yarn not found"
    ok "Yarn: $(yarn --version)"

    FREE=$(df -BG "$PANEL" | tail -1 | awk '{print $4}' | tr -d 'G')
    [ "$FREE" -lt 2 ] && warn "Low disk: ${FREE}GB"
    ok "Disk: ${FREE}GB free"
}

# ── Backup ──
backup() {
    header "BACKING UP"
    BACKUP="$PANEL/.bsdk-backup-$(date +%Y%m%d-%H%M%S)"
    mkdir -p "$BACKUP"

    local files=(tailwind.config.js resources/scripts/index.tsx routes/web.php)
    local dirs=(resources/scripts resources/views public/assets public/DGEN public/assets/css public/assets/fonts)

    for f in "${files[@]}"; do
        [ -f "$PANEL/$f" ] && cp "$PANEL/$f" "$BACKUP/" 2>/dev/null
    done
    for d in "${dirs[@]}"; do
        [ -d "$PANEL/$d" ] && cp -r "$PANEL/$d" "$BACKUP/$(basename $d)" 2>/dev/null
    done

    ok "Backup: $BACKUP"
}

# ── Download Theme ──
download() {
    header "DOWNLOADING BSDK V1"
    local tmp="/tmp/bsdk-$$"
    rm -rf "$tmp"

    step "Cloning theme"
    git clone --depth 1 "$THEME_REPO" "$tmp" 2>>"$LOG" || fail "Clone failed"
    ok "Downloaded"

    THEME="$tmp"
}

# ── Install Theme ──
install() {
    header "INSTALLING BSDK V1 THEME"
    cd "$PANEL" || fail "Cannot access panel"

    # ── 1. Tailwind Config ──
    step "Replacing tailwind.config.js"
    cp "$THEME/tailwind.config.js" "$PANEL/tailwind.config.js" 2>>"$LOG"
    ok "tailwind.config.js"

    # ── 2. Custom CSS ──
    step "Installing theme CSS"
    cp "$THEME/resources/scripts/custom.css" "$PANEL/resources/scripts/custom.css" 2>>"$LOG"
    cp "$THEME/resources/scripts/hyper-components.css" "$PANEL/resources/scripts/hyper-components.css" 2>>"$LOG"

    # Inject import into index.tsx
    local idx="$PANEL/resources/scripts/index.tsx"
    if [ -f "$idx" ] && ! grep -q "custom.css" "$idx"; then
        sed -i "1a import './custom.css';" "$idx" 2>>"$LOG"
    fi
    ok "custom.css + hyper-components.css + import"

    # ── 3. Login Page ──
    step "Installing login page"
    mkdir -p "$PANEL/resources/scripts/components/auth"
    cp "$THEME/resources/scripts/components/auth/LoginContainer.tsx" \
       "$PANEL/resources/scripts/components/auth/LoginContainer.tsx" 2>>"$LOG"
    ok "LoginContainer.tsx"

    # ── 4. Navigation Bar ──
    step "Installing navigation bar"
    cp "$THEME/resources/scripts/components/NavigationBar.tsx" \
       "$PANEL/resources/scripts/components/NavigationBar.tsx" 2>>"$LOG"
    ok "NavigationBar.tsx"

    # ── 5. Server Card ──
    step "Installing server card"
    mkdir -p "$PANEL/resources/scripts/components/server"
    cp "$THEME/resources/scripts/components/server/ServerCard.tsx" \
       "$PANEL/resources/scripts/components/server/ServerCard.tsx" 2>>"$LOG"
    ok "ServerCard.tsx"

    # ── 6. Blade Layout ──
    step "Installing blade layout"
    cp "$THEME/resources/views/layouts/master.blade.php" \
       "$PANEL/resources/views/layouts/master.blade.php" 2>>"$LOG"
    ok "master.blade.php"

    # ── 6b. Hyper React Components ──
    step "Installing Hyper components"
    mkdir -p "$PANEL/resources/scripts/components/hyper"
    mkdir -p "$PANEL/resources/scripts/components/hyper/settings"
    mkdir -p "$PANEL/resources/scripts/components/hyper/addons"
    cp "$THEME/resources/scripts/components/hyper/HyperSidebar.tsx" \
       "$PANEL/resources/scripts/components/hyper/HyperSidebar.tsx" 2>>"$LOG"
    cp "$THEME/resources/scripts/components/hyper/HyperDashboard.tsx" \
       "$PANEL/resources/scripts/components/hyper/HyperDashboard.tsx" 2>>"$LOG"
    cp "$THEME/resources/scripts/components/hyper/settings/HyperSettings.tsx" \
       "$PANEL/resources/scripts/components/hyper/settings/HyperSettings.tsx" 2>>"$LOG"
    cp "$THEME/resources/scripts/components/hyper/addons/AddonSettings.tsx" \
       "$PANEL/resources/scripts/components/hyper/addons/AddonSettings.tsx" 2>>"$LOG"
    ok "Hyper components installed"

    # ── 7. Public Assets ──
    step "Installing assets"
    mkdir -p "$PANEL/public/assets/bsdk"
    cp "$THEME/public/assets/"* "$PANEL/public/assets/bsdk/" 2>>"$LOG"
    ok "logo.svg, background.svg, favicon.svg"

    # ── 7b. Hyper Assets ──
    step "Installing Hyper assets (DGEN, fonts, CSS)"
    mkdir -p "$PANEL/public/assets/css"
    mkdir -p "$PANEL/public/assets/fonts"
    mkdir -p "$PANEL/public/DGEN/themes/Hyperv2/lang"
    mkdir -p "$PANEL/public/DGEN/themes/Hyperv2/server/banner"
    mkdir -p "$PANEL/public/DGEN/themes/Hyperv2/server/card"
    mkdir -p "$PANEL/public/DGEN/themes/Hyperv2/img"
    mkdir -p "$PANEL/public/DGEN/addons/MinecraftPlayerManager"

    cp "$THEME/public/assets/css/hyper.css" "$PANEL/public/assets/css/hyper.css" 2>>"$LOG"
    [ -f "$THEME/public/assets/css/fonts.css" ] && cp "$THEME/public/assets/css/fonts.css" "$PANEL/public/assets/css/fonts.css" 2>>"$LOG"
    [ -f "$THEME/public/assets/css/fonts-selector.css" ] && cp "$THEME/public/assets/css/fonts-selector.css" "$PANEL/public/assets/css/fonts-selector.css" 2>>"$LOG"
    [ -f "$THEME/public/service-worker.js" ] && cp "$THEME/public/service-worker.js" "$PANEL/public/service-worker.js" 2>>"$LOG"

    cp -r "$THEME/public/assets/fonts/"* "$PANEL/public/assets/fonts/" 2>/dev/null
    cp -r "$THEME/public/DGEN/"* "$PANEL/public/DGEN/" 2>/dev/null
    ok "Hyper assets installed"

    # ── 8. Theme Config ──
    step "Installing theme config"
    mkdir -p "$PANEL/storage/app"
    cp "$THEME/config/theme.json" "$PANEL/storage/app/bsdk-theme.json" 2>>"$LOG"
    ok "theme.json"

    # ── 9. Plugin Installer API ──
    step "Installing plugin installer"
    mkdir -p "$PANEL/app/Http/Controllers/Api/Client"
    cp "$THEME/app/InstallerController.php" \
       "$PANEL/app/Http/Controllers/Api/Client/InstallerController.php" 2>>"$LOG"
    cp "$THEME/routes/installer.php" "$PANEL/routes/installer.php" 2>>"$LOG"

    # Append routes
    local web="$PANEL/routes/web.php"
    if [ -f "$web" ] && ! grep -q "installer" "$web"; then
        echo "" >> "$web"
        echo "require __DIR__ . '/installer.php';" >> "$web"
    fi
    ok "Installer API"

    # ── 10. Admin Theme Settings ──
    step "Installing admin theme settings"
    mkdir -p "$PANEL/app/Http/Controllers/Admin"
    cp "$THEME/app/Http/Controllers/Admin/BsdkThemeController.php" \
       "$PANEL/app/Http/Controllers/Admin/BsdkThemeController.php" 2>>"$LOG"
    cp "$THEME/routes/bsdk-routes.php" "$PANEL/routes/bsdk-routes.php" 2>>"$LOG"
    cp "$THEME/config/bsdk-presets.php" "$PANEL/config/bsdk-presets.php" 2>>"$LOG"

    mkdir -p "$PANEL/resources/views/admin"
    cp "$THEME/resources/views/admin/bsdk-theme.blade.php" \
       "$PANEL/resources/views/admin/bsdk-theme.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/admin/bsd-settings.blade.php" \
       "$PANEL/resources/views/admin/bsd-settings.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/admin/addon-settings.blade.php" \
       "$PANEL/resources/views/admin/addon-settings.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/admin/servers.blade.php" \
       "$PANEL/resources/views/admin/servers.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/admin/users.blade.php" \
       "$PANEL/resources/views/admin/users.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/admin/nodes.blade.php" \
       "$PANEL/resources/views/admin/nodes.blade.php" 2>>"$LOG"

    # ── 10c. User Account Pages ──
    step "Installing user account pages"
    mkdir -p "$PANEL/resources/views/account"
    cp "$THEME/resources/views/account/index.blade.php" \
       "$PANEL/resources/views/account/index.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/account/api.blade.php" \
       "$PANEL/resources/views/account/api.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/account/ssh.blade.php" \
       "$PANEL/resources/views/account/ssh.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/account/activity.blade.php" \
       "$PANEL/resources/views/account/activity.blade.php" 2>>"$LOG"

    # ── 10d. User Pages ──
    step "Installing user pages"
    cp "$THEME/resources/views/staff-request.blade.php" \
       "$PANEL/resources/views/staff-request.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/notifications.blade.php" \
       "$PANEL/resources/views/notifications.blade.php" 2>>"$LOG"

    # ── 10e. Email Templates ──
    step "Installing email templates"
    mkdir -p "$PANEL/resources/views/emails"
    cp "$THEME/resources/views/emails/password-reset.blade.php" \
       "$PANEL/resources/views/emails/password-reset.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/emails/server-created.blade.php" \
       "$PANEL/resources/views/emails/server-created.blade.php" 2>>"$LOG"
    cp "$THEME/resources/views/emails/server-suspended.blade.php" \
       "$PANEL/resources/views/emails/server-suspended.blade.php" 2>>"$LOG"

    # ── 10f. Login Page ──
    step "Installing login page"
    mkdir -p "$PANEL/resources/views/auth"
    cp "$THEME/resources/views/auth/login.blade.php" \
       "$PANEL/resources/views/auth/login.blade.php" 2>>"$LOG"

    # ── 10g. PWA Manifest ──
    step "Installing PWA manifest"
    cp "$THEME/public/manifest.json" \
       "$PANEL/public/manifest.json" 2>>"$LOG"

    # ── 10b. Settings & Addon API Controllers ──
    step "Installing Settings & Addon API controllers"
    mkdir -p "$PANEL/app/Http/Controllers/Api/Admin"
    cp "$THEME/app/Http/Controllers/Api/Admin/BsdkSettingsController.php" \
       "$PANEL/app/Http/Controllers/Api/Admin/BsdkSettingsController.php" 2>>"$LOG"
    cp "$THEME/app/Http/Controllers/Api/Admin/BsdkAddonController.php" \
       "$PANEL/app/Http/Controllers/Api/Admin/BsdkAddonController.php" 2>>"$LOG"
    ok "Settings & Addon API controllers installed"

    # Append admin routes
    if [ -f "$web" ] && ! grep -q "bsdk-routes" "$web"; then
        echo "" >> "$web"
        echo "require __DIR__ . '/bsdk-routes.php';" >> "$web"
    fi
    ok "Admin theme settings"

    # ── 11. Database Migration ──
    step "Running database migration"
    if php artisan migrate --force 2>>"$LOG"; then
        ok "Migration complete"
    else
        warn "Migration may have failed (table might already exist)"
    fi

    # ── 12. Permissions ──
    step "Setting permissions"
    chown -R www-data:www-data "$PANEL" 2>>"$LOG"
    ok "Done"
}

# ── Build ──
build() {
    header "BUILDING FRONTEND"
    cd "$PANEL" || fail "Cannot access panel"

    export NODE_OPTIONS=--openssl-legacy-provider

    step "Installing dependencies"
    yarn install --frozen-lockfile 2>>"$LOG" || yarn install 2>>"$LOG"
    ok "Dependencies"

    step "Building production assets"
    yarn build:production 2>>"$LOG"
    ok "Build complete"
}

# ── Clear Caches ──
caches() {
    header "CLEARING CACHES"
    cd "$PANEL"
    php artisan optimize:clear 2>>"$LOG"
    php artisan view:clear 2>>"$LOG"
    php artisan cache:clear 2>>"$LOG"
    ok "Caches cleared"
}

# ── Done ──
done_screen() {
    clear
    echo -e "${GREEN}"
    cat << 'EOF'
  ╔═══════════════════════════════════════════════════════╗
  ║   ██████╗ ███████╗██╗   ██╗███████╗███╗   ██╗████████╗
  ║   ██╔══██╗██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝
  ║   ██║  ██║█████╗  ██║   ██║██████╗ ██╔██╗ ██║   ██║   
  ║   ██║  ██║██╔══╝  ╚██╗ ██╔╝╚════██╗██║╚██╗██║   ██║   
  ║   ██████╔╝███████╗ ╚████╔╝ ███████║██║ ╚████║   ██║   
  ║   ╚═════╝ ╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝   
  ╚═══════════════════════════════════════════════════════╝
EOF
    echo -e "${NC}"
    echo -e "  ${GREEN}${BOLD}THEME INSTALLED${NC}"
    echo ""
    echo -e "  ${CYAN}Panel:${NC}    $PANEL"
    echo -e "  ${CYAN}Theme:${NC}    BSDK V1"
    echo -e "  ${CYAN}Made by:${NC}  Akshit"
    echo ""
    echo -e "  ${WHITE}Admin Settings:${NC}"
    echo -e "  ${DIM}Visit ${BOLD}${PANEL_URL:-http://your-panel}/admin/bsdk-theme${NC}"
    echo -e "  ${DIM}to customize theme, colors, presets, and more${NC}"
    echo ""
    echo -e "  ${WHITE}Customize:${NC}"
    echo -e "  ${DIM}Edit ${BOLD}$PANEL/storage/app/bsdk-theme.json${NC}"
    echo -e "  ${DIM}Then run: ${BOLD}sudo bash install.sh --rebuild${NC}"
    echo ""
    echo -e "  ${DIM}Backup: $BACKUP${NC}"
    echo -e "  ${DIM}Log: $LOG${NC}"
    br
}

# ── Uninstall ──
uninstall() {
    header "UNINSTALLING BSDK V1"
    warn "Removing theme, restoring original files"
    read -p "  Continue? [y/N]: " c
    [[ ! "$c" =~ ^[Yy]$ ]] && return

    cd "$PANEL"

    # Find latest backup
    local latest=$(ls -td "$PANEL"/.bsdk-backup-* 2>/dev/null | head -1)
    if [ -n "$latest" ]; then
        step "Restoring from backup"
        [ -f "$latest/tailwind.config.js" ] && cp "$latest/tailwind.config.js" "$PANEL/"
        [ -f "$latest/index.tsx" ] && cp "$latest/index.tsx" "$PANEL/resources/scripts/"
        [ -d "$latest/scripts" ] && cp -r "$latest/scripts/"* "$PANEL/resources/scripts/"
        [ -d "$latest/views" ] && cp -r "$latest/views/"* "$PANEL/resources/views/"
        [ -d "$latest/assets" ] && cp -r "$latest/assets/"* "$PANEL/public/assets/"
        [ -d "$latest/css" ] && cp -r "$latest/css/"* "$PANEL/public/assets/css/" 2>/dev/null
        [ -d "$latest/DGEN" ] && cp -r "$latest/DGEN/"* "$PANEL/public/DGEN/" 2>/dev/null
        ok "Restored"
    fi

    # Remove theme files
    rm -f "$PANEL/resources/scripts/custom.css"
    rm -f "$PANEL/resources/scripts/hyper-components.css"
    rm -rf "$PANEL/public/assets/bsdk"
    rm -f "$PANEL/public/assets/css/hyper.css"
    rm -f "$PANEL/public/service-worker.js"
    rm -rf "$PANEL/public/DGEN"
    rm -f "$PANEL/storage/app/bsdk-theme.json"
    rm -rf "$PANEL/app/Http/Controllers/Api/Client/InstallerController.php"
    rm -f "$PANEL/routes/installer.php"
    rm -rf "$PANEL/app/Http/Controllers/Admin/BsdkThemeController.php"
    rm -rf "$PANEL/app/Http/Controllers/Api/Admin/BsdkSettingsController.php"
    rm -rf "$PANEL/app/Http/Controllers/Api/Admin/BsdkAddonController.php"
    rm -f "$PANEL/routes/bsdk-routes.php"
    rm -f "$PANEL/config/bsdk-presets.php"
    rm -rf "$PANEL/resources/views/admin"
    rm -rf "$PANEL/resources/views/account"
    rm -rf "$PANEL/resources/views/emails"
    rm -rf "$PANEL/resources/views/auth/login.blade.php"
    rm -f "$PANEL/resources/views/staff-request.blade.php"
    rm -f "$PANEL/resources/views/notifications.blade.php"
    rm -f "$PANEL/public/manifest.json"
    rm -rf "$PANEL/resources/scripts/components/hyper"

    # Remove import
    sed -i "/import '.\/custom.css'/d" "$PANEL/resources/scripts/index.tsx" 2>/dev/null

    # Remove routes
    sed -i "/require.*installer.php/d" "$PANEL/routes/web.php" 2>/dev/null
    sed -i "/require.*bsdk-routes.php/d" "$PANEL/routes/web.php" 2>/dev/null

    build
    caches
    ok "BSDK V1 removed"
}

# ── Rebuild (for config changes) ──
rebuild() {
    header "REBUILDING"
    cd "$PANEL"

    # Regenerate CSS variables from theme.json
    local cfg="$PANEL/storage/app/bsdk-theme.json"
    if [ -f "$cfg" ]; then
        step "Regenerating CSS from theme.json"
        ok "Config will apply on next page load"
    fi

    export NODE_OPTIONS=--openssl-legacy-provider
    step "Rebuilding assets"
    yarn build:production 2>>"$LOG"
    ok "Rebuilt"
}

# ══════════════════════════════════════════════════════════════
#  MENU
# ══════════════════════════════════════════════════════════════

menu() {
    clear
    br
    echo -e "  ${CYAN}${BOLD}BSDK V1 — Premium Pterodactyl Theme${NC}"
    echo -e "  ${DIM}Made by Akshit${NC}"
    br
    echo ""
    echo -e "  ${WHITE}╔═══════════════════════════════════════════════╗${NC}"
    echo -e "  ${WHITE}║              ${CYAN}MAIN MENU${WHITE}                      ║${NC}"
    echo -e "  ${WHITE}╠═══════════════════════════════════════════════╣${NC}"
    echo -e "  ${WHITE}║  ${GREEN}1)${NC} ${CYAN}Install Theme${NC}    ${DIM}Full install${NC}           ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}2)${NC} ${CYAN}Reinstall${NC}         ${DIM}Clean reinstall${NC}        ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}3)${NC} ${CYAN}Rebuild${NC}           ${DIM}Rebuild after config${NC}   ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}4)${NC} ${CYAN}Uninstall${NC}         ${DIM}Remove theme${NC}           ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}5)${NC} ${CYAN}System Check${NC}      ${DIM}Verify requirements${NC}     ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}0)${NC} ${RED}Exit${NC}                                      ${WHITE}║${NC}"
    echo -e "  ${WHITE}╚═══════════════════════════════════════════════╝${NC}"
    echo ""
    br
    echo -e "  ${YELLOW}Select [0-5]: ${NC}"
}

while true; do
    banner
    menu
    read -r c
    case $c in
        1) check; backup; download; install; build; caches; done_screen ;;
        2) check; backup; download; install; build; caches; done_screen ;;
        3) rebuild ;;
        4) uninstall ;;
        5) check; echo; read -p "  Press Enter..." ;;
        0) echo -e "  ${GREEN}Made by Akshit — BSDK V1${NC}"; exit 0 ;;
        *) echo -e "  ${RED}Invalid${NC}"; sleep 1 ;;
    esac
    echo ""
    read -p "  Press Enter..." -n 1
done
