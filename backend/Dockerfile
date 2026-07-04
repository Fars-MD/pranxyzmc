FROM dunglas/frankenphp:php8.2-bookworm

RUN install-php-extensions pdo_pgsql pgsql intl opcache

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app
COPY . .

RUN composer install --no-dev --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    chmod -R 777 storage bootstrap/cache

ENV FRANKENPHP_CONFIG="worker {\
    user www-data group www-data\
    count 1\
    supervisor 10\
    /app/public/index.php\
}"
