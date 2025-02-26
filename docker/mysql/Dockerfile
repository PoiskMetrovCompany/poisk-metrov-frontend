FROM mysql:8.0
LABEL authors="vuacheslav.mir@gmail.com"

ARG TZ=UTC
ENV LANG de_DE.utf8
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone

WORKDIR /var/www/data/database

EXPOSE 3306
