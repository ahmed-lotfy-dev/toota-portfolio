# Laravel + Dokploy Deployment Guide

> **SOLUTION**: Use a standard `Dockerfile` to guarantee dependencies.

---

## Guaranteed Fix (Dockerfile)

Create a `Dockerfile` that explicitly installs `postgresql-client`:

```dockerfile
FROM dunglas/frankenphp:php8.4

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Install pg_dump
RUN apt-get update && apt-get install -y postgresql-client zip unzip

# ... (see Full Guide below) ...
```

**Why this works:**
- It uses the standard Debian package manager within a controlled Docker build.
- No compilation from source on the server (avoids ARM64 build failures).
- `postgresql-client` is baked into the image.

---

## Alternative: Manual Installation (Temporary)

If you need a quick fix before redeploying, SSH into the Dokploy container and run:

```bash
apt-get update && apt-get install -y postgresql-client
```

**Note:** This is temporary and will reset on next deployment.

---

## Full Deployment Guide

### 1. Prerequisites

- Laravel project (PHP 8.4+)
- Dokploy instance running
- PostgreSQL or MySQL database
- (Optional) Cloudflare R2 for backups

---

### 2. Required Packages

```bash
composer require spatie/laravel-backup
composer require league/flysystem-aws-s3-v3  # For R2
```

---

### 3. Railpack Configuration
### 3. Dockerfile Configuration

**Important:** In your Dokploy Application Settings, change the **Build Type** to **Dockerfile** and ensure **App Port** is set to **80**.
Also, ensure your `Caddyfile` (if you have one) is configured to listen on port 80, for example:

```caddyfile
:80 {
	php_server
	root * public/
	encode zstd gzip
	file_server
}
```

Create `Dockerfile` (replacing `nixpacks.toml` or `railpack.json`):

```dockerfile
FROM dunglas/frankenphp:php8.4

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 1. Install System Dependencies (Includes pg_dump)
RUN --mount=type=cache,target=/var/lib/apt/lists,sharing=locked \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    apt-get update && apt-get install -y --no-install-recommends \
    postgresql-client \
    git \
    zip \
    unzip \
    curl \
    gnupg

# 2. Install PHP Extensions
RUN install-php-extensions \
    pdo_pgsql \
    gd \
    intl \
    zip \
    opcache

# 3. Install Node.js (Version 22)
RUN --mount=type=cache,target=/var/lib/apt/lists,sharing=locked \
    --mount=type=cache,target=/var/cache/apt,sharing=locked \
    mkdir -p /etc/apt/keyrings \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_22.x nodistro main" | tee /etc/apt/sources.list.d/nodesource.list \
    && apt-get update && apt-get install -y nodejs \
    && npm install -g npm@latest

# 4. Application Setup
WORKDIR /app

# 5. Composer Dependencies (Cached Layer + BuildKit Cache)
COPY composer.json composer.lock ./
ENV COMPOSER_ALLOW_SUPERUSER=1
RUN --mount=type=cache,target=/root/.composer/cache \
    composer install --optimize-autoloader --no-dev --no-scripts --no-interaction

# 6. Node Dependencies (Cached Layer + BuildKit Cache)
COPY package.json package-lock.json ./
RUN --mount=type=cache,target=/root/.npm \
    npm ci

# 7. Copy Application Code & Build (Frequent Changes)
COPY . .
RUN npm run build
RUN composer dump-autoload --optimize --no-scripts

# 8. Permissions & Entrypoint
RUN chmod -R 777 storage bootstrap/cache
RUN chmod +x docker-entrypoint.sh
EXPOSE 80
CMD ["./docker-entrypoint.sh"]
```

---

### 4. Runtime Setup Script

Update `docker-entrypoint.sh` to serve as the start command:

```bash
#!/bin/bash

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Link storage
php artisan storage:link || true

# backend optimization
php artisan optimize:clear
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Runtime setup complete, starting FrankenPHP..."

# Start FrankenPHP
exec frankenphp php-server
```

Make executable: `chmod +x docker-entrypoint.sh`

---

### 5. Database Backup Configuration

**config/backup.php:**

```php
'backup' => [
    'source' => [
        'databases' => ['pgsql'],
    ],
    'destination' => [
        'disks' => ['local', 'r2'],
    ],
],

'notifications' => [
    'mail' => [
        'to' => env('ADMIN_EMAIL'),
    ],
],
```

**config/filesystems.php:**

```php
'r2' => [
    'driver' => 's3',
    'key' => env('CLOUDFLARE_R2_ACCESS_KEY_ID'),
    'secret' => env('CLOUDFLARE_R2_SECRET_ACCESS_KEY'),
    'region' => 'auto',
    'bucket' => env('CLOUDFLARE_R2_BUCKET'),
    'url' => env('CLOUDFLARE_R2_URL'),
    'endpoint' => env('CLOUDFLARE_R2_ENDPOINT'),
    'use_path_style_endpoint' => false,
    'throw' => false,
],
```

---

### 6. Dynamic Database Dumper (Livewire Component)

