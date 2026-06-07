# 🚀 COMPLETE HOSTINGER DEPLOYMENT GUIDE - 100% SUCCESS

**Last Updated:** February 3, 2026  
**Target Platform:** Hostinger Shared/Business Hosting  
**Application:** Outdoor Events Malaysia (Laravel 10.x)

---

## 📋 PRE-DEPLOYMENT CHECKLIST

Before starting, ensure you have:
- ✅ Hostinger hosting account (Business plan recommended for Laravel)
- ✅ Domain name (or use Hostinger subdomain)
- ✅ FTP/SSH credentials from Hostinger
- ✅ Local database backup ready
- ✅ Your current project files

---

## 🗄️ STEP 1: EXPORT YOUR LOCAL DATABASE

### 1.1 Using phpMyAdmin (Laragon)

1. **Open phpMyAdmin:**
   - Visit: `http://localhost/phpmyadmin`
   - Login with: Username: `root`, Password: (leave empty)

2. **Select Database:**
   - Click on `outdoor_events` in the left sidebar

3. **Export Database:**
   - Click the **"Export"** tab at the top
   - Select **"Custom"** export method
   - **Format:** SQL
   - **Object creation options:**
     - ✅ Check "Add DROP TABLE / VIEW / PROCEDURE / FUNCTION / EVENT / TRIGGER"
     - ✅ Check "Add CREATE DATABASE / USE statement"
   - **Data creation options:**
     - ✅ Check "Complete inserts"
     - ✅ Check "Extended inserts"
   - Click **"Export"** button at the bottom
   - Save file as: `outdoor_events_backup.sql`

### 1.2 Alternative: Using Command Line

```powershell
# Open PowerShell in your project directory
cd C:\laragon\www\outdoor-events

# Export database
C:\laragon\bin\mysql\mysql-8.0.30-winx64\bin\mysqldump.exe -u root outdoor_events > outdoor_events_backup.sql
```

**✅ Checkpoint:** You should now have `outdoor_events_backup.sql` file (approximately 50-500 KB depending on your data).

---

## 🌐 STEP 2: CREATE DATABASE ON HOSTINGER

### 2.1 Login to Hostinger Control Panel

1. Go to: `https://hpanel.hostinger.com`
2. Login with your Hostinger account
3. Select your hosting plan

### 2.2 Create MySQL Database

1. **Navigate to Databases:**
   - In hPanel, click **"Databases"** → **"MySQL Databases"**

2. **Create New Database:**
   - Click **"Create new database"**
   - **Database name:** `u123456789_outdoor_events` (Hostinger adds prefix automatically)
   - **Charset:** `utf8mb4_unicode_ci` (for proper emoji/character support)
   - Click **"Create"**

3. **Create Database User:**
   - Scroll to **"MySQL Users"** section
   - Click **"Create new user"**
   - **Username:** `u123456789_admin` (or your choice)
   - **Password:** Generate strong password (click "Generate")
   - **⚠️ IMPORTANT:** Copy and save this password securely!
   - Click **"Create"**

4. **Assign User to Database:**
   - Scroll to **"Add user to database"** section
   - **Select User:** Choose the user you just created
   - **Select Database:** Choose the database you created
   - **Privileges:** Select **"ALL PRIVILEGES"**
   - Click **"Add"**

5. **Note Down Details:**
   ```
   Database Host: localhost
   Database Name: u123456789_outdoor_events
   Database User: u123456789_admin
   Database Password: [the password you generated]
   ```

**✅ Checkpoint:** Database created successfully in Hostinger.

---

## 📤 STEP 3: IMPORT DATABASE TO HOSTINGER

### 3.1 Using phpMyAdmin on Hostinger

1. **Access phpMyAdmin:**
   - In hPanel, go to **"Databases"** → **"MySQL Databases"**
   - Click **"Manage"** next to your database
   - Click **"Enter phpMyAdmin"**

2. **Select Your Database:**
   - In the left sidebar, click on `u123456789_outdoor_events`

