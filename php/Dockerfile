FROM php:7.2-fpm

# FROM --platform=linux/amd64 mysql:5.7

# RUN cd /usr/bin && curl -s http://getcomposer.org/installer \n\
RUN apt-get update \
&& apt-get install -y \
git \
libpq-dev \
unzip \
zip \
&& docker-php-ext-install pdo_mysql pdo_pgsql mysqli 

ENV LANG ja_JP.UTF-8
ENV LANGUAGE ja_JP:ja
ENV LC_ALL=ja_JP.UTF-8

WORKDIR /var/www/html

EXPOSE 3303