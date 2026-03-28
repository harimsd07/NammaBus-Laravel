#!/bin/sh
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Seed only our DatabaseSeeder (not factories)
php artisan db:seed --class=DatabaseSeeder --force

# Cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start all processes via supervisor
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
