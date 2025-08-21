#!/bin/bash

# Deployment script for Laravel application
# This script ensures case sensitivity compatibility between Windows and Linux

echo "🚀 Starting deployment..."

# Clear all Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear

# Regenerate composer autoload
echo "📦 Regenerating composer autoload..."
composer dump-autoload --optimize

# Clear and regenerate route cache
echo "🛣️  Regenerating route cache..."
php artisan route:cache

# Clear and regenerate config cache
echo "⚙️  Regenerating config cache..."
php artisan config:cache

# Clear and regenerate view cache
echo "👁️  Regenerating view cache..."
php artisan view:cache

# Optimize application
echo "🚀 Optimizing application..."
php artisan optimize

echo "✅ Deployment completed successfully!"
echo "🌐 Application is ready to serve!"
