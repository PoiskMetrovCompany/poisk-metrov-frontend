FROM php:8.1-fpm-bullseye

ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX

ENV DEBIAN_FRONTEND=noninteractive

ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

RUN sed -i 's/deb.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org/mirror.yandex.ru/g' /etc/apt/sources.list && \
    apt-get update --allow-releaseinfo-change && \
    apt-get install -y --no-install-recommends \
        bash \
        zlib1g-dev \
        libpq-dev \
        libpng-dev \
        libxml2-dev \
        libssl-dev \
        libcurl4-openssl-dev \
        libzip-dev \
        libwebp-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libmemcached-dev && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg \
    --with-webp \
    && docker-php-ext-install gd

RUN pecl install memcached \
    && docker-php-ext-enable memcached

RUN docker-php-ext-install \
        zip \
        pcntl \
        bcmath \
        opcache \
        pdo \
        pdo_mysql \
        mysqli \
        exif \
        sockets

RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN apt clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

WORKDIR /var/www/sources

EXPOSE 9000