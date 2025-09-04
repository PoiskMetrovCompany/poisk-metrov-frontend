FROM php:8.1-fpm-bullseye

ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX

ENV DEBIAN_FRONTEND=noninteractive

ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

RUN apt update && \
    apt install -y software-properties-common && \
    add-apt-repository ppa:ondrej/php -y && \
    apt update && \
    apt install -y libpq-dev

RUN apt update && \
    apt install -y \
        zlib1g-dev \
        libpq-dev \
        libpng-dev \
        libxml2-dev \
        libssl-dev \
        libcurl4-openssl-dev

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install gd

RUN pecl install memcached \
    && docker-php-ext-enable memcached

RUN docker-php-ext-install zip
RUN docker-php-ext-install pcntl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install mysqli
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

WORKDIR /var/www/sources

EXPOSE 9000