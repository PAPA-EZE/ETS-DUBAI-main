#!/usr/bin/env bash
set -e

echo "🚀 Configuration de l'application pour Render"
echo "=============================================="
echo ""

# Vérifier si nous sommes dans un projet Laravel
if [ ! -f "artisan" ]; then
    echo "❌ Erreur : Ce script doit être exécuté depuis la racine d'un projet Laravel"
    exit 1
fi

# Demander à l'utilisateur quel type de base de données
echo "📊 Quelle base de données souhaitez-vous utiliser ?"
echo "1) SQLite (données éphémères, recommandé pour tests uniquement)"
echo "2) PostgreSQL (données persistantes, recommandé pour production)"
read -p "Votre choix (1 ou 2) : " db_choice

# Créer les scripts de build et start
echo ""
echo "📝 Création des scripts de déploiement..."

# Script build.sh
cat > build.sh << 'EOF'
#!/usr/bin/env bash
set -e

echo "🚀 Starting build process..."

# Installer les dépendances Composer
echo "📦 Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Créer les répertoires nécessaires
echo "📁 Creating directories..."
mkdir -p database storage/framework/{sessions,views,cache}

# Créer le fichier SQLite si nécessaire
if [ "$DB_CONNECTION" = "sqlite" ]; then
    echo "🗄️ Creating SQLite database..."
    touch database/database.sqlite
    chmod 664 database/database.sqlite
fi

# Définir les permissions
echo "🔐 Setting permissions..."
chmod -R 775 storage bootstrap/cache
[ -d "database" ] && chmod -R 775 database

# Générer la clé d'application si nécessaire
if [ -z "$APP_KEY" ]; then
    echo "🔑 Generating application key..."
    php artisan key:generate --force
fi

# Nettoyer les caches
echo "🧹 Clearing caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimiser pour la production
echo "⚡ Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Exécuter les migrations
echo "🔄 Running migrations..."
php artisan migrate --force --no-interaction

# Créer un lien symbolique pour le storage
echo "🔗 Creating storage link..."
php artisan storage:link || true

echo "✅ Build completed successfully!"
EOF

# Script start.sh
cat > start.sh << 'EOF'
#!/usr/bin/env bash
set -e

echo "🌟 Starting Etablissement Dubai application..."

# S'assurer que les permissions sont correctes
chmod -R 775 storage bootstrap/cache
[ -d "database" ] && chmod -R 775 database

# Démarrer le serveur PHP sur le port défini par Render
echo "🚀 Starting PHP server on port ${PORT:-8000}..."
php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
EOF

# Rendre les scripts exécutables
chmod +x build.sh start.sh
git update-index --chmod=+x build.sh
git update-index --chmod=+x start.sh

echo "✅ Scripts créés avec succès !"

# Créer le fichier render.yaml approprié
echo ""
echo "📝 Création du fichier render.yaml..."

if [ "$db_choice" = "2" ]; then
    # Configuration PostgreSQL
    cat > render.yaml << 'EOF'
services:
  - type: web
    name: etablissement-dubai
    runtime: php
    plan: free
    buildCommand: ./build.sh
    startCommand: ./start.sh
    envVars:
      - key: APP_NAME
        value: "Etablissement Dubai"
      - key: APP_ENV
        value: production
      - key: APP_KEY
        generateValue: true
      - key: APP_DEBUG
        value: false
      - key: APP_URL
        sync: false
      - key: DB_CONNECTION
        value: pgsql
      - key: DB_HOST
        fromDatabase:
          name: dubai-db
          property: host
      - key: DB_PORT
        fromDatabase:
          name: dubai-db
          property: port
      - key: DB_DATABASE
        fromDatabase:
          name: dubai-db
          property: database
      - key: DB_USERNAME
        fromDatabase:
          name: dubai-db
          property: user
      - key: DB_PASSWORD
        fromDatabase:
          name: dubai-db
          property: password
      - key: SESSION_DRIVER
        value: database
      - key: CACHE_STORE
        value: database
      - key: QUEUE_CONNECTION
        value: database
      - key: LOG_CHANNEL
        value: stack
      - key: LOG_LEVEL
        value: error

databases:
  - name: dubai-db
    databaseName: dubai
    user: dubai_user
    plan: free
EOF
    echo "✅ Configuration PostgreSQL créée !"
else
    # Configuration SQLite
    cat > render.yaml << 'EOF'
services:
  - type: web
    name: etablissement-dubai
    runtime: php
    plan: free
    buildCommand: ./build.sh
    startCommand: ./start.sh
    envVars:
      - key: APP_NAME
        value: "Etablissement Dubai"
      - key: APP_ENV
        value: production
      - key: APP_KEY
        generateValue: true
      - key: APP_DEBUG
        value: false
      - key: APP_URL
        sync: false
      - key: DB_CONNECTION
        value: sqlite
      - key: SESSION_DRIVER
        value: database
      - key: CACHE_STORE
        value: database
      - key: QUEUE_CONNECTION
        value: database
      - key: LOG_CHANNEL
        value: stack
      - key: LOG_LEVEL
        value: error
EOF
    echo "✅ Configuration SQLite créée !"
    echo "⚠️  ATTENTION : Avec SQLite, vos données seront perdues à chaque redéploiement !"
fi

# Créer/mettre à jour .gitignore
echo ""
echo "📝 Mise à jour du .gitignore..."
if [ ! -f .gitignore ]; then
    cat > .gitignore << 'EOF'
/vendor/
/node_modules/
/public/hot
/public/storage
/storage/*.key
/database/database.sqlite
/database/database.sqlite-journal
.env
.env.backup
.env.production
.phpunit.result.cache
.idea/
.vscode/
*.swp
*.swo
*~
.DS_Store
Homestead.json
Homestead.yaml
npm-debug.log
yarn-error.log
/bootstrap/cache/*
!/bootstrap/cache/.gitignore
EOF
    echo "✅ .gitignore créé !"
else
    echo "ℹ️  .gitignore existe déjà, vérifiez qu'il contient les exclusions nécessaires"
fi

# Résumé
echo ""
echo "=============================================="
echo "✅ Configuration terminée !"
echo "=============================================="
echo ""
echo "📋 Fichiers créés/modifiés :"
echo "   - build.sh"
echo "   - start.sh"
echo "   - render.yaml"
echo "   - .gitignore"
echo ""
echo "🚀 Prochaines étapes :"
echo ""
echo "1️⃣  Vérifiez les fichiers créés"
echo "2️⃣  Committez les changements :"
echo "    git add ."
echo "    git commit -m \"Configuration Render\""
echo "    git push origin main"
echo ""
echo "3️⃣  Sur Render (https://render.com) :"
echo "    - Cliquez sur 'New +' → 'Blueprint'"
echo "    - Sélectionnez votre repository"
echo "    - Cliquez sur 'Apply'"
echo ""
echo "4️⃣  Attendez le déploiement (5-10 minutes)"
echo ""
echo "5️⃣  Créez votre utilisateur admin via le Shell Render"
echo ""
echo "=============================================="
echo ""

if [ "$db_choice" = "2" ]; then
    echo "💡 Conseil : Installez l'extension PostgreSQL localement pour tester :"
    echo "   Ubuntu/Debian : sudo apt-get install php-pgsql"
    echo "   macOS : brew install postgresql"
    echo ""
fi

echo "📚 Documentation complète disponible dans le guide de déploiement"
echo ""