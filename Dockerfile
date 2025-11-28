# ---- Stage 1: Build PHP dependencies (Composer) ----
FROM composer:2 AS composer_builder
WORKDIR /app

# Copy composer files
COPY composer.json composer.lock ./

# Install PHP dependencies (no dev)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ---- Stage 2: Build frontend assets (Node/Vite) ----
FROM node:18 AS node_builder
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .
RUN npm run build

# ---- Stage 3: Production container (FrankenPHP) ----
FROM dunglas/frankenphp:1.1-php8.3 AS production

# Set working directory
WORKDIR /app

# Copy Laravel app
COPY . .

# Copy vendor from Composer stage
COPY --from=composer_builder /app/vendor ./vendor

# Copy built frontend assets
COPY --from=node_builder /app/public ./public

# Correct permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

# Expose web ports
EXPOSE 80
EXPOSE 443

# Run FrankenPHP with Laravel's public folder
CMD ["php", "frankenphp", "run", "--config=/app/frankenphp.php"]
