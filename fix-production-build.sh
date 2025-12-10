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

echo "✅ Runtime setup complete"
