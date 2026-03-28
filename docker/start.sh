#!/bin/sh
set -e

cd /var/www/html

# Run migrations
php artisan migrate --force

# Clear and cache config for production
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start supervisor (runs nginx + php-fpm + reverb + queue)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
