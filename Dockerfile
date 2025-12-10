FROM dunglas/frankenphp:php8.4

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 1. Install System Dependencies
# 'postgresql-client' is essential for backups (pg_dump)
RUN apt-get update && apt-get install -y \
    postgresql-client \
    git \
    zip \
    unzip \
    curl \
    gnupg \
    && rm -rf /var/lib/apt/lists/*

# 2. Install PHP Extensions
RUN install-php-extensions \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache

# 3. Install Node.js (Version 22) for frontend build
RUN mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# 4. Set working directory
WORKDIR /app

# 5. Composer Dependencies (Cached Layer)
COPY composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# 6. Node Dependencies (Cached Layer)
COPY package.json package-lock.json ./
RUN npm ci

# 7. Copy Application Code (Frequent Changes Layer)
COPY . .

# 8. Build Frontend & Finalize
RUN npm run build
RUN composer dump-autoload --optimize --no-scripts

# 8. Setup Permissions
RUN chmod -R 777 storage bootstrap/cache
RUN chmod +x docker-entrypoint.sh

# 9. Configure Entrypoint
# We use the fix-production-build.sh as the starter, which allows runtime hooks
# It MUST end with executing the frankenphp runner
CMD ["./docker-entrypoint.sh"]
