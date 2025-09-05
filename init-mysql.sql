-- Создание пользователя и базы данных
CREATE DATABASE IF NOT EXISTS poiskmetrov;
CREATE USER IF NOT EXISTS 'raptor'@'%' IDENTIFIED BY 'lama22';
GRANT ALL PRIVILEGES ON poiskmetrov.* TO 'raptor'@'%';
FLUSH PRIVILEGES;
