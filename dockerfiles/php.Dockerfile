FROM php:8.2-fpm-alpine

WORKDIR /var/www/laravel

# Устанавливаем зависимости для PostgreSQL и Redis
RUN apk add --no-cache postgresql-dev $PHPIZE_DEPS \
    && docker-php-ext-install pdo pdo_pgsql \
    && pecl install redis \
    && docker-php-ext-enable redis
