# Cleaned up for MySQL: Migrate -> Seed -> Cache -> Start Servers
web: php artisan migrate --force && php artisan db:seed --class=BusDetailSeeder --force && php artisan optimize:clear && (php artisan reverb:start --host=0.0.0.0 --port=8081 &) && php artisan serve --host=0.0.0.0 --port=$PORT
