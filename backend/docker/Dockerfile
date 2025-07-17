FROM php:8.3-fpm

# 必要パッケージと Laravel 用拡張をインストール
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composer を追加
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# .envでPORTを受け取ってLaravel起動
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}