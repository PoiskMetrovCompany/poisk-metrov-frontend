FROM mysql:8.0

ARG TZ=UTC
ENV LANG de_DE.utf8
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone

RUN mkdir -p /var/log/mysql /var/run/mysqld && \
    chown -R mysql:mysql /var/log/mysql /var/run/mysqld

COPY ./conf/my.cnf /etc/mysql/conf.d/my.cnf

COPY ./backup/poiskmetrov.sql.tar.gz /var/poiskmetrov.sql.tar.gz

EXPOSE 3306