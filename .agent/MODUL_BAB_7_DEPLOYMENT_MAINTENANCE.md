# BAB VII
# DEPLOYMENT DAN MAINTENANCE

---

## 7.1 Persiapan Deployment

Sebelum deploy aplikasi ke production, ada beberapa persiapan yang harus dilakukan untuk memastikan aplikasi berjalan dengan baik dan aman.

### 7.1.1 Environment Configuration

Environment configuration menentukan bagaimana aplikasi berperilaku di production.

**Production .env File:**
```env
# Application
APP_NAME="Seblak Umi AI"
APP_ENV=production
APP_KEY=base64:GENERATED_KEY_HERE
APP_DEBUG=false  # PENTING: Set false di production
APP_URL=https://seblakumiai.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=seblak_umi_ai
DB_USERNAME=production_user
DB_PASSWORD=strong_password_here

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Redis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

# Mail
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@seblakumiai.com
MAIL_FROM_NAME="${APP_NAME}"

# Midtrans
MIDTRANS_MERCHANT_ID=your_merchant_id
MIDTRANS_CLIENT_KEY=your_client_key
MIDTRANS_SERVER_KEY=your_server_key
MIDTRANS_IS_PRODUCTION=true  # Set true untuk production
MIDTRANS_IS_SANITIZED=true
MIDTRANS_IS_3DS=true

# AWS S3 (Optional - untuk file storage)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=ap-southeast-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

# Security
SESSION_SECURE_COOKIE=true  # HTTPS only
SESSION_HTTP_ONLY=true
SESSION_SAME_SITE=lax
```

**Generate Application Key:**
```bash
php artisan key:generate
```

**Security Checklist:**
- ✅ APP_DEBUG=false
- ✅ APP_ENV=production
- ✅ Strong APP_KEY generated
- ✅ Strong database password
- ✅ HTTPS enabled (SESSION_SECURE_COOKIE=true)
- ✅ Remove development dependencies
- ✅ Disable error display

### 7.1.2 Database Migration

Pastikan semua migrations sudah dijalankan di production database.

**Migration Strategy:**

**1. Backup Database:**
```bash
# Backup existing database
mysqldump -u username -p database_name > backup_$(date +%Y%m%d_%H%M%S).sql
```

**2. Run Migrations:**
```bash
# Run all pending migrations
php artisan migrate --force

# Atau dengan seeding (hati-hati di production!)
php artisan migrate:fresh --seed --force
```

**3. Check Migration Status:**
```bash
php artisan migrate:status
```

**4. Rollback Strategy:**
```bash
# Rollback last migration batch
php artisan migrate:rollback

# Rollback specific steps
php artisan migrate:rollback --step=2

# Reset all migrations
php artisan migrate:reset
```

**Best Practices:**
- Test migrations di staging environment dulu
- Backup database sebelum migrate
- Run migrations saat traffic rendah
- Monitor untuk errors
- Siapkan rollback plan

### 7.1.3 Asset Compilation

Compile dan optimize assets untuk production.

**Compile Assets:**
```bash
# Install dependencies
npm install

# Build untuk production (minified, optimized)
npm run production

# Verify compiled assets
ls -lh public/css/
ls -lh public/js/
```

**Laravel Mix Production Build:**
```javascript
// webpack.mix.js
const mix = require('laravel-mix');

if (mix.inProduction()) {
    mix.version(); // Cache busting
    mix.sourceMaps(); // Source maps untuk debugging
}

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
       require('autoprefixer'),
   ])
   .minify('public/js/app.js')
   .minify('public/css/app.css');
```

**Optimize Images:**
```bash
# Install image optimization tools
npm install -g imagemin-cli

# Optimize images
imagemin public/images/* --out-dir=public/images/optimized
```

### 7.1.4 Security Checklist

**Pre-Deployment Security Checklist:**

✅ **Application Security:**
- [ ] APP_DEBUG=false
- [ ] Remove development packages
- [ ] Update all dependencies
- [ ] Check for security vulnerabilities
- [ ] Enable HTTPS
- [ ] Set secure session cookies
- [ ] Configure CORS properly
- [ ] Implement rate limiting

✅ **Database Security:**
- [ ] Strong database password
- [ ] Restrict database access
- [ ] Enable SSL for database connection
- [ ] Regular backups configured
- [ ] Limit user privileges

✅ **File Permissions:**
```bash
# Set proper permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache
chmod 644 .env

# Storage should be writable
chmod -R 775 storage
chown -R www-data:www-data storage
```

✅ **Server Security:**
- [ ] Firewall configured
- [ ] SSH key authentication
- [ ] Disable root login
- [ ] Keep server updated
- [ ] Install fail2ban
- [ ] Configure SSL certificate

