# Laravel + Dokploy Deployment Guide

> **SOLUTION**: Use `railpack.json` to define runtime dependencies like `postgresql-client`.

---

## Guaranteed Fix (Railpack)

Create `railpack.json` in your project root:

```json
{
  "$schema": "https://schema.railpack.com",
  "packages": {
    "php": "8.4",
    "node": "22"
  },
  "deploy": {
    "startCommand": "./fix-production-build.sh && frankenphp run --workers=3 public/index.php",
    "aptPackages": ["postgresql-client", "zip", "unzip"],
    "inputs": [ { "step": "build", "include": ["."] } ]
  }
}
```

**Why this works:**
- `deploy.aptPackages` explicitly installs `postgresql-client` in the final runtime image.
- This ensures `pg_dump` is always available for backups.

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

Create `railpack.json` (replacing `nixpacks.toml`):

```json
{
  "$schema": "https://schema.railpack.com",
  "buildAptPackages": ["postgresql-client", "zip", "unzip", "bison", "re2c", "pkg-config", "autoconf", "build-essential", "libxml2-dev", "libsqlite3-dev", "libcurl4-openssl-dev", "libssl-dev", "zlib1g-dev", "libreadline-dev", "libonig-dev", "libzip-dev", "libpng-dev", "libjpeg-dev", "libfreetype6-dev", "libicu-dev", "libgd-dev", "libxslt1-dev"],
  "packages": {
    "php": "8.4",
    "node": "22"
  },
  "steps": {
    "install": {
      "commands": [
        "composer install --optimize-autoloader --no-dev --no-scripts --no-interaction",
        "npm ci --include=dev || npm install --include=dev"
      ]
    },
    "build": {
      "inputs": [{ "step": "install" }],
      "commands": [
        "npm run build",
        "mkdir -p .frankenphp/conf.d",
        "chmod -R 777 storage bootstrap/cache",
        "php artisan config:cache",
        "php artisan route:cache",
        "php artisan view:cache",
        "php artisan storage:link"
      ]
    }
  },
  "deploy": {
    "startCommand": "./fix-production-build.sh && frankenphp run --workers=3 public/index.php",
    "aptPackages": ["postgresql-client", "zip", "unzip"],
    "inputs": [
      {
        "step": "build",
        "include": ["."]
      }
    ]
  }
}
```

---

### 4. Runtime Setup Script

Create `fix-production-build.sh`:

```bash
#!/bin/bash

# Install PostgreSQL client if missing
if ! command -v pg_dump &> /dev/null; then
    echo "🔧 Installing postgresql-client..."
    apt-get update -qq && apt-get install -y -qq postgresql-client
    echo "✅ PostgreSQL client installed"
fi

# Fix permissions
chmod -R 777 storage bootstrap/cache

# Link storage
php artisan storage:link || true

echo "✅ Runtime setup complete"
```

Make executable: `chmod +x fix-production-build.sh`

**CRITICAL**: DO NOT put `npm build` here - Node.js isn't available at runtime!

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

**CORRECT Solution** (Railpack):

Use `railpack.json` with `deploy.aptPackages`:

```json
"deploy": {
  "aptPackages": ["postgresql-client"]
}
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

- [ ] `nixpacks.toml` configured
- [ ] **CRITICAL**: Environment variables set in Dokploy:
  - `NIXPACKS_APT_PKGS=postgresql-client`
  - `NIXPACKS_DEBIAN=1`
- [ ] Application environment variables set (DB, R2, Email)
- [ ] R2 bucket created and configured
- [ ] Database connected
- [ ] Deploy and verify `test_env.php` shows ✅ pg_dump
- [ ] Test backup downloads
- [ ] Test R2 uploads
- [ ] Test email notifications
- [ ] Delete `test_env.php` from production

---

**Last Updated:** 2024-12-10
**Project:** Toota Art Portfolio