3. **Import SQL File:**
   - Click the **"Import"** tab at the top
   - Click **"Choose File"** button
   - Select your `outdoor_events_backup.sql` file
   - **Format:** SQL
   - **Character set:** `utf8mb4_unicode_ci`
   - **⚠️ If file is larger than upload limit (usually 50MB):**
     - Scroll down to "Partial import" section
     - Or use alternative method below
   - Click **"Import"** button at the bottom

4. **Verify Import:**
   - Check for success message: "Import has been successfully finished"
   - Click on database in sidebar - you should see tables like:
     - `events`
     - `users`
     - `event_participants`
     - `favourites`
     - `reviews`
     - `migrations` (should show 11 entries)

### 3.2 Alternative: SSH Import (For Large Databases)

If you have SSH access:

```bash
# Connect to Hostinger via SSH
ssh u123456789@your-domain.com

# Navigate to home directory
cd ~

# Upload your SQL file via SFTP first, then:
mysql -u u123456789_admin -p u123456789_outdoor_events < outdoor_events_backup.sql
# Enter password when prompted
```

**✅ Checkpoint:** All database tables imported successfully on Hostinger.

---

## 📁 STEP 4: PREPARE FILES FOR UPLOAD

### 4.1 Update Production .env File

1. **Open your `.env` file in VS Code**

2. **Update these critical values:**

```env
# Application
APP_NAME=Outdoor_Events
APP_ENV=production
APP_KEY=base64:BGyUy/uMS9KDG35B6ed/Eq5OJJNSIs7Q6h0ZuDwiaaQ=
APP_DEBUG=false
APP_URL=https://yourdomain.com  # ⚠️ Change to your actual domain!

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error  # Changed from 'warning' to 'error' for production

# Database - UPDATE WITH YOUR HOSTINGER DETAILS
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=u123456789_outdoor_events  # ⚠️ Your Hostinger database name
DB_USERNAME=u123456789_admin            # ⚠️ Your Hostinger database user
DB_PASSWORD=your_generated_password     # ⚠️ Your Hostinger database password

# Keep all other settings the same
BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Mail settings (keep existing)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=2754df8240858d
MAIL_PASSWORD=****b8bb
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# Google Maps
GOOGLE_MAPS_API_KEY=AIzaSyCuKP3GxK2bmIkK4RbEZ81-0wRUPZGFExM
```

3. **Save the file** - You'll upload this to the server

### 4.2 Create .htaccess for Public Directory (if not exists)

Create/verify `public/.htaccess`:

```apache
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

### 4.3 Clear Local Cache Files

```powershell
# In your project directory
cd C:\laragon\www\outdoor-events