✅ **Code Security:**
- [ ] No sensitive data in code
- [ ] No debug statements
- [ ] Input validation everywhere
- [ ] CSRF protection enabled
- [ ] XSS protection enabled
- [ ] SQL injection prevention

**Security Scan:**
```bash
# Check for known vulnerabilities
composer audit

# Check npm packages
npm audit

# Fix vulnerabilities
npm audit fix
```

---

## 7.2 Deployment Process

### 7.2.1 Server Requirements

**Minimum Server Requirements:**

**PHP Requirements:**
- PHP >= 8.1
- BCMath PHP Extension
- Ctype PHP Extension
- Fileinfo PHP Extension
- JSON PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PDO PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- GD PHP Extension
- cURL PHP Extension

**Web Server:**
- Apache 2.4+ atau Nginx 1.18+
- MySQL 8.0+ atau MariaDB 10.3+
- Redis (recommended untuk caching)

**System Resources:**
- RAM: Minimum 1GB, Recommended 2GB+
- Storage: Minimum 10GB
- CPU: 1 core minimum, 2+ cores recommended

**Check PHP Version:**
```bash
php -v
php -m  # List installed extensions
```

### 7.2.2 Deployment ke Shared Hosting

Deployment ke shared hosting (seperti cPanel) untuk budget-friendly option.

**Steps:**

**1. Prepare Files:**
```bash
# Di local machine
# Compile assets
npm run production

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Create zip file
zip -r seblak-umi-ai.zip . -x "node_modules/*" ".git/*" "tests/*"
```

**2. Upload Files:**
- Upload via FTP/SFTP atau cPanel File Manager
- Extract files di server
- Move public folder contents ke public_html

**3. Configure .htaccess:**
```apache
# public_html/.htaccess
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>

# public_html/public/.htaccess
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

    # Redirect Trailing Slashes
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_URI} (.+)/$
    RewriteRule ^ %1 [L,R=301]

    # Send Requests To Front Controller
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]
</IfModule>
```

**4. Setup Database:**
- Create database via cPanel
- Import database dump
- Update .env with database credentials

**5. Set Permissions:**
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

**6. Create Symbolic Link:**
```bash
php artisan storage:link
```

### 7.2.3 Deployment ke VPS

Deployment ke VPS memberikan kontrol penuh atas server.

**Setup VPS (Ubuntu 22.04):**

**1. Update System:**
```bash
sudo apt update
sudo apt upgrade -y
```

**2. Install LAMP Stack:**
```bash
# Install Apache
sudo apt install apache2 -y

# Install MySQL
sudo apt install mysql-server -y
sudo mysql_secure_installation

# Install PHP 8.1
sudo apt install php8.1 php8.1-fpm php8.1-mysql php8.1-mbstring \
    php8.1-xml php8.1-bcmath php8.1-curl php8.1-gd php8.1-zip \
    php8.1-redis -y

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install nodejs -y

# Install Redis
sudo apt install redis-server -y
```

**3. Configure Apache:**
```bash
# Create virtual host
sudo nano /etc/apache2/sites-available/seblakumiai.conf
```

```apache
<VirtualHost *:80>
    ServerName seblakumiai.com
    ServerAlias www.seblakumiai.com
    ServerAdmin admin@seblakumiai.com
    DocumentRoot /var/www/seblakumiai/public

    <Directory /var/www/seblakumiai/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/seblakumiai_error.log
    CustomLog ${APACHE_LOG_DIR}/seblakumiai_access.log combined
</VirtualHost>
```

```bash
# Enable site and modules
sudo a2ensite seblakumiai.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

**4. Deploy Application:**
```bash
# Clone repository atau upload files
cd /var/www
sudo git clone https://github.com/username/seblak-umi-ai.git seblakumiai

# Set permissions
sudo chown -R www-data:www-data /var/www/seblakumiai
sudo chmod -R 755 /var/www/seblakumiai/storage
sudo chmod -R 755 /var/www/seblakumiai/bootstrap/cache

# Install dependencies
cd /var/www/seblakumiai
composer install --optimize-autoloader --no-dev
npm install
npm run production

# Setup environment
cp .env.example .env
nano .env  # Edit configuration
php artisan key:generate

# Run migrations
php artisan migrate --force

# Create storage link
php artisan storage:link

# Optimize
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

**5. Setup SSL with Let's Encrypt:**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache -y

# Get SSL certificate
sudo certbot --apache -d seblakumiai.com -d www.seblakumiai.com

