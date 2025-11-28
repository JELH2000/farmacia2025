FROM ghcr.io/railwayapp/nginx-php:8.2

RUN install-php-extensions mysqli pdo_mysql opcache

COPY default.conf /etc/nginx/sites-enabled/default.conf

COPY . /app

WORKDIR /app
