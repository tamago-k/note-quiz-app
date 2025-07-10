# note-quiz-app/backend/docker/php/Dockerfile

FROM php:8.3-fpm

# 基本パッケージとLaravel用拡張をインストール
RUN apt-get update && apt-get install -y \
    git zip unzip curl libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Composerインストール
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www