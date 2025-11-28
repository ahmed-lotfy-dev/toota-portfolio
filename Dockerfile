# -------------------------------------------------------
# STAGE 1: Composer Builder
# -------------------------------------------------------
FROM composer:2 AS vendor
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction --optimize-autoloader

COPY . .
RUN composer dump-autoload -o


# -------------------------------------------------------
# STAGE 2: Node/Vite Builder
# -------------------------------------------------------
FROM node:20-alpine AS frontend
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install --production=false

COPY . .
RUN npm run build


# -------------------------------------------------------
# STAGE 3: Production PHP-FPM App
# -------------------------------------------------------
FROM php:8.3-fpm AS app

# Install system + PHP extensions
RUN apt-get update && apt-get install -y \
    zip unzip curl git libpng-dev libjpeg62-turbo-dev libfreetype6-dev \
    libonig-dev libxml2-dev libzip-dev \
 && docker-php-ext-install pdo_mysql mbstring zip gd

WORKDIR /var/www/html

# Copy application source
COPY . .

# Copy vendors + production assets
COPY --from=vendor /app/vendor ./vendor
COPY --from=frontend /app/public/build ./public/build

# Permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]
