# -------------------------------------------------------
# 1) Composer Builder Stage
# -------------------------------------------------------
FROM composer:2 AS composer_builder
WORKDIR /app

COPY composer.json composer.lock ./
RUN composer install --no-dev --prefer-dist --no-interaction

COPY . .
RUN composer dump-autoload --optimize


# -------------------------------------------------------
# 2) Node Builder Stage (Vite)
# -------------------------------------------------------
FROM node:20-alpine AS node_builder
WORKDIR /app

COPY package.json package-lock.json ./
RUN npm install --production=false

COPY . .
RUN npm run build


# -------------------------------------------------------
# 3) Production FrankenPHP Runtime
# -------------------------------------------------------
FROM dunglas/frankenphp:1.2

# Install PHP extensions (official method)
RUN install-php-extensions \
    pdo_mysql \
    pdo_pgsql \
    gd \
    zip \
    opcache \
    intl \
    bcmath

WORKDIR /app

# Copy app files
COPY . .

# Copy vendor + built assets
COPY --from=composer_builder /app/vendor ./vendor
COPY --from=node_builder /app/public/build ./public/build

# Permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

CMD ["frankenphp", "run", "--config", "/app/frankenphp.php"]
