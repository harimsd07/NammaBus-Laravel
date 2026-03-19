FROM php:8.4-cli

# Install system deps + ext-pcntl (required for Reverb)
RUN apt-get update && apt-get install -y     git curl zip unzip libpq-dev libzip-dev     && docker-php-ext-install pdo pdo_mysql zip pcntl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:cache &&     php artisan route:cache &&     php artisan event:cache

EXPOSE 8080 8084
