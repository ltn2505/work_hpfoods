#!/bin/bash

echo "=== HP FOODS - Laravel Deployment Script ==="
echo ""

# 1. Clear all caches
echo "1. Clearing Laravel caches..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear

# 2. Install production dependencies
echo "2. Installing production dependencies..."
composer install --no-dev --optimize-autoloader

# 3. Generate application key if not exists
echo "3. Checking application key..."
if [ -z "$(grep 'APP_KEY=base64:' .env 2>/dev/null)" ]; then
    echo "   Generating new application key..."
    php artisan key:generate
else
    echo "   Application key already exists"
fi

# 4. Create storage link
echo "4. Creating storage link..."
php artisan storage:link

# 5. Optimize for production
echo "5. Optimizing for production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 6. Set proper permissions
echo "6. Setting file permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 644 storage/logs/*.log 2>/dev/null || true

# 7. Create deployment checklist
echo "7. Creating deployment checklist..."
cat > DEPLOYMENT_CHECKLIST.md << 'EOF'
# HP FOODS - Deployment Checklist

## Before Uploading to Hosting:

### 1. Database Configuration
- [ ] Update `.env` file with production database credentials
- [ ] Set `APP_ENV=production`
- [ ] Set `APP_DEBUG=false`
- [ ] Set `APP_URL=https://yourdomain.com` (or http://yourdomain.com)

### 2. File Structure
- [ ] Upload entire project to hosting
- [ ] Ensure `public_html` folder contains:
  - [ ] `.htaccess` file
  - [ ] `index.php` file
  - [ ] All public assets (CSS, JS, images)

### 3. Database Setup
- [ ] Run migrations: `php artisan migrate`
- [ ] Run seeders: `php artisan db:seed`
- [ ] Check if admin user exists

### 4. File Permissions
- [ ] Set `storage` folder permissions to 755
- [ ] Set `bootstrap/cache` folder permissions to 755
- [ ] Ensure log files are writable

### 5. Post-Deployment
- [ ] Test login functionality
- [ ] Test task creation and management
- [ ] Test file uploads
- [ ] Check if reports are working
- [ ] Verify all routes are accessible

## Common Issues & Solutions:

### 500 Internal Server Error
- Check if `.env` file exists and is configured correctly
- Verify database connection
- Check file permissions
- Review error logs in `storage/logs/laravel.log`

### Class Not Found Errors
- Run `composer dump-autoload`
- Clear all caches: `php artisan optimize:clear`
- Check if vendor folder is uploaded

### Permission Denied
- Set proper file permissions
- Ensure web server can write to storage folder

## Contact Support:
If issues persist, check the Laravel logs at: `storage/logs/laravel.log`
EOF

echo "8. Creating .env template..."
cat > .env.template << 'EOF'
APP_NAME="HP FOODS"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database_name
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

MEMCACHED_HOST=127.0.0.1

REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_DEFAULT_REGION=us-east-1
AWS_BUCKET=
AWS_USE_PATH_STYLE_ENDPOINT=false

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_HOST=
PUSHER_PORT=443
PUSHER_SCHEME=https
PUSHER_APP_CLUSTER=mt1

VITE_APP_NAME="${APP_NAME}"
VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
EOF

echo ""
echo "=== Deployment Preparation Complete ==="
echo ""
echo "Next steps:"
echo "1. Review DEPLOYMENT_CHECKLIST.md"
echo "2. Update .env.template with your actual database credentials"
echo "3. Upload files to hosting"
echo "4. Run migrations and seeders on hosting"
echo "5. Test the application"
echo ""
echo "Files created:"
echo "- DEPLOYMENT_CHECKLIST.md (deployment guide)"
echo "- .env.template (environment template)"
echo "- public_html/.htaccess (Apache configuration)"
echo "- public_html/index.php (Laravel bootstrap)"
echo ""
echo "Good luck with your deployment! 🚀"
