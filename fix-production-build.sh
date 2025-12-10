#!/bin/bash

# This script should be run by Dokploy's post-deploy hook
# or manually in the container

# Install required system packages for backups
if ! command -v pg_dump &> /dev/null; then
    echo "🔧 Installing postgresql-client..."
    apt-get update -qq && apt-get install -y -qq postgresql-client zip unzip
    echo "✅ PostgreSQL client installed"
fi

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Install dependencies
npm ci --include=dev || npm install --include=dev

# Build assets
npm run build

# Clear and cache config/routes/views
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Link storage
php artisan storage:link

echo "✅ Production setup complete"
