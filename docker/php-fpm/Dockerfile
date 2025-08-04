FROM php:8.1-fpm

ENV PUID ${PUID}
ENV PGID ${PGID}
ARG LOCALE=POSIX
ENV LC_ALL ${LOCALE}

RUN apt-get update && apt-get install -y \
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
    && docker-php-ext-install pdo pdo_mysql mysqli


RUN pecl install xdebug
RUN docker-php-ext-enable xdebug
RUN apt install -y \
        libzip-dev \
        zip \
        && docker-php-ext-install zip

RUN pecl update-channels

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN apt-get clean && rm -rf /var/lib/apt/lists/*
COPY --from=mlocati/php-extension-installer /usr/bin/install-php-extensions /usr/bin/

RUN install-php-extensions xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env
RUN echo "xdebug.start_with_request = yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env
RUN echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env
RUN echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env
RUN echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env
RUN echo "xdebug.idekey = PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.env

WORKDIR /var/www/sources

RUN chown -R www-data:www-data /var/www/sources

EXPOSE 9000