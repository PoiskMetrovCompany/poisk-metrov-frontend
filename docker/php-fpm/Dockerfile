FROM php:8.1-fpm

ENV PUID=${PUID}
ENV PGID=${PGID}
ARG LOCALE=POSIX
ENV LC_ALL=${LOCALE}

RUN apt update && \
    apt install -y --no-install-recommends \
    zlib1g-dev
#    libpq-dev \
#    libpng-dev \
#    libonig-dev \
#    libxml2-dev \
#    libmemcached-dev \
#    curl \
#    vim \
#    git \
#    zip \
#    unzip \
#    graphviz \
#    libssl-dev \
#    libcurl4-openssl-dev \
#    libfreetype6-dev \
#    libjpeg62-turbo-dev \
#    libwebp-dev \
#    && docker-php-ext-install pdo pdo_mysql mysqli \
#    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
#    && docker-php-ext-install gd \
#    && docker-php-ext-install exif

#RUN docker-php-ext-install sockets
#
#RUN pecl install mongodb
#RUN docker-php-ext-enable mongodb
#
## Установка xdebug через pecl
#RUN pecl install xdebug \
#    && docker-php-ext-enable xdebug
#
## Установка zip
#RUN apt install -y libzip-dev \
#    && docker-php-ext-install zip
#
## Установка Composer
#RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
#
## Очистка
#RUN apt clean && rm -rf /var/lib/apt/lists/*
#
## Установка php-extension-installer (если нужен, но у тебя уже COPY)
#COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/
#
## Настройка xdebug (можно оставить как есть)
#RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini
#RUN echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

WORKDIR /var/www/sources

RUN #chown -R www-data:www-data /var/www/sources

EXPOSE 9000