# Auto-renewal
sudo certbot renew --dry-run
```

### 7.2.4 Deployment ke Cloud (AWS/GCP/Azure)

**AWS Deployment Example:**

**1. Setup EC2 Instance:**
- Launch EC2 instance (Ubuntu 22.04)
- Configure security groups (HTTP, HTTPS, SSH)
- Assign Elastic IP

**2. Setup RDS Database:**
- Create RDS MySQL instance
- Configure security groups
- Note endpoint and credentials

**3. Setup S3 for File Storage:**
```php
// config/filesystems.php
's3' => [
    'driver' => 's3',
    'key' => env('AWS_ACCESS_KEY_ID'),
    'secret' => env('AWS_SECRET_ACCESS_KEY'),
    'region' => env('AWS_DEFAULT_REGION'),
    'bucket' => env('AWS_BUCKET'),
],
```

**4. Setup CloudFront CDN:**
- Create CloudFront distribution
- Point to S3 bucket
- Configure caching rules

**5. Deploy Application:**
```bash
# SSH to EC2
ssh -i key.pem ubuntu@ec2-ip-address

# Follow VPS deployment steps
# Update .env with RDS credentials
```

### 7.2.5 Domain dan SSL Configuration

**Domain Setup:**

**1. Point Domain to Server:**
```
A Record: @ -> Server IP Address
A Record: www -> Server IP Address
```

**2. SSL Certificate (Let's Encrypt):**
```bash
# Install Certbot
sudo apt install certbot python3-certbot-apache

# Get certificate
sudo certbot --apache -d yourdomain.com -d www.yourdomain.com

# Verify auto-renewal
sudo systemctl status certbot.timer
```

**3. Force HTTPS:**
```php
// app/Providers/AppServiceProvider.php
public function boot()
{
    if (config('app.env') === 'production') {
        URL::forceScheme('https');
    }
}
```

```apache
# .htaccess - Force HTTPS
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

---

## 7.3 Monitoring dan Logging

### 7.3.1 Error Logging

Laravel menyediakan robust logging system.

**Configure Logging:**
```php
// config/logging.php
'channels' => [
    'stack' => [
        'driver' => 'stack',
        'channels' => ['single', 'slack'],
    ],
    
    'single' => [
        'driver' => 'single',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
    ],
    
    'daily' => [
        'driver' => 'daily',
        'path' => storage_path('logs/laravel.log'),
        'level' => env('LOG_LEVEL', 'debug'),
        'days' => 14,
    ],
    
    'slack' => [
        'driver' => 'slack',
        'url' => env('LOG_SLACK_WEBHOOK_URL'),
        'username' => 'Laravel Log',
        'emoji' => ':boom:',
        'level' => env('LOG_LEVEL', 'critical'),
    ],
],
```

**Custom Logging:**
```php
use Illuminate\Support\Facades\Log;

// Log levels
Log::emergency($message);
Log::alert($message);
Log::critical($message);
Log::error($message);
Log::warning($message);
Log::notice($message);
Log::info($message);
Log::debug($message);

// With context
Log::error('Payment failed', [
    'order_id' => $order->id,
    'user_id' => $user->id,
    'amount' => $amount,
]);
```

### 7.3.2 Performance Monitoring

Monitor aplikasi performance untuk detect issues.

**Laravel Telescope (Development):**
```bash
composer require laravel/telescope --dev
php artisan telescope:install
php artisan migrate
```

**Production Monitoring Tools:**
- New Relic
- Datadog
- Sentry
- Laravel Forge
- Laravel Vapor

**Custom Performance Logging:**
```php
// Measure execution time
$start = microtime(true);

// Your code here

$executionTime = microtime(true) - $start;
Log::info('Operation completed', ['execution_time' => $executionTime]);
```

### 7.3.3 User Analytics

Track user behavior dan usage patterns.

**Google Analytics:**
```blade
<!-- resources/views/layouts/app.blade.php -->
<head>
    <!-- Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=GA_MEASUREMENT_ID"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', 'GA_MEASUREMENT_ID');
    </script>
</head>
```

**Custom Event Tracking:**
```javascript
// Track add to cart
gtag('event', 'add_to_cart', {
    'event_category': 'ecommerce',
    'event_label': productName,
    'value': productPrice
});

// Track purchase
gtag('event', 'purchase', {
    'transaction_id': orderId,
    'value': totalAmount,
    'currency': 'IDR',
    'items': items
});
```

### 7.3.4 Backup Strategy

Regular backups mencegah data loss.

**Database Backup:**
```bash
# Manual backup
mysqldump -u username -p database_name > backup_$(date +%Y%m%d).sql

# Automated backup script
#!/bin/bash
BACKUP_DIR="/var/backups/mysql"
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u username -p'password' database_name | gzip > $BACKUP_DIR/backup_$DATE.sql.gz

# Keep only last 30 days
find $BACKUP_DIR -type f -mtime +30 -delete
```