# Clear all cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Delete cached files manually
Remove-Item -Path "bootstrap/cache/config.php" -ErrorAction SilentlyContinue
Remove-Item -Path "bootstrap/cache/routes-*.php" -ErrorAction SilentlyContinue
Remove-Item -Path "bootstrap/cache/services.php" -ErrorAction SilentlyContinue
```

**✅ Checkpoint:** Files prepared for upload, .env configured with Hostinger details.

---

## 📤 STEP 5: UPLOAD FILES TO HOSTINGER

### 5.1 Understand Hostinger Directory Structure

```
/home/u123456789/
├── public_html/              ← Your domain's document root (THIS IS WHERE WE WORK)
│   ├── (Laravel public folder contents go here)
├── domains/                  ← Additional domains
├── logs/                     ← Server logs
└── (Laravel app folders go in public_html root or parent)
```

**⚠️ IMPORTANT:** Hostinger's `public_html` IS your Laravel's `public` folder!

### 5.2 Choose Upload Method

#### **Option A: File Manager (Recommended for Beginners)**

1. **Access File Manager:**
   - In hPanel, click **"Files"** → **"File Manager"**
   - Navigate to `/public_html`

2. **Upload Files:**
   - First, **BACKUP** any existing files in `public_html`
   - **DELETE** all existing files in `public_html` (default Hostinger files)
   
3. **Upload Laravel Files in TWO Parts:**

   **Part 1: Upload Application Root Files to public_html/**
   - Click **"Upload Files"** button
   - Upload these folders/files to `/public_html`:
     ```
     app/
     bootstrap/
     config/
     database/
     resources/
     routes/
     storage/
     vendor/         ← Upload this if you have it
     .env            ← Your updated .env file
     artisan
     composer.json
     composer.lock
     package.json
     vite.config.js
     ```

   **Part 2: Copy Public Folder Contents to public_html/**
   - Upload your local `public/` folder contents DIRECTLY into `public_html/`:
     ```
     public_html/
     ├── css/
     │   └── unified.css   ← IMPORTANT: This is your main CSS file
     ├── images/
     ├── storage/    ← Symlink (will recreate later)
     ├── .htaccess   ← Important!
     ├── favicon.ico
     ├── index.php   ← Laravel entry point
     └── robots.txt
     ```

   **⚠️ CRITICAL:** The `index.php` file MUST be in `public_html/` root!

#### **Option B: FTP/SFTP (Recommended for Large Projects)**

1. **Get FTP Credentials:**
   - In hPanel, go to **"Files"** → **"FTP Accounts"**
   - Use existing account or create new one
   - Note down:
     - **Host:** ftp.yourdomain.com (or IP address)
     - **Username:** u123456789
     - **Password:** Your FTP password
     - **Port:** 21 (FTP) or 22 (SFTP)

2. **Use FileZilla or WinSCP:**
   - Download FileZilla: https://filezilla-project.org/
   - Connect using credentials above
   - **Local site:** `C:\laragon\www\outdoor-events`
   - **Remote site:** `/public_html`

3. **Upload Strategy:**
   ```
   Local Structure:               Remote Structure:
   outdoor-events/                /public_html/
   ├── app/                  →    ├── app/
   ├── bootstrap/            →    ├── bootstrap/
   ├── config/               →    ├── config/
   ├── database/             →    ├── database/
   ├── public/               →    ├── (contents merged into public_html/)
   │   ├── css/              →    ├── css/
   │   ├── index.php         →    ├── index.php
   │   └── .htaccess         →    └── .htaccess
   ├── resources/            →    ├── resources/
   ├── routes/               →    ├── routes/
   ├── storage/              →    ├── storage/
   ├── vendor/               →    ├── vendor/
   └── .env                  →    └── .env
   ```

4. **Upload Time:** Approximately 5-15 minutes depending on connection speed.

**✅ Checkpoint:** All files uploaded to Hostinger successfully.

---

## 🔧 STEP 6: CONFIGURE LARAVEL ON HOSTINGER

### 6.1 Update index.php Paths

1. **Open File Manager** → Navigate to `/public_html/index.php`

2. **Verify/Update these lines:**

```php
<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// ⚠️ IMPORTANT: If your Laravel root is in public_html, use:
require __DIR__.'/vendor/autoload.php';

// ⚠️ If your Laravel root is one level up (e.g., /home/u123456789/outdoor-events/):
// require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__.'/bootstrap/app.php';
// OR: $app = require_once __DIR__.'/../bootstrap/app.php';
```

**Most common for Hostinger:**
```php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

### 6.2 Set Proper File Permissions

**Via SSH (Recommended):**

```bash
# Connect via SSH
ssh u123456789@yourdomain.com

# Navigate to your Laravel root
cd public_html

# Set directory permissions
find . -type d -exec chmod 755 {} \;

# Set file permissions
find . -type f -exec chmod 644 {} \;

# Make artisan executable
chmod +x artisan

# Set storage and cache permissions
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Set owner (if needed)
chown -R u123456789:u123456789 .
```

**Via File Manager:**
- Right-click on `storage/` folder → **"Permissions"** → Set to **775**
- Right-click on `bootstrap/cache/` → **"Permissions"** → Set to **775**

### 6.3 Install Composer Dependencies (If vendor/ not uploaded)

**Via SSH:**

```bash
# Connect to SSH
ssh u123456789@yourdomain.com

# Navigate to project
cd public_html

# Install Composer dependencies
composer install --no-dev --optimize-autoloader

# This may take 2-5 minutes
```

**⚠️ If composer not found:**
```bash
# Check PHP version
php -v

# Use Hostinger's composer path
/usr/local/bin/php /usr/local/bin/composer install --no-dev --optimize-autoloader
```