```php
protected function getDbDumper()
{
    $connection = config('database.default');
    $config = config("database.connections.$connection");

    $dumper = match ($connection) {
        'pgsql' => PostgreSql::create()
            ->setDbName($config['database'])
            ->setUserName($config['username'])
            ->setPassword($config['password'])
            ->setHost($config['host'] ?? '127.0.0.1')
            ->setPort($config['port'] ?? 5432),
        'mysql' => MySql::create()
            ->setDbName($config['database'])
            ->setUserName($config['username'])
            ->setPassword($config['password'])
            ->setHost($config['host'] ?? '127.0.0.1')
            ->setPort($config['port'] ?? 3306),
        default => throw new \Exception("Unsupported database: $connection"),
    };

    // Auto-detect binary path
    if ($connection === 'pgsql') {
        $pgDumpPath = $this->findBinary('pg_dump');
        if ($pgDumpPath) {
            $dumper->setDumpBinaryPath($pgDumpPath);
        }
    }

    return $dumper;
}

protected function findBinary($binaryName)
{
    $path = trim(shell_exec("which $binaryName 2>/dev/null") ?? '');
    if ($path && file_exists($path)) {
        return $path;
    }

    if (file_exists('/nix/store')) {
        $path = trim(shell_exec("find /nix/store -name $binaryName -type f 2>/dev/null | head -1") ?? '');
        if ($path && file_exists($path)) {
            return $path;
        }
    }

    return null;
}
```

---

### 7. Environment Variables

#### Required Nixpacks Variables (In Dokploy)

**CRITICAL:** Add these in Dokploy Dashboard → Environment tab:

```env
NIXPACKS_APT_PKGS=postgresql-client
NIXPACKS_DEBIAN=1
```

#### Application Environment Variables

```env
# App
APP_NAME=YourApp
APP_ENV=production
APP_KEY=base64:...
APP_DEBUG=false
APP_URL=https://your-domain.com

# Database
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your_db
DB_USERNAME=your_user
DB_PASSWORD=your_password

# Email (centralized)
ADMIN_EMAIL=admin@example.com
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_FROM_ADDRESS=noreply@example.com
MAIL_FROM_NAME="${APP_NAME}"

# Cloudflare R2
CLOUDFLARE_R2_ACCOUNT_ID=your-account-id
CLOUDFLARE_R2_ACCESS_KEY_ID=your-access-key
CLOUDFLARE_R2_SECRET_ACCESS_KEY=your-secret-key
CLOUDFLARE_R2_BUCKET=your-bucket
CLOUDFLARE_R2_ENDPOINT=https://your-account-id.r2.cloudflarestorage.com
CLOUDFLARE_R2_URL=https://your-public-url.r2.dev

# Storage
FILESYSTEM_DISK=r2
```

---

### 8. Troubleshooting

#### pg_dump NOT found

**CORRECT Solution** (Dockerfile):

Use the provided `Dockerfile` which includes:
```dockerfile
RUN apt-get update && apt-get install -y postgresql-client
```

#### Bad Gateway

**Cause**: Build script trying to run `npm` at runtime.

**Solution**: Remove `npm` commands from runtime script. They belong in Nixpacks build phase only.

#### Diagnostic Tool

Create `public/test_env.php`:

```php
<?php
echo "<h1>Environment Debug</h1>";

echo "<h2>PHP Extensions</h2>";
$extensions = get_loaded_extensions();
echo in_array('zip', $extensions) ? "<p style='color:green'>✅ zip loaded</p>" : "<p style='color:red'>❌ zip MISSING</p>";
echo in_array('pdo_pgsql', $extensions) ? "<p style='color:green'>✅ pdo_pgsql loaded</p>" : "<p style='color:red'>❌ pdo_pgsql MISSING</p>";

echo "<h2>System Binaries</h2>";
function check_binary($name) {
    $path = shell_exec("which $name 2>&1");
    if ($path && !str_contains($path, 'not found')) {
        echo "<p style='color:green'>✅ $name found at: " . htmlspecialchars(trim($path)) . "</p>";
    } else {
        echo "<p style='color:red'>❌ $name NOT found</p>";
    }
}

check_binary('pg_dump');
check_binary('zip');

echo "<h2>PATH</h2>";
echo "<pre>" . htmlspecialchars(getenv('PATH')) . "</pre>";
```

Visit `https://your-domain.com/test_env.php` to check.

---

### 9. Best Practices

1. **Separate Build vs Runtime**:
   - Build: `npm install`, `npm build`, `composer install`
   - Runtime: permissions, storage linking, package installation (fallback)

2. **Environment Variables**:
   - Never hardcode secrets
   - Use `ADMIN_EMAIL` for all admin-related emails

3. **Monitor Logs**:
   - Dokploy → Your App → Logs
   - Watch for errors during build and runtime

4. **Incremental Deploys**:
   - Push small changes
   - Test after each deployment

---

### 10. Quick Checklist

- [ ] **Dokploy**: Build Type set to **Dockerfile**
- [ ] `Dockerfile` created with optimizations
- [ ] `docker-entrypoint.sh` created and executable
- [ ] Env variables set (DB, R2, Email)
- [ ] Deploy and verify!

---

**Last Updated:** 2024-12-10
**Status:** Verified Working (Dockerfile Method)
