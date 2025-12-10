#!/bin/bash

# This script runs AFTER container starts (runtime setup)
# Do NOT build assets here - they're built during Nixpacks build phase

# Install required system packages for backups (only if missing)
# Handled by railpack.json deploy.aptPackages
# if ! command -v pg_dump &> /dev/null; then
#     echo "🔧 Installing postgresql-client..."
#     apt-get update -qq && apt-get install -y -qq postgresql-client
#     echo "✅ PostgreSQL client installed"
# fi

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Link storage (safe to run multiple times)
php artisan storage:link || true

# backend optimization
php artisan optimize:clear
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Runtime setup complete, starting FrankenPHP..."

# Start Supercronic in the background
/usr/local/bin/supercronic -quiet /etc/supercronic/crontab &

# Start FrankenPHP
exec frankenphp run --config Caddyfile
