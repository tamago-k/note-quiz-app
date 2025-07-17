FROM php:8.3-fpm

# Laravel に必要なパッケージ
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY ./src . 

RUN composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev 

# Laravel サーバを起動
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
