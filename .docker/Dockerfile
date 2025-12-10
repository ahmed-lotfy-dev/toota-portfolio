FROM dunglas/frankenphp:php8.4

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 1. Install System Dependencies
# 'postgresql-client' is essential for backups (pg_dump)
RUN --mount=type=cache,target=/var/lib/apt/lists,sharing=locked \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
    postgresql-client \
    git \
    zip \
    unzip \
    curl \
    gnupg \
    # Install supercronic for cron scheduling
    && curl -L https://github.com/aptible/supercronic/releases/download/v0.1.20/supercronic-linux-amd64 -o /usr/local/bin/supercronic \
    && chmod +x /usr/local/bin/supercronic

# Copy crontab file
COPY .docker/crontab.txt /etc/supercronic/crontab


# 2. Install PHP Extensions
RUN install-php-extensions \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache

# 3. Install Node.js (Version 22) for frontend build
RUN --mount=type=cache,target=/var/lib/apt/lists,sharing=locked \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# ...

# 5. Composer Dependencies (Cached Layer)
COPY composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# 6. Node Dependencies (Cached Layer)
COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci

# 7. Copy Application Code & Configs
COPY . .
COPY .docker/Caddyfile /app/Caddyfile
COPY .docker/php.ini /usr/local/etc/php/conf.d/custom.ini

# 8. Build Frontend & Finalize
RUN npm run build
RUN composer dump-autoload --optimize --no-scripts

# 9. Setup Permissions & Entrypoint
RUN chmod -R 777 storage bootstrap/cache
RUN chmod +x .docker/entrypoint.sh
EXPOSE 80
CMD ["./.docker/entrypoint.sh"]
