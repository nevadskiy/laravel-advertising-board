FROM php:7.2-fpm

RUN apt-get update

# CURL lib, Used by elasticsearch
RUN apt-get install libcurl3-dev curl -y
RUN docker-php-ext-install curl

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www
