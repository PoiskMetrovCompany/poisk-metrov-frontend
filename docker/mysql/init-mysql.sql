-- Создание пользователя и базы данных
CREATE DATABASE IF NOT EXISTS poiskmetrov;
CREATE USER IF NOT EXISTS 'poisk_metrov_root'@'%' IDENTIFIED BY 'T0nw$IkSOELnMbINBBWIWx9dO*n5D|';
GRANT ALL PRIVILEGES ON poiskmetrov.* TO 'poisk_metrov_root'@'%';
FLUSH PRIVILEGES;
