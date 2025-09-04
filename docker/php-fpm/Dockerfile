FROM php:8.1-fpm-bullseye

ENV DEBIAN_FRONTEND=noninteractive

ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX
ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

RUN apt update \
    apt install -y \
    zlib1g-dev \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libmemcached-dev \
    curl \
    vim \
    git \
    zip \
    unzip \
    graphviz \
    libssl-dev \
    libcurl4-openssl-dev \
    libfreetype6-dev \
    libjpeg62-turbo-dev \
    libwebp-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install gd \
    && docker-php-ext-install exif \
    && docker-php-ext-install sockets

RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

RUN apt-get install -y --no-install-recommends libzip-dev && \
    docker-php-ext-install zip

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN apt-get clean && \
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