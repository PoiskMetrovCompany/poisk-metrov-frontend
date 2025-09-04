FROM php:8.1-fpm-bullseye

ENV DEBIAN_FRONTEND=noninteractive

ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX
ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

RUN apt update

RUN #apt install -y zlib1g-dev
RUN apt install -y libpq-dev
RUN apt install -y libpng-dev
RUN apt install -y libonig-dev
RUN apt install -y libxml2-dev
RUN apt install -y libmemcached-dev
RUN apt install -y curl
RUN apt install -y vim
RUN apt install -y git
RUN apt install -y zip
RUN apt install -y unzip
RUN apt install -y graphviz
RUN apt install -y libssl-dev
RUN apt install -y libcurl4-openssl-dev
RUN apt install -y libfreetype6-dev
RUN apt install -y libjpeg62-turbo-dev
RUN apt install -y libwebp-dev

RUN docker-php-ext-install pdo pdo_mysql mysqli

RUN docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp
RUN docker-php-ext-install gd

RUN docker-php-ext-install exif

RUN docker-php-ext-install sockets

RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN apt install -y libzip-dev && \
    docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/sources

RUN chown -R www-data:www-data /var/www/sources

# Открываем порт
EXPOSE 9000