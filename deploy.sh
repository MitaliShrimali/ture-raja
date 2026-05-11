#!/bin/bash

echo "🚀 Deployment started"

# 🚧 Entering maintenance mode
echo "🚧 Entering maintenance mode..."
php artisan down || true

# 📥 Pulling latest code
echo "📥 Pulling latest code..."
git pull origin main

# 📦 Installing composer dependencies
echo "📦 Installing composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction

# 🧹 Clearing and rebuilding caches (This fixes the Method Not Allowed error)
echo "🧹 Clearing and rebuilding caches..."
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 🏗️ Running database migrations
echo "🏗️ Running database migrations..."
# Using --force because we are in production
php artisan migrate --force

# 🔓 Exiting maintenance mode
echo "🔓 Exiting maintenance mode..."
php artisan up

# 📂 Setting permissions
echo "📂 Setting permissions..."
chmod -R 775 storage bootstrap/cache

echo "✅ Deployment finished successfully!"
