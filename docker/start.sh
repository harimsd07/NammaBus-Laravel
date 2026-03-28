#!/bin/sh
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Seed database (--class flag runs only DatabaseSeeder, safe to run multiple times)
php artisan db:seed --force

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start all processes via supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