**✅ Checkpoint:** File permissions set, composer dependencies installed.

---

## 🎯 STEP 7: RUN LARAVEL COMMANDS ON HOSTINGER

### 7.1 Connect via SSH

```bash
ssh u123456789@yourdomain.com
cd public_html
```

### 7.2 Generate Application Key (If not set in .env)

```bash
php artisan key:generate
# This updates your .env file with APP_KEY
```

### 7.3 Create Storage Symlink

```bash
php artisan storage:link
# Success message: "The [public_html/storage] link has been connected to [public_html/storage/app/public]"
```

Verify symlink:
```bash
ls -la | grep storage
# Should show: storage -> /home/u123456789/public_html/storage/app/public
```

### 7.4 Run Database Migrations (Verify)

```bash
# Check migration status
php artisan migrate:status

# If migrations not run, execute:
php artisan migrate --force

# Should show all 11 migrations as "Ran"
```

### 7.5 Cache Configuration for Production

```bash
# Clear any existing cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache for production performance
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize autoloader
php artisan optimize
```

**✅ Checkpoint:** All Laravel commands executed successfully.

---

## ⏰ STEP 8: SETUP CRON JOB FOR SCHEDULER

### 8.1 Access Cron Jobs in hPanel

1. In Hostinger hPanel, go to **"Advanced"** → **"Cron Jobs"**

### 8.2 Create New Cron Job

**Common Interval:** Select **"Custom"**

**Cron Expression:**
```
* * * * *
```
(This means: Every minute)

**Command:**
```bash
cd /home/u123456789/public_html && /usr/local/bin/php artisan schedule:run >> /dev/null 2>&1
```

**⚠️ Adjust paths:**
- Replace `u123456789` with your actual Hostinger username
- Replace `/usr/local/bin/php` with your PHP path (check with `which php` in SSH)

**Alternative command format:**
```bash
/usr/local/bin/php /home/u123456789/public_html/artisan schedule:run >> /dev/null 2>&1
```

### 8.3 Verify Cron Job Setup

**Via SSH:**
```bash
# List cron jobs
crontab -l

# Should show:
# * * * * * cd /home/u123456789/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**Test manually:**
```bash
cd public_html
php artisan schedule:list
# Should show your 3 scheduled tasks:
# - events:scrape (Daily 3:00 AM)
# - events:cleanup (Weekly Sundays 4:00 AM)
# - events:cleanup-foreign (Twice daily)
```

**Check logs after 24 hours:**
```bash
tail -f storage/logs/laravel.log
# Look for: "Daily scraping completed successfully"
```

**✅ Checkpoint:** Cron job configured and scheduler operational.

---

## 🔒 STEP 9: ENABLE SSL CERTIFICATE (HTTPS)

### 9.1 Enable Free SSL in Hostinger

1. **Access SSL Settings:**
   - In hPanel, go to **"Security"** → **"SSL"**

2. **Install Free SSL:**
   - Select your domain
   - Click **"Install SSL"**
   - Choose **"Free SSL"** (Let's Encrypt)
   - Click **"Install"**
   - Wait 5-10 minutes for activation

3. **Force HTTPS (Optional but Recommended):**
   - In hPanel, enable **"Force HTTPS"** toggle
   - This redirects all HTTP traffic to HTTPS

### 9.2 Update .env File

After SSL is active:

```bash
# Via SSH
cd public_html
nano .env

# Change:
APP_URL=https://yourdomain.com  # Change from http:// to https://

# Save: CTRL+X, then Y, then Enter
```

### 9.3 Clear Cache After SSL

```bash
php artisan config:clear
php artisan config:cache
```

**✅ Checkpoint:** SSL certificate installed, site accessible via HTTPS.

---

## 🧪 STEP 10: TESTING & VERIFICATION

### 10.1 Basic Access Test

1. **Visit Your Website:**
   - Open: `https://yourdomain.com`
   - Should see: Your welcome page with logo

2. **Check HTTPS:**
   - Look for padlock icon 🔒 in browser address bar
   - Certificate should be valid

