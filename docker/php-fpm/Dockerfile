FROM php:8.1-fpm-bullseye

ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX

ENV DEBIAN_FRONTEND=noninteractive

ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

RUN apt update

RUN apt install -y \
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
    libwebp-dev

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

WORKDIR /var/www/sources

EXPOSE 9000