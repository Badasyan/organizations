FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_mysql

COPY . /var/www

RUN chmod -R 777 /var/www/storage /var/www/bootstrap/cache

CMD php artisan serve --host=0.0.0.0 --port=8000