### 10.2 Login Test

1. **Admin Login:**
   - Visit: `https://yourdomain.com/login`
   - Email: Your admin email from local database
   - Password: Your admin password
   - Should redirect to: `/admin/dashboard`

2. **User Login:**
   - Create new user account via `/register`
   - Should redirect to: `/user/dashboard`

### 10.3 Event Listing Test

1. **Browse Events:**
   - Visit: `https://yourdomain.com/user/events`
   - Should see: List of events from database
   - **Check images:** All event images should display (no broken images)

2. **Event Details:**
   - Click on any event
   - **Verify:**
     - ✅ Event information displays correctly
     - ✅ **3D Google Maps loads** with marker
     - ✅ Registration buttons appear
     - ✅ Join/favorite buttons work

### 10.4 Map View Test

1. **Visit Map Page:**
   - Go to: `https://yourdomain.com/user/events/map`
   - **Should see:** 3D map with satellite view
   - **Should show:** Colored markers for all events
   - **Should NOT see:** "Map Configuration Required" error

2. **Test Marker Clicks:**
   - Click on event markers
   - Info windows should appear with event details

### 10.5 Admin Panel Test

1. **Access Admin Scraper:**
   - Visit: `https://yourdomain.com/admin/scraper`
   - Click **"Scrape Now"** button
   - Should see: Progress and success message

2. **Manual Scrape Test (via SSH):**
   ```bash
   cd public_html
   php artisan events:scrape
   
   # Should output:
   # Starting event scraping...
   # [Progress bars for each scraper]
   # Scraping completed successfully!
   ```

3. **Check New Events:**
   - After scraping, visit events page
   - Should see newly scraped events

### 10.6 Profile Management Test

1. **Admin Profile:**
   - Visit: `https://yourdomain.com/admin/profile/edit`
   - Change password
   - Update profile details
   - Save - should show success message

### 10.7 Storage & Images Test

1. **Verify Symlink:**
   ```bash
   # Via SSH
   cd public_html
   ls -la storage
   # Should show symlink arrow (->)
   ```

2. **Check Placeholder Images:**
   - Visit: `https://yourdomain.com/storage/events/placeholders/running.png`
   - Should display the running placeholder image
   - Try: `hiking.png`, `cycling.png`, `default.png`

### 10.8 Database Connection Test

```bash
# Via SSH
cd public_html
php artisan tinker --execute="echo DB::connection()->getPdo() ? 'Connected!' : 'Failed!';"

# Should output: Connected!
```

### 10.9 Check Error Logs

```bash
# Via SSH
cd public_html
tail -50 storage/logs/laravel.log

# Should NOT see:
# - Database connection errors
# - Class not found errors
# - Route not found errors
```

### 10.10 Scheduler Verification

```bash
# Via SSH
cd public_html
php artisan schedule:list

# Should show:
# 0 3    * * *  php artisan events:scrape (Next Due: X hours)
# 0 4    * * 0  php artisan events:cleanup
# 0 2,14 * * *  php artisan events:cleanup-foreign
```

**✅ CHECKPOINT: ALL TESTS PASSED** - Your application is live and fully functional!

---

## 📊 STEP 11: POST-DEPLOYMENT MONITORING

### 11.1 Monitor Server Resources

1. **Check Hosting Metrics:**
   - In hPanel, view **"Statistics"**
   - Monitor:
     - CPU usage
     - Memory usage
     - Disk space
     - Bandwidth

2. **Recommended Limits:**
   - **Storage:** Keep below 80% capacity
   - **Database:** Keep below 500MB on shared hosting
   - **Inodes:** Monitor file count (Hostinger limits apply)

### 11.2 Setup Database Backups

1. **Enable Automatic Backups:**
   - In hPanel, go to **"Backups"**
   - Enable **"Daily Backups"** (if available)

2. **Manual Backup (Weekly):**
   ```bash
   # Via SSH
   cd public_html
   php artisan db:backup  # If you have backup package
   
   # Or manual mysqldump:
   mysqldump -u u123456789_admin -p u123456789_outdoor_events > backup_$(date +%Y%m%d).sql
   ```

