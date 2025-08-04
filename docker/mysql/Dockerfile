FROM mysql:8.0.3

ARG TZ=UTC
ENV LANG de_DE.utf8
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone

RUN mkdir -p /var/log/mysql && \
    chown -R mysql:mysql /var/log/mysql

COPY ./conf/my.cnf /etc/mysql/conf.d/my.cnf

COPY ../../app/backup/poiskmetrov_backup.sql.tar.gz /tmp/poiskmetrov_dump.tar.gz

RUN cd /tmp && \
    tar -xzf poiskmetrov_dump.tar.gz -C /tmp && \
    mv /tmp/poiskmetrov_backup.sql /docker-entrypoint-initdb.d/001-poiskmetrov.sql && \
    rm -f poiskmetrov_dump.tar.gz

WORKDIR /var/www/data/database

EXPOSE 3306