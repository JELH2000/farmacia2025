FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    $PHPIZE_DEPS \
    libzip-dev \
    zip \
    && docker-php-ext-install mysqli pdo pdo_mysql opcache \
    && docker-php-ext-enable opcache

WORKDIR /var/www/html

COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]