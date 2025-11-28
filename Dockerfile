# ---------------------------------------------------------
# 1) Build Stage – Composer & Node
# ---------------------------------------------------------
FROM dunglas/frankenphp:1.2-builder AS builder

WORKDIR /app

# Copy all project files
COPY . .

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Build frontend assets (Vite)
RUN if [ -f package.json ]; then \
      npm install && \
      npm run build; \
    fi


# ---------------------------------------------------------
# 2) Production Runtime
# ---------------------------------------------------------
FROM dunglas/frankenphp:1.2

# Set domain or disable HTTPS
ENV SERVER_NAME=:80
ENV SERVER_ROOT=/app/public

# Use production php.ini
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

WORKDIR /app

# Copy project
COPY . .

# Copy vendor + built assets from builder
COPY --from=builder /app/vendor ./vendor
COPY --from=builder /app/public/build ./public/build

# Ensure Laravel has writable storage
RUN chmod -R 777 storage bootstrap/cache
