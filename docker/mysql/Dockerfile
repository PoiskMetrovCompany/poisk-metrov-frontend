FROM mysql:8.0

ARG TZ=UTC
ENV LANG de_DE.utf8
ENV TZ ${TZ}
RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime && \
    echo ${TZ} > /etc/timezone

RUN mkdir -p /var/log/mysql /var/run/mysqld && \
    chown -R mysql:mysql /var/log/mysql /var/run/mysqld

COPY ./conf/my.cnf /etc/mysql/conf.d/my.cnf

COPY ./backup/poiskmetrov.sql.tar.gz /docker-entrypoint-initdb.d/poiskmetrov.sql.tar.gz

RUN echo '#!/bin/bash\n\
set -e\n\
[ -d "/var/lib/mysql/mysql" ] && echo "Database already initialized" && exit 0\n\
tar -xzf /docker-entrypoint-initdb.d/poiskmetrov.sql.tar.gz -C /docker-entrypoint-initdb.d/\n\
mv /docker-entrypoint-initdb.d/poiskmetrov.sql /docker-entrypoint-initdb.d/init.sql\n\
rm /docker-entrypoint-initdb.d/poiskmetrov.sql.tar.gz' > /docker-entrypoint-initdb.d/01-import.sh && \
chmod +x /docker-entrypoint-initdb.d/01-import.sh

RUN echo '#!/bin/bash\n\
set -e\n\
# Удаляем старый сокет если он существует\n\
rm -f /var/lib/mysql/mysql.sock\n\
# Создаем символическую ссылку для сокета\n\
ln -sf /var/run/mysqld/mysqld.sock /var/lib/mysql/mysql.sock\n\
# Запускаем оригинальный entrypoint\n\
exec /entrypoint.sh "$@"' > /custom-entrypoint.sh && \
chmod +x /custom-entrypoint.sh

EXPOSE 3306