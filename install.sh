#!/bin/bash

# ═══════════════════════════════════════════════════════════════
# BSDK V1 — Premium Pterodactyl Theme Installer
# Made by Akshit
# Full Blueprint + Theme Installer with animations
# ═══════════════════════════════════════════════════════════════

# ── Colors ──
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
MAGENTA='\033[0;35m'
WHITE='\033[1;37m'
DIM='\033[2m'
BOLD='\033[1m'
UNDERLINE='\033[4m'
NC='\033[0m'

# ── Config ──
PANEL_DIR="${PANEL_DIR:-/var/www/pterodactyl}"
THEME_REPO="https://github.com/CodeByCruel/theme.git"
VERSION="1.0.0"
LOG="/tmp/bsdk-$(date +%Y%m%d-%H%M%S).log"
BACKUP=""
STEPS_TOTAL=0
STEPS_DONE=0

# ── Pretty Output ──
br() { echo -e "${BLUE}━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━${NC}"; }
header() { clear; br; echo -e "  ${CYAN}${BOLD}$1${NC}"; br; echo; }
step() { ((STEPS_DONE++)); echo -e "  ${CYAN}[$STEPS_DONE/$STEPS_TOTAL]${NC} ${WHITE}$1${NC}"; }
ok() { echo -e "  ${GREEN}  ✓ $1${NC}"; }
fail() { echo -e "  ${RED}  ✗ $1${NC}"; echo -e "  ${DIM}    Log: $LOG${NC}"; exit 1; }
warn() { echo -e "  ${YELLOW}  ⚠ $1${NC}"; }
info() { echo -e "  ${DIM}    $1${NC}"; }
divider() { echo -e "  ${DIM}────────────────────────────────────────────────${NC}"; }