**Cron Job untuk Auto Backup:**
```bash
# Edit crontab
crontab -e

# Add daily backup at 2 AM
0 2 * * * /path/to/backup-script.sh
```

**Laravel Backup Package:**
```bash
composer require spatie/laravel-backup

# Configure
php artisan vendor:publish --provider="Spatie\Backup\BackupServiceProvider"

# Run backup
php artisan backup:run

# Schedule backup
// app/Console/Kernel.php
protected function schedule(Schedule $schedule)
{
    $schedule->command('backup:clean')->daily()->at('01:00');
    $schedule->command('backup:run')->daily()->at('02:00');
}
```

---

## 7.4 Maintenance dan Update

### 7.4.1 Regular Updates

Keep aplikasi dan dependencies up-to-date.

**Update Laravel:**
```bash
# Check current version
php artisan --version

# Update composer dependencies
composer update

# Update specific package
composer update laravel/framework
```

**Update NPM Packages:**
```bash
# Check outdated packages
npm outdated

# Update all packages
npm update

# Update specific package
npm update package-name
```

**Security Updates:**
```bash
# Check for vulnerabilities
composer audit
npm audit

# Fix vulnerabilities
npm audit fix
```

### 7.4.2 Bug Fixing Workflow

Systematic approach untuk fix bugs.

**Bug Fix Process:**
1. Identify and reproduce bug
2. Create issue/ticket
3. Fix in development environment
4. Test fix thoroughly
5. Deploy to staging
6. Test in staging
7. Deploy to production
8. Monitor for issues

**Hotfix Deployment:**
```bash
# Create hotfix branch
git checkout -b hotfix/critical-bug

# Make fixes
# Test locally

# Commit and push
git add .
git commit -m "Fix critical bug"
git push origin hotfix/critical-bug

# Merge to main
git checkout main
git merge hotfix/critical-bug
git push origin main

# Deploy to production
```

### 7.4.3 Feature Enhancement

Add new features systematically.

**Feature Development Process:**
1. Plan feature requirements
2. Design database schema changes
3. Create migrations
4. Implement backend logic
5. Create frontend UI
6. Write tests
7. Code review
8. Deploy to staging
9. User testing
10. Deploy to production

### 7.4.4 Database Maintenance

Regular database maintenance untuk optimal performance.

**Optimize Tables:**
```sql
-- Optimize all tables
OPTIMIZE TABLE orders, products, users;

-- Analyze tables
ANALYZE TABLE orders;
```

**Clean Old Data:**
```php
// Delete old logs
DB::table('logs')->where('created_at', '<', now()->subMonths(3))->delete();

// Archive old orders
Order::where('created_at', '<', now()->subYears(2))
    ->where('status', 'delivered')
    ->update(['archived' => true]);
```

---

## 7.5 Troubleshooting Guide

### 7.5.1 Common Issues

**Issue: 500 Internal Server Error**
```bash
# Check error logs
tail -f storage/logs/laravel.log

# Check Apache/Nginx logs
tail -f /var/log/apache2/error.log

# Common fixes:
php artisan config:clear
php artisan cache:clear
chmod -R 775 storage bootstrap/cache
```

**Issue: Database Connection Error**
```bash
# Verify database credentials in .env
# Test connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check MySQL service
sudo systemctl status mysql
sudo systemctl restart mysql
```

**Issue: Permission Denied**
```bash
# Fix permissions
sudo chown -R www-data:www-data /var/www/app
sudo chmod -R 755 storage bootstrap/cache
```

### 7.5.2 Payment Gateway Issues

**Midtrans Connection Issues:**
```php
// Test Midtrans connection
try {
    \Midtrans\Config::$serverKey = config('midtrans.server_key');
    $status = \Midtrans\Transaction::status('test-order-id');
} catch (\Exception $e) {
    Log::error('Midtrans Error: ' . $e->getMessage());
}

// Common issues:
// - Wrong API keys
// - Wrong environment (sandbox vs production)
// - Network/firewall blocking requests
```

### 7.5.3 Database Connection Problems

**Troubleshooting Steps:**
1. Verify credentials
2. Check MySQL service status
3. Test connection from command line
4. Check firewall rules
5. Verify user permissions

### 7.5.4 Performance Issues

**Identify Slow Queries:**
```sql
-- Enable slow query log
SET GLOBAL slow_query_log = 'ON';
SET GLOBAL long_query_time = 2;

-- Check slow queries
SELECT * FROM mysql.slow_log;
```

**Optimize Performance:**
- Enable caching
- Optimize database queries
- Add indexes
- Use CDN
- Enable compression
- Minimize assets

---

*Halaman 141-165 selesai. BAB 7 lengkap!*
