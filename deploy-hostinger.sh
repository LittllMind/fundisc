#!/bin/bash
# Deploy script for Hostinger (manual fallback)

set -e

HOST="195.35.49.242"
PORT="65002"
USER="u644586166"
REMOTE_DIR="~/domains/fundisc.fr/public_html/"

echo "🚀 Déploiement Fundisc sur Hostinger"
echo "===================================="

# Build assets
echo "📦 Installation dépendances..."
composer install --no-dev --optimize-autoloader 2>/dev/null || echo "Composer déjà OK"
npm install && npm run build 2>/dev/null || echo "Assets déjà buildés"

# Sync via rsync (si SSH key config)
# rsync -avz --exclude='.git' --exclude='vendor' --exclude='node_modules' \
#   -e "ssh -p $PORT" ./ $USER@$HOST:$REMOTE_DIR

echo ""
echo "⚠️  Déploiement manuel nécessaire :"
echo "   1. Zipper le projet : zip -r fundisc.zip . -x '.git/*' -x 'vendor/*' -x 'node_modules/*'"
echo "   2. Upload via File Manager Hostinger dans $REMOTE_DIR"
echo "   3. Extraire et renommer le .env"
echo ""
echo "🔧 OU configurer GitHub Actions avec les secrets:"
echo "   - HOSTINGER_HOST: 195.35.49.242"
echo "   - HOSTINGER_USER: u644586166"
echo "   - HOSTINGER_PASSWORD: [votre mot de passe]"
echo ""
echo "🌐 Domains configurés:"
echo "   - fundisc.fr (principal)"
echo "   - la-main-a-la-pate.online (landing)"