# ── Spinner ──
spinner() {
    local pid=$1 msg=$2
    local frames='⠋⠙⠹⠸⠼⠴⠦⠧⠇⠏'
    while kill -0 "$pid" 2>/dev/null; do
        for (( i=0; i<${#frames}; i++ )); do
            printf "\r  ${CYAN}%s${NC} %s" "${frames:$i:1}" "$msg"
            sleep 0.07
        done
    done
    printf "\r  ${GREEN}✓${NC} %s\n" "$msg"
}

# ── Progress Bar ──
bar() {
    local cur=$1 max=$2 msg=$3
    local pct=$((cur * 100 / max))
    local fill=$((pct / 3))
    local empty=$((33 - fill))
    printf "\r  ${CYAN}[${GREEN}%s${DIM}%s${NC}] ${WHITE}%3d%%${NC} %s" \
        "$(printf '█%.0s' $(seq 1 $((fill > 0 ? fill : 1))))" \
        "$(printf '░%.0s' $(seq 1 $((empty > 0 ? empty : 1))))" \
        "$pct" "$msg"
}

# ── ASCII Banner ──
banner() {
    clear
    echo -e "${CYAN}"
    cat << 'EOF'

  ╔═══════════════════════════════════════════════════════════════╗
  ║                                                               ║
  ║   ██████╗ ███████╗██╗   ██╗███████╗███╗   ██╗████████╗██╗   ║
  ║   ██╔══██╗██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝██║   ║
  ║   ██║  ██║█████╗  ██║   ██║██████╗ ██╔██╗ ██║   ██║   ██║   ║
  ║   ██║  ██║██╔══╝  ╚██╗ ██╔╝╚════██╗██║╚██╗██║   ██║   ██║   ║
  ║   ██████╔╝███████╗ ╚████╔╝ ███████║██║ ╚████║   ██║   ██║   ║
  ║   ╚═════╝ ╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝   ║
  ║                                                               ║
  ║            ${WHITE}Premium Pterodactyl Theme${CYAN}                      ║
  ║            ${DIM}Made by Akshit${CYAN}                                  ║
  ║                                                               ║
  ╚═══════════════════════════════════════════════════════════════╝

EOF
    echo -e "${NC}"
    sleep 1
}

# ── System Check ──
check_system() {
    header "SYSTEM DIAGNOSTICS"

    # Root
    if [ "$EUID" -ne 0 ]; then
        fail "Run as root: sudo bash install.sh"
    fi
    ok "Root access confirmed"

    # OS
    if [ -f /etc/os-release ]; then
        . /etc/os-release
        ok "OS: $PRETTY_NAME"
    fi

    # Panel
    if [ -f "$PANEL_DIR/artisan" ]; then
        ok "Pterodactyl found: $PANEL_DIR"
        VER=$(cd "$PANEL_DIR" && php artisan --version 2>/dev/null || echo "?")
        info "Version: $VER"
    else
        fail "Pterodactyl not found at $PANEL_DIR"
    fi

    # Blueprint
    if command -v blueprint &>/dev/null; then
        ok "Blueprint: installed"
        HAS_BP=true
    else
        info "Blueprint: not found (will install)"
        HAS_BP=false
    fi

    # PHP
    if command -v php &>/dev/null; then
        ok "PHP: $(php -r 'echo PHP_VERSION;')"
    else
        fail "PHP not found"
    fi

    # Node
    if command -v node &>/dev/null; then
        ok "Node.js: $(node --version)"
    else
        info "Node.js: not found (will install)"
    fi

    # Yarn
    if command -v yarn &>/dev/null; then
        ok "Yarn: $(yarn --version)"
    else
        info "Yarn: not found (will install)"
    fi

    # Composer
    if command -v composer &>/dev/null; then
        ok "Composer: $(composer --version 2>/dev/null | head -1)"
    else
        info "Composer: not found"
    fi

    # Disk
    FREE=$(df -BG "$PANEL_DIR" | tail -1 | awk '{print $4}' | tr -d 'G')
    if [ "$FREE" -lt 2 ]; then
        warn "Low disk: ${FREE}GB free"
    else
        ok "Disk: ${FREE}GB free"
    fi

    # RAM
    TOTAL_RAM=$(free -m | awk '/Mem:/ {print $2}')
    USED_RAM=$(free -m | awk '/Mem:/ {print $3}')
    ok "RAM: ${USED_RAM}MB / ${TOTAL_RAM}MB"

    # Internet
    if ping -c 1 github.com &>/dev/null; then
        ok "Internet: connected"
    else
        fail "No internet connection"
    fi

    divider
    echo ""
}

# ── Backup ──
backup() {
    header "CREATING BACKUP"
    BACKUP="$PANEL_DIR/.bsdk-backup-$(date +%Y%m%d-%H%M%S)"
    mkdir -p "$BACKUP"

    local files=(
        "tailwind.config.js"
        "routes/web.php"
        "routes/client.php"
        "resources/scripts/index.tsx"
    )

    local dirs=(
        "resources/scripts"
        "resources/views"
        "public/assets"
        "app/Http/Controllers"
    )

    for f in "${files[@]}"; do
        [ -f "$PANEL_DIR/$f" ] && cp "$PANEL_DIR/$f" "$BACKUP/" 2>/dev/null
    done

    for d in "${dirs[@]}"; do
        [ -d "$PANEL_DIR/$d" ] && cp -r "$PANEL_DIR/$d" "$BACKUP/$(basename $d)" 2>/dev/null
    done

    ok "Backup saved: $BACKUP"
    info "Size: $(du -sh "$BACKUP" 2>/dev/null | cut -f1)"
}

# ── Install Blueprint ──
install_blueprint() {
    header "INSTALLING BLUEPRINT FRAMEWORK"

    if [ "$HAS_BP" = true ]; then
        info "Already installed — skipping"
        return 0
    fi

    cd "$PANEL_DIR" || fail "Cannot access panel"

    step "Downloading latest Blueprint release"
    local url=$(curl -s https://api.github.com/repos/BlueprintFramework/framework/releases/latest \
        | grep 'browser_download_url' | grep 'release.zip' | cut -d '"' -f 4)
    [ -z "$url" ] && fail "Could not fetch Blueprint URL"

    wget -q "$url" -O release.zip 2>>"$LOG"
    ok "Downloaded"

    step "Extracting Blueprint"
    unzip -oq release.zip 2>>"$LOG"
    rm -f release.zip
    ok "Extracted"

    if [ ! -f .blueprintrc ]; then
        step "Creating config"
        cat <<'RC' > .blueprintrc
WEBUSER="www-data";
OWNERSHIP="www-data:www-data";
USERSHELL="/bin/bash";
RC
        ok "Config created"
    fi

    step "Setting permissions"
    chmod +x blueprint.sh 2>/dev/null
    chown -R www-data:www-data "$PANEL_DIR" 2>>"$LOG"
    ok "Permissions set"
}

# ── Install Node.js ──
install_node() {
    header "INSTALLING NODE.JS 20"

    if command -v node &>/dev/null; then
        local major=$(node --version | cut -d'v' -f2 | cut -d'.' -f1)
        if [ "$major" -ge 18 ]; then
            info "Node.js $(node --version) already installed"
            return 0
        fi
    fi

    step "Adding NodeSource repository"
    mkdir -p /etc/apt/keyrings
    curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key \
        | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg 2>>"$LOG"
    echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_20.x nodistro main" \
        > /etc/apt/sources.list.d/nodesource.list

    step "Installing Node.js"
    apt-get update -qq 2>>"$LOG"
    apt-get install -y nodejs 2>>"$LOG"
    ok "Node.js $(node --version) installed"
}

# ── Install Yarn ──
install_yarn() {
    header "INSTALLING YARN"

    if command -v yarn &>/dev/null; then
        info "Yarn $(yarn --version) already installed"
        return 0
    fi

    step "Installing Yarn"
    npm i -g yarn 2>>"$LOG"
    ok "Yarn $(yarn --version) installed"
}

# ── Install Panel Dependencies ──
install_deps() {
    header "INSTALLING PANEL DEPENDENCIES"
    cd "$PANEL_DIR" || fail "Cannot access panel"

    step "Running yarn install"
    yarn install --frozen-lockfile 2>>"$LOG" || yarn install 2>>"$LOG"
    ok "Dependencies installed"
}

# ── Install BSDK V1 Theme ──
install_theme() {
    header "INSTALLING BSDK V1 THEME"
    cd "$PANEL_DIR" || fail "Cannot access panel"

    local TMP="/tmp/bsdk-theme-$$"
    rm -rf "$TMP"

    # Download theme
    step "Fetching theme files"
    if git clone --depth 1 "$THEME_REPO" "$TMP" 2>>"$LOG"; then
        ok "Cloned from GitHub"
    else
        local zip="/tmp/bsdk.zip"
        curl -sL "https://github.com/prplwtf/Nebula/archive/refs/heads/main.zip" -o "$zip" 2>>"$LOG"
        [ -s "$zip" ] || fail "Download failed"
        unzip -oq "$zip" -d /tmp/ 2>>"$LOG"
        mv /tmp/Nebula-main "$TMP" 2>/dev/null || mv /tmp/nebula-main "$TMP" 2>/dev/null
        rm -f "$zip"
        ok "Downloaded zip"
    fi

    # ── Admin Views ──
    step "Installing admin panel"
    mkdir -p "$PANEL_DIR/resources/views/admin/extensions/bsdkv1"
    cp -r "$TMP/admin/"* "$PANEL_DIR/resources/views/admin/extensions/bsdkv1/" 2>>"$LOG"
    ok "Admin views"

    # ── Admin Wrapper ──
    step "Installing admin wrapper"
    mkdir -p "$PANEL_DIR/resources/views/blueprint/admin/wrappers"
    cp "$TMP/admin/wrapper.blade.php" "$PANEL_DIR/resources/views/blueprint/admin/wrappers/bsdkv1.blade.php" 2>>"$LOG"
    mkdir -p "$PANEL_DIR/public/extensions/bsdkv1"
    cp "$TMP/admin/admin.css" "$PANEL_DIR/public/extensions/bsdkv1/admin.css" 2>>"$LOG"
    ok "Admin wrapper"

    # ── Dashboard Theme ──
    step "Installing theme CSS"
    cp "$TMP/dashboard/theme.css" "$PANEL_DIR/resources/scripts/custom.css" 2>>"$LOG"

    # Inject import
    local idx="$PANEL_DIR/resources/scripts/index.tsx"
    if [ -f "$idx" ] && ! grep -q "custom.css" "$idx"; then
        sed -i "1a\\import './custom.css';" "$idx" 2>>"$LOG"
    fi

    mkdir -p "$PANEL_DIR/resources/views/blueprint/dashboard/wrappers"
    cp "$TMP/dashboard/wrapper.blade.php" "$PANEL_DIR/resources/views/blueprint/dashboard/wrappers/bsdkv1.blade.php" 2>>"$LOG"
    ok "Theme CSS + wrapper"

    # ── React Components ──
    step "Installing React components"
    if [ -d "$TMP/dashboard/components/src" ]; then
        mkdir -p "$PANEL_DIR/resources/scripts/bsdk"
        cp -r "$TMP/dashboard/components/src/"* "$PANEL_DIR/resources/scripts/bsdk/" 2>>"$LOG"
        [ -f "$TMP/dashboard/components/Components.yml" ] && \
            cp "$TMP/dashboard/components/Components.yml" "$PANEL_DIR/resources/scripts/bsdk/" 2>>"$LOG"
    fi
    ok "React components"

    # ── Backend ──
    step "Installing backend"
    [ -d "$TMP/app" ] && cp -r "$TMP/app/"* "$PANEL_DIR/app/" 2>>"$LOG"
    ok "Controllers"

    # ── Routes ──
    step "Installing routes"
    if [ -f "$TMP/routes/client.php" ]; then
        local routes="$PANEL_DIR/routes/client.php"
        if [ -f "$routes" ]; then
            grep -q "installer" "$routes" || { echo "" >> "$routes"; cat "$TMP/routes/client.php" >> "$routes"; }
        else
            cp "$TMP/routes/client.php" "$routes" 2>>"$LOG"
        fi
    fi
    ok "Routes"

    # ── Migration ──
    step "Running migration"
    [ -d "$TMP/database/migrations" ] && \
        cp -r "$TMP/database/migrations/"* "$PANEL_DIR/database/migrations/" 2>>"$LOG"
    cd "$PANEL_DIR" && php artisan migrate --force 2>>"$LOG"
    ok "Migration"

    # ── Assets ──
    step "Installing assets"
    if [ -d "$TMP/data/public" ]; then
        mkdir -p "$PANEL_DIR/public/extensions/bsdkv1/assets"
        cp -r "$TMP/data/public/"* "$PANEL_DIR/public/extensions/bsdkv1/" 2>>"$LOG"
    fi
    ok "Assets"

    # ── Permissions ──
    step "Setting permissions"
    chown -R www-data:www-data "$PANEL_DIR" 2>>"$LOG"
    ok "Permissions"

    rm -rf "$TMP"
    ok "BSDK V1 theme installed!"
}

# ── Build Frontend ──
build() {
    header "BUILDING FRONTEND"
    cd "$PANEL_DIR" || fail "Cannot access panel"

    export NODE_OPTIONS=--openssl-legacy-provider

    step "Building production assets"
    yarn build:production 2>>"$LOG"
    ok "Build complete"
}

# ── Clear Caches ──
caches() {
    header "CLEARING CACHES"
    cd "$PANEL_DIR" || fail "Cannot access panel"

    php artisan optimize:clear 2>>"$LOG"
    php artisan view:clear 2>>"$LOG"
    php artisan cache:clear 2>>"$LOG"
    php artisan config:clear 2>>"$LOG"
    ok "All caches cleared"
}

# ── Done Screen ──
done_screen() {
    clear
    echo -e "${GREEN}"
    cat << 'EOF'
  ╔═══════════════════════════════════════════════════════════════╗
  ║                                                               ║
  ║   ██████╗ ███████╗██╗   ██╗███████╗███╗   ██╗████████╗██╗   ║
  ║   ██╔══██╗██╔════╝██║   ██║██╔════╝████╗  ██║╚══██╔══╝██║   ║
  ║   ██║  ██║█████╗  ██║   ██║██████╗ ██╔██╗ ██║   ██║   ██║   ║
  ║   ██║  ██║██╔══╝  ╚██╗ ██╔╝╚════██╗██║╚██╗██║   ██║   ██║   ║
  ║   ██████╔╝███████╗ ╚████╔╝ ███████║██║ ╚████║   ██║   ██║   ║
  ║   ╚═════╝ ╚══════╝  ╚═══╝  ╚══════╝╚═╝  ╚═══╝   ╚═╝   ╚═╝   ║
  ║                                                               ║
  ╚═══════════════════════════════════════════════════════════════╝
EOF
    echo -e "${NC}"

    echo -e "  ${GREEN}${BOLD}INSTALLATION COMPLETE${NC}"
    echo ""
    echo -e "  ${CYAN}Panel:${NC}     $PANEL_DIR"
    echo -e "  ${CYAN}Theme:${NC}     BSDK V1"
    echo -e "  ${CYAN}Made by:${NC}   Akshit"
    echo ""
    echo -e "  ${WHITE}${BOLD}What's Next:${NC}"
    echo -e "  ${DIM}1.${NC} Go to ${UNDERLINE}Admin → Extensions → BSDK V1${NC}"
    echo -e "  ${DIM}2.${NC} Set your colors, logo, panel name"
    echo -e "  ${DIM}3.${NC} Theme applies instantly"
    echo -e "  ${DIM}4.${NC} Server → Plugins & Mods tab to install plugins"
    echo ""
    echo -e "  ${CYAN}Settings:${NC}  /admin/extensions/bsdkv1"
    echo -e "  ${CYAN}Installer:${NC} /server/{id}/plugins"
    echo ""

    [ -n "$BACKUP" ] && echo -e "  ${DIM}Backup: $BACKUP${NC}"
    echo -e "  ${DIM}Log: $LOG${NC}"
    echo ""
    br
    echo -e "  ${DIM}Made by Akshit — BSDK V1${NC}"
    br
}

# ── Uninstall Theme ──
uninstall() {
    header "UNINSTALLING BSDK V1"
    warn "Theme will be removed. Panel stays intact."
    echo ""
    read -p "  Continue? [y/N]: " c
    [[ ! "$c" =~ ^[Yy]$ ]] && return

    backup

    step "Removing theme files"
    rm -rf "$PANEL_DIR/resources/scripts/custom.css" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/scripts/bsdk" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/views/admin/extensions/bsdkv1" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/views/blueprint/admin/wrappers/bsdkv1.blade.php" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/views/blueprint/dashboard/wrappers/bsdkv1.blade.php" 2>/dev/null
    rm -rf "$PANEL_DIR/public/extensions/bsdkv1" 2>/dev/null
    rm -rf "$PANEL_DIR/app/Http/Controllers/Extensions/Bsdkv1" 2>/dev/null

    local idx="$PANEL_DIR/resources/scripts/index.tsx"
    [ -f "$idx" ] && sed -i "/import '.\/custom.css'/d" "$idx" 2>/dev/null

    step "Rebuilding"
    cd "$PANEL_DIR"
    export NODE_OPTIONS=--openssl-legacy-provider
    yarn build:production 2>>"$LOG"
    php artisan optimize:clear 2>>"$LOG"

    ok "BSDK V1 removed. Backup at: $BACKUP"
}

# ── Update Theme ──
update() {
    header "UPDATING BSDK V1"
    local TMP="/tmp/bsdk-update-$$"
    rm -rf "$TMP"

    step "Fetching latest"
    git clone --depth 1 "$THEME_REPO" "$TMP" 2>>"$LOG" || fail "Fetch failed"

    if [ -f "$TMP/conf.yml" ]; then
        local ver=$(grep "version:" "$TMP/conf.yml" | head -1 | awk '{print $2}' | tr -d "'\"")
        info "Latest: v$ver"
    fi

    backup
    install_theme
    build
    caches
    rm -rf "$TMP"

    ok "Updated!"
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
    echo -e "  ${WHITE}${BOLD}╔════════════════════════════════════════════════════════╗${NC}"
    echo -e "  ${WHITE}║                   ${CYAN}MAIN MENU${WHITE}                         ║${NC}"
    echo -e "  ${WHITE}╠════════════════════════════════════════════════════════╣${NC}"
    echo -e "  ${WHITE}║  ${GREEN}1)${NC} ${CYAN}Full Install${NC}      ${DIM}Blueprint + BSDK V1 Theme${NC}    ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}2)${NC} ${CYAN}Theme Only${NC}        ${DIM}Install theme on existing BP${NC}  ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}3)${NC} ${CYAN}Reinstall${NC}         ${DIM}Clean reinstall of theme${NC}      ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}4)${NC} ${CYAN}Update Theme${NC}      ${DIM}Fetch & apply latest${NC}         ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}5)${NC} ${CYAN}Update Panel${NC}      ${DIM}Update Pterodactyl + rebuild${NC} ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}6)${NC} ${CYAN}Uninstall${NC}         ${DIM}Remove theme, keep panel${NC}     ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}7)${NC} ${CYAN}System Check${NC}      ${DIM}Verify requirements${NC}           ${WHITE}║${NC}"
    echo -e "  ${WHITE}║  ${GREEN}0)${NC} ${RED}Exit${NC}                                         ${WHITE}║${NC}"
    echo -e "  ${WHITE}╚════════════════════════════════════════════════════════╝${NC}"
    echo ""
    br
    echo -e "  ${YELLOW}Select [0-7]: ${NC}"
}

# ── Full Install ──
do_full() {
    STEPS_TOTAL=8
    STEPS_DONE=0
    check_system
    backup
    install_blueprint
    install_node
    install_yarn
    install_deps
    install_theme
    build
    caches
    done_screen
}

# ── Theme Only ──
do_theme() {
    STEPS_TOTAL=4
    STEPS_DONE=0
    [ "$HAS_BP" != true ] && { install_blueprint; }
    backup
    install_theme
    build
    caches
    done_screen
}

# ── Reinstall ──
do_reinstall() {
    STEPS_TOTAL=4
    STEPS_DONE=0
    command -v blueprint &>/dev/null || fail "Blueprint not found. Run full install."
    backup
    step "Removing old theme"
    rm -rf "$PANEL_DIR/resources/scripts/custom.css" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/scripts/bsdk" 2>/dev/null
    rm -rf "$PANEL_DIR/resources/views/admin/extensions/bsdkv1" 2>/dev/null
    rm -rf "$PANEL_DIR/public/extensions/bsdkv1" 2>/dev/null
    install_theme
    build
    caches
    done_screen
}

# ══════════════════════════════════════════════════════════════
#  MAIN
# ══════════════════════════════════════════════════════════════

banner

while true; do
    menu
    read -r c
    case $c in
        1) do_full ;;
        2) do_theme ;;
        3) do_reinstall ;;
        4) update ;;
        5)
            header "UPDATING PANEL"
            warn "This updates Pterodactyl itself"
            read -p "  Continue? [y/N]: " c2
            [[ "$c2" =~ ^[Yy]$ ]] || continue
            backup
            cd "$PANEL_DIR"
            git pull origin develop 2>>"$LOG"
            composer install --no-dev 2>>"$LOG"
            yarn install 2>>"$LOG"
            php artisan migrate --force 2>>"$LOG"
            install_theme
            build
            caches
            done_screen
            ;;
        6) uninstall ;;
        7) check_system; echo; read -p "  Press Enter..." ;;
        0)
            echo ""
            echo -e "  ${GREEN}Made by Akshit — BSDK V1${NC}"
            echo ""
            sleep 1
            exit 0
            ;;
        *)
            echo -e "  ${RED}Invalid option${NC}"
            sleep 1
            ;;
    esac
    echo ""
    read -p "  Press Enter to continue..." -n 1
done