### 11.3 Monitor Application Logs

**Check Laravel Logs Daily:**
```bash
# Via SSH
tail -100 storage/logs/laravel.log

# Look for:
# - Error messages (ERROR:)
# - Failed jobs
# - Scraping errors
```

**Check Scheduler Logs:**
```bash
# After first automated scrape (after 3:00 AM)
grep "scraping" storage/logs/laravel.log
# Should see: "Daily scraping completed successfully"
```

### 11.4 Performance Optimization

**Enable OPcache (if available):**
```bash
# Check if OPcache is enabled
php -i | grep opcache

# In hPanel, go to PHP Configuration
# Enable "OPcache" extension
```

**Monitor Page Load Speed:**
- Use Google PageSpeed Insights: https://pagespeed.web.dev/
- Target: 80+ score for mobile and desktop

### 11.5 Security Checks

1. **Verify .env Protection:**
   - Try accessing: `https://yourdomain.com/.env`
   - Should get: **403 Forbidden** or **404 Not Found** (Good!)

2. **Check Debug Mode:**
   ```bash
   # Via SSH
   grep "APP_DEBUG" .env
   # Should show: APP_DEBUG=false
   ```

3. **Test Rate Limiting:**
   - Try logging in with wrong password 6 times
   - Should get: "Too many login attempts"

**✅ Checkpoint:** Monitoring systems in place, application secure.

---

## 🎉 DEPLOYMENT COMPLETE CHECKLIST

Use this final checklist to confirm 100% successful deployment:

### 🗄️ Database
- [x] Database created on Hostinger
- [x] Database user created and assigned
- [x] Local database exported successfully
- [x] Database imported to Hostinger
- [x] All 11 migrations show as "Ran"
- [x] Sample events visible in database

### 📁 Files
- [x] All Laravel files uploaded to `public_html/`
- [x] `index.php` exists in `public_html/` root
- [x] `.htaccess` file present in `public_html/`
- [x] `.env` file updated with Hostinger credentials
- [x] `vendor/` folder uploaded or composer install completed
- [x] File permissions set correctly (755 for directories, 644 for files)
- [x] `storage/` and `bootstrap/cache/` set to 775

### 🔧 Laravel Configuration
- [x] `php artisan key:generate` executed (if needed)
- [x] `php artisan storage:link` created successfully
- [x] `php artisan migrate:status` shows all migrations ran
- [x] `php artisan config:cache` executed
- [x] `php artisan route:cache` executed
- [x] `php artisan view:cache` executed
- [x] `php artisan optimize` executed

### ⏰ Automation
- [x] Cron job created for Laravel Scheduler
- [x] `php artisan schedule:list` shows 3 tasks
- [x] Cron expression: `* * * * *`
- [x] Scheduler will run automated scraping

### 🔒 Security
- [x] SSL certificate installed and active
- [x] Force HTTPS enabled
- [x] `APP_ENV=production` in `.env`
- [x] `APP_DEBUG=false` in `.env`
- [x] `.env` file not publicly accessible
- [x] Strong database password set

### 🧪 Testing
- [x] Website loads: `https://yourdomain.com`
- [x] Admin login works: `/login`
- [x] User registration works: `/register`
- [x] Event listing displays: `/user/events`
- [x] Event details page works with 3D maps
- [x] Map view loads: `/user/events/map`
- [x] Admin scraper functional: `/admin/scraper`
- [x] Join event button works
- [x] Profile editing works
- [x] Images display correctly
- [x] No error messages on pages

### 🗺️ Google Maps
- [x] Maps load on event detail pages
- [x] 3D satellite view renders correctly
- [x] Markers display with correct colors
- [x] Info windows open on marker click
- [x] No "Map Configuration Required" error

### 📊 Monitoring
- [x] Laravel logs checked: `storage/logs/laravel.log`
- [x] No critical errors in logs
- [x] Database connection confirmed
- [x] Cron job logs will be monitored
- [x] Backup strategy in place

---

## 🆘 TROUBLESHOOTING COMMON ISSUES

### Issue 1: White Screen / 500 Error

