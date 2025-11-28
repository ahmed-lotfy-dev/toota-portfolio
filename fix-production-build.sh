#!/bin/bash

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
