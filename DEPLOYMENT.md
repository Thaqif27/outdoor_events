# Outdoor Events - Deployment Guide

## Pre-Deployment Checklist

### 1. Environment Configuration

Before deploying to production, ensure you:

- [ ] Copy `.env.production.example` to `.env` on your production server
- [ ] Generate a new APP_KEY: `php artisan key:generate`
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `LOG_LEVEL=error` or `warning`
- [ ] Update `APP_URL` to your production domain
- [ ] Configure database credentials
- [ ] Set up mail service (Mailgun, AWS SES, SendGrid, etc.)
- [ ] Get a NEW Google Maps API key with domain/IP restrictions
- [ ] Configure AWS S3 for file storage (or use local storage)

### 2. Database Setup

```bash
# Run migrations
php artisan migrate --force

# Seed admin user
php artisan db:seed --class=AdminSeeder

# Default Admin Credentials:
# Email: admin@outdoor-events.com
# Password: Admin@123456
# ⚠️ CHANGE THIS PASSWORD IMMEDIATELY AFTER FIRST LOGIN!
```

### 3. File Permissions

```bash
# Set correct permissions for storage and cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Create symbolic link for public storage
php artisan storage:link
```

### 4. Optimize Application

```bash
# Clear and cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# If using Composer 2.x
composer install --optimize-autoloader --no-dev
```

### 5. Web Server Configuration

#### For Apache (.htaccess already included in public/)

Ensure `AllowOverride All` is enabled for the document root.

#### For Nginx

```nginx
server {
    listen 80;
    listen [::]:80;
    server_name yourdomain.com;
    root /var/www/outdoor-events/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

### 6. SSL Certificate

```bash
# Using Let's Encrypt (Certbot)
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com
```

### 7. Scheduled Tasks (Cron Jobs)

If you plan to run automated scrapers, add to crontab:

```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

### 8. Queue Workers (Optional)

If using queue workers for background jobs:

```bash
# Using Supervisor
sudo nano /etc/supervisor/conf.d/outdoor-events-worker.conf
```

Add:

```ini
[program:outdoor-events-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/outdoor-events/artisan queue:work --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/outdoor-events/storage/logs/worker.log
stopwaitsecs=3600
```

Then:

```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start outdoor-events-worker:*
```

## Security Hardening

### 1. Hide Sensitive Files

Ensure `.env`, `.git`, `storage/`, and other sensitive directories are not publicly accessible.

For Apache, add to `.htaccess` (already included in Laravel):

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 2. Google Maps API Key Restrictions

In Google Cloud Console:
- Application restrictions: HTTP referrers
- Add your domain: `yourdomain.com/*`
- API restrictions: Limit to Geocoding API, Maps JavaScript API, Places API

### 3. Database User Privileges

Create a dedicated database user with minimal privileges:

```sql
CREATE USER 'outdoor_events_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT SELECT, INSERT, UPDATE, DELETE ON outdoor_events_production.* TO 'outdoor_events_user'@'localhost';
FLUSH PRIVILEGES;
```

### 4. Enable Laravel Security Features

Already configured in this application:
- ✅ CSRF Protection
- ✅ Rate Limiting on authentication routes
- ✅ XSS Protection via Blade templating
- ✅ SQL Injection protection via Eloquent ORM
- ✅ CORS configuration

### 5. Regular Updates

```bash
# Update dependencies regularly
composer update
npm update

# Check for security vulnerabilities
composer audit
npm audit
```

## Monitoring & Maintenance

### 1. Log Monitoring

Monitor Laravel logs:

```bash
tail -f storage/logs/laravel.log
```

### 2. Database Backups

Set up automated database backups:

```bash
# Example cron job for daily backups
0 2 * * * mysqldump -u username -p'password' outdoor_events_production > /backups/db_$(date +\%Y\%m\%d).sql
```

### 3. Application Monitoring

Consider setting up:
- **Laravel Telescope** (for development/staging)
- **Sentry** or **Bugsnag** (for error tracking)
- **New Relic** or **DataDog** (for performance monitoring)

## Troubleshooting

### Common Issues

1. **"500 Internal Server Error"**
   - Check storage and cache permissions
   - Check `.env` file exists and is readable
   - Check Apache/Nginx error logs

2. **"CSRF Token Mismatch"**
   - Clear application cache: `php artisan cache:clear`
   - Check session configuration in `.env`

3. **Images not displaying**
   - Run `php artisan storage:link`
   - Check file permissions on storage directory

4. **Google Maps not working**
   - Verify API key is correct
   - Check API key restrictions in Google Cloud Console
   - Ensure billing is enabled in Google Cloud

## Post-Deployment Steps

- [ ] Test all major features (registration, login, event creation)
- [ ] Verify email sending works
- [ ] Test scraper functionality (if using)
- [ ] Check Google Maps integration
- [ ] Monitor error logs for the first 24 hours
- [ ] Set up automated backups
- [ ] Document any custom configurations
- [ ] Change default admin password
- [ ] Set up monitoring/alerting

## Contact & Support

For issues or questions about this deployment, refer to:
- Laravel Documentation: https://laravel.com/docs
- Application logs: `storage/logs/laravel.log`
- Server logs: `/var/log/nginx/error.log` or `/var/log/apache2/error.log`

---

**Last Updated:** February 1, 2026
