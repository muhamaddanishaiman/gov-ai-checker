#!/bin/bash
set -e

# Create SQLite database if it doesn't exist
touch /var/www/html/database/database.sqlite

# Ensure storage directories exist with correct permissions
mkdir -p /var/www/html/storage/framework/{sessions,views,cache}
mkdir -p /var/www/html/storage/logs
mkdir -p /var/www/html/bootstrap/cache

chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache /var/www/html/database

# Clear any stale caches from build time
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Run migrations
php artisan migrate --force 2>/dev/null || true

# Cache config/routes/views using RUNTIME env vars
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Update Apache port from Cloud Run's PORT env var
sed -i "s/Listen 8080/Listen ${PORT:-8080}/g" /etc/apache2/ports.conf
sed -i "s/:8080/:${PORT:-8080}/g" /etc/apache2/sites-available/000-default.conf

# Start Apache
exec apache2-foreground