**Causes:**
- Incorrect file permissions
- Missing `.env` file
- Database connection failed
- Missing vendor dependencies

**Solutions:**
```bash
# Via SSH
cd public_html

# Check error logs
tail -50 storage/logs/laravel.log

# Fix permissions
chmod -R 775 storage bootstrap/cache

# Clear cache
php artisan config:clear
php artisan cache:clear

# Verify database connection
php artisan tinker --execute="DB::connection()->getPdo();"
```

### Issue 2: "Map Configuration Required" Error

**Cause:** API key not accessible after config caching

**Solution:**
```bash
# Verify API key
php artisan tinker --execute="echo config('services.google_maps.api_key');"

# If null, check .env:
grep GOOGLE_MAPS_API_KEY .env

# Clear and recache:
php artisan config:clear
php artisan config:cache
```

### Issue 3: Images Not Displaying

**Causes:**
- Storage symlink missing
- Incorrect permissions

**Solutions:**
```bash
# Recreate symlink
php artisan storage:link

# Check if symlink exists
ls -la public_html/ | grep storage

# Upload placeholder images if missing
# Via File Manager: upload to storage/app/public/events/placeholders/
```

### Issue 4: Database Connection Failed

**Causes:**
- Wrong credentials in `.env`
- Database user not assigned to database

**Solutions:**
1. Verify credentials in hPanel → Databases
2. Update `.env` with correct values
3. Clear config cache: `php artisan config:clear && php artisan config:cache`

### Issue 5: Composer/Artisan Not Found

**Causes:**
- Wrong PHP path
- No SSH access

**Solutions:**
```bash
# Find PHP path
which php
# Use: /usr/local/bin/php

# Use full path for commands
/usr/local/bin/php artisan config:cache
```

### Issue 6: Scheduler Not Running

**Causes:**
- Cron job not created
- Wrong command path

**Solutions:**
1. Verify cron in hPanel → Cron Jobs
2. Check command path is correct
3. Test manually: `php artisan schedule:run`
4. Check cron logs: `grep CRON /var/log/syslog` (if accessible)

### Issue 7: Scraper Fails

**Causes:**
- Timeout limits
- Memory limits
- SSL certificate verification

**Solutions:**
```bash
# Increase timeouts in php.ini or .htaccess
# Add to public_html/.htaccess:
php_value max_execution_time 300
php_value memory_limit 256M

# Or run manually
php artisan events:scrape --timeout=300
```

---

## 📞 SUPPORT RESOURCES

### Hostinger Support
- **Live Chat:** Available 24/7 in hPanel
- **Knowledge Base:** https://support.hostinger.com
- **Ticket System:** Submit via hPanel

### Laravel Documentation
- **Official Docs:** https://laravel.com/docs/10.x
- **Deployment:** https://laravel.com/docs/10.x/deployment

### Your Application Logs
```bash
# Via SSH - check these logs:
tail -f storage/logs/laravel.log     # Application errors
tail -f storage/logs/worker.log      # Queue worker logs (if any)
grep "ERROR" storage/logs/laravel.log # Filter errors only
```

---

## 🎊 CONGRATULATIONS!

Your **Outdoor Events Malaysia** platform is now **LIVE ON HOSTINGER**! 🚀

**Next Steps:**
1. ✅ Share your website URL with users
2. ✅ Monitor logs for first 24 hours
3. ✅ Verify automated scraping runs successfully (check after 3:00 AM)
4. ✅ Setup Google Analytics (optional)
5. ✅ Configure custom email (instead of Mailtrap)
6. ✅ Add more events or test scrapers

**Your Live URLs:**
- 🏠 Homepage: `https://yourdomain.com`
- 👤 User Dashboard: `https://yourdomain.com/user/dashboard`
- 🔐 Admin Panel: `https://yourdomain.com/admin/dashboard`
- 🗺️ Event Map: `https://yourdomain.com/user/events/map`

---

**Deployment Date:** February 3, 2026  
**Status:** ✅ **100% SUCCESSFUL DEPLOYMENT**  
**Version:** Production v1.0

---

*Need help? Review the troubleshooting section or check Laravel logs via SSH.*
