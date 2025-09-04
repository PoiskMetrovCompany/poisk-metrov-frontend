FROM mysql:8.0

WORKDIR /var/www/data/database

ARG TZ=UTC
ENV LANG de_DE.utf8
ENV TZ ${TZ}

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone
RUN mkdir -p /var/log/mysql && \
    chown -R mysql:mysql /var/log/mysql
COPY ./conf/my.cnf /etc/mysql/conf.d/my.cnf

COPY ./backup/poiskmetrov.sql.tar.gz /var/poiskmetrov.sql.tar.gz

RUN mkdir -p /var && \
    tar -xzf /var/poiskmetrov.sql.tar.gz -C /var
RUN rm -f /var/poiskmetrov.tar.gz

EXPOSE 3306