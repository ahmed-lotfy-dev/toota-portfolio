# ============================================
# 1) COMPOSER STAGE
# ============================================
FROM composer:2 AS composer_builder
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# ============================================
# 2) NODE/VITE STAGE
# ============================================
FROM node:18 AS node_builder
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install

COPY . .
RUN npm run build

# ============================================
# 3) PROD STAGE (FrankenPHP)
# ============================================
FROM dunglas/frankenphp:1.1-php8.3 AS production

WORKDIR /app

# Copy whole Laravel project
COPY . .

# Copy dependencies from Composer stage
COPY --from=composer_builder /app/vendor ./vendor

# Copy built assets from Node stage
COPY --from=node_builder /app/public ./public

# Set correct permissions
RUN chown -R www-data:www-data /app/storage /app/bootstrap/cache

EXPOSE 80
EXPOSE 443

CMD ["php", "frankenphp", "run", "--config=/app/frankenphp.php"]
