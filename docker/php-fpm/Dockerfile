FROM php:8.1-fpm-bullseye  # Используем стабильную версию Debian

ENV DEBIAN_FRONTEND=noninteractive

# Установка аргументов
ARG PUID=1000
ARG PGID=1000
ARG LOCALE=POSIX
ENV PUID=${PUID}
ENV PGID=${PGID}
ENV LC_ALL=${LOCALE}

# Увеличиваем таймаут для apt и используем стабильные репозитории
RUN echo "Acquire::http::Timeout \"30\";" > /etc/apt/apt.conf.d/99timeout && \
    echo "Acquire::ftp::Timeout \"30\";" >> /etc/apt/apt.conf.d/99timeout && \
    echo "Acquire::Retries \"3\";" >> /etc/apt/apt.conf.d/99timeout && \
    echo "deb http://deb.debian.org/debian bullseye main" > /etc/apt/sources.list && \
    echo "deb http://deb.debian.org/debian bullseye-updates main" >> /etc/apt/sources.list && \
    echo "deb http://security.debian.org/debian-security bullseye-security main" >> /etc/apt/sources.list

# Обновляем пакеты с повторными попытками
RUN apt-get update --fix-missing || (sleep 30 && apt-get update --fix-missing) || (sleep 60 && apt-get update --fix-missing) && \
    apt-get install -y --no-install-recommends \
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

# Устанавливаем расширения через pecl
RUN pecl install mongodb && \
    docker-php-ext-enable mongodb

# Устанавливаем xdebug
RUN pecl install xdebug && \
    docker-php-ext-enable xdebug

# Устанавливаем расширение zip
RUN apt-get install -y --no-install-recommends libzip-dev && \
    docker-php-ext-install zip

# Устанавливаем Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Очищаем систему
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Настройка xdebug
RUN echo "xdebug.mode=debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_host=docker.for.mac.localhost" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.client_port=9001" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.log=/var/log/xdebug.log" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini && \
    echo "xdebug.idekey=PHPSTORM" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini

# Устанавливаем рабочую директорию
WORKDIR /var/www/sources

# Меняем владельца рабочей директории
RUN chown -R www-data:www-data /var/www/sources

# Открываем порт
EXPOSE 9000