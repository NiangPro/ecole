#!/bin/bash
# ============================================================
#  Déploiement des optimisations de performance — 30 juin 2026
#  Exécuter depuis : /Users/macbook/Documents/NiangProgrammeur/site
# ============================================================
# AVANT DE LANCER : remplacer SSH_USER et REMOTE_PATH
#   SSH_USER  = ton login SSH Infomaniak (ex: niang12345)
#   REMOTE_PATH = chemin absolu du projet sur le serveur
#               (ex: /home/clients/abc123/web/niangprogrammeur.com)
# ============================================================

SSH_USER="REMPLACER_TON_USER_SSH"
SSH_HOST="h2web496.infomaniak.ch"
REMOTE_PATH="REMPLACER_CHEMIN_DISTANT"   # ex: /home/clients/xxxxxx/web/niangprogrammeur.com
LOCAL_PATH="/Users/macbook/Documents/NiangProgrammeur/site"

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'; NC='\033[0m'

# ---- Vérification ----
if [[ "$SSH_USER" == "REMPLACER"* ]] || [[ "$REMOTE_PATH" == "REMPLACER"* ]]; then
    echo -e "${RED}❌ Configure SSH_USER et REMOTE_PATH en haut du script avant de lancer.${NC}"
    exit 1
fi

echo -e "${GREEN}======================================================${NC}"
echo -e "${GREEN}  DÉPLOIEMENT PERF — NiangProgrammeur.com${NC}"
echo -e "${GREEN}======================================================${NC}"
echo ""

# ---- 1. Vérifier que le build local existe ----
echo -e "${YELLOW}[1/4] Vérification du build local...${NC}"
if ! ls "$LOCAL_PATH/public/build/css/" | grep -q "epreuves-show"; then
    echo -e "${RED}❌ Build manquant. Lance d'abord : npm run build${NC}"
    exit 1
fi
echo -e "${GREEN}  ✓ Build CSS prêt${NC}"
echo ""

# ---- 2. Envoi des fichiers PHP/Blade modifiés ----
echo -e "${YELLOW}[2/4] Envoi des fichiers modifiés...${NC}"

FILES_TO_SEND=(
    "app/Http/Controllers/EpreuveController.php"
    "app/Http/Controllers/Admin/EpreuveController.php"
    "app/Http/Controllers/Admin/JobArticleController.php"
    "app/Http/Middleware/PageCache.php"
    "resources/views/layouts/app.blade.php"
    "resources/views/epreuves/show.blade.php"
    "resources/css/features/epreuves-show.css"
    "bootstrap/app.php"
    "routes/web.php"
)

for file in "${FILES_TO_SEND[@]}"; do
    echo -n "  → $file ... "
    # Créer le dossier distant si nécessaire
    REMOTE_DIR="$SSH_USER@$SSH_HOST:$REMOTE_PATH/$(dirname "$file")"
    scp "$LOCAL_PATH/$file" "$SSH_USER@$SSH_HOST:$REMOTE_PATH/$file" && echo -e "${GREEN}OK${NC}" || echo -e "${RED}ERREUR${NC}"
done
echo ""

# ---- 3. Envoi du build (public/build/) ----
echo -e "${YELLOW}[3/4] Envoi du dossier public/build/...${NC}"
rsync -az --progress \
    "$LOCAL_PATH/public/build/" \
    "$SSH_USER@$SSH_HOST:$REMOTE_PATH/public/build/"
echo -e "${GREEN}  ✓ Build envoyé${NC}"
echo ""

# ---- 4. Commandes artisan sur le serveur ----
echo -e "${YELLOW}[4/4] Nettoyage des caches Laravel...${NC}"
ssh "$SSH_USER@$SSH_HOST" "
    cd $REMOTE_PATH && \
    php artisan view:clear && \
    php artisan cache:clear && \
    php artisan config:cache && \
    php artisan route:cache && \
    echo 'Caches vidés OK'
"

echo ""
echo -e "${GREEN}======================================================${NC}"
echo -e "${GREEN}  ✅ Déploiement terminé !${NC}"
echo -e "${GREEN}======================================================${NC}"
echo ""
echo "  Vérifie : https://www.niangprogrammeur.com/epreuves"
echo "  et       : https://www.niangprogrammeur.com"
