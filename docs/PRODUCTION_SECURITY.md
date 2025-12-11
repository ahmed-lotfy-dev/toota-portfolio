# Production Environment Configuration

## Required Production Settings

Copy these to your production `.env` file to ensure maximum security:

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Session Security
SESSION_SECURE_COOKIE=true
SESSION_ENCRYPT=true
SESSION_LIFETIME=120
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax

# Database (PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=your-db-host
DB_PORT=5432
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_secure_password

# Cloudflare R2
CLOUDFLARE_R2_ACCESS_KEY_ID=your_access_key
CLOUDFLARE_R2_SECRET_ACCESS_KEY=your_secret_key
CLOUDFLARE_R2_BUCKET=your_bucket
CLOUDFLARE_R2_ENDPOINT=https://account-id.r2.cloudflarestorage.com
CLOUDFLARE_R2_URL=https://your-bucket.r2.dev
FILESYSTEM_DISK=r2

# Email Configuration
ADMIN_EMAIL=admin@yourdomain.com
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your_smtp_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# Google OAuth
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URI="${APP_URL}/login/google/callback"

# Backup Security
BACKUP_ARCHIVE_PASSWORD=your_strong_encryption_password
```

## Critical Security Checklist

Before deploying to production:

- [ ] Set `APP_DEBUG=false` (prevents error details from leaking)
- [ ] Set `APP_ENV=production` (disables dev features)
- [ ] Set `SESSION_SECURE_COOKIE=true` (HTTPS-only cookies)
- [ ] Set `SESSION_ENCRYPT=true` (encrypt session data)
- [ ] Configure proper SSL certificate (HTTPS)
- [ ] Set strong `BACKUP_ARCHIVE_PASSWORD`
- [ ] Verify `ADMIN_EMAIL` is set correctly (only this email can login)
- [ ] Ensure `.env` is NOT in version control (check `.gitignore`)
- [ ] Use strong, unique passwords for all credentials
- [ ] Test Google OAuth callback URL matches production domain

## Performance Optimizations

Once deployed, run these commands:

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
npm run build
```

## Monitoring

Set up monitoring for:
- Failed login attempts (potential brute force)
- Backup job failures (email notifications already configured)
- Disk space on R2 bucket
- SSL certificate expiration

## Security Headers (Optional but Recommended)

Consider adding these headers via your web server (Nginx/Apache):

```nginx
add_header X-Frame-Options "SAMEORIGIN";
add_header X-Content-Type-Options "nosniff";
add_header X-XSS-Protection "1; mode=block";
add_header Referrer-Policy "strict-origin-when-cross-origin";
add_header Permissions-Policy "geolocation=(), microphone=(), camera=()";
```

Or in Laravel middleware, add to `bootstrap/app.php`:

```php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        SetLocale::class,
        \Illuminate\Http\Middleware\HandleCors::class,
    ]);
    
    $middleware->appendToGroup('web', function ($request, $next) {
        $response = $next($request);
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        return $response;
    });
})
```
