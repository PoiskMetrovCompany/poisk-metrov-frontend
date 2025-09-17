# **<img src="https://avatars.githubusercontent.com/u/180920011?s=200&v=4" alt="Логотип" width="26" /> ПОИСК МЕТРОВ**


## Используемые технологии

- **Infrastructure**: ***<span style="color: #6074d5;">Linux, Nginx, MySQL 8</span>***
- **Backend**: ***<span style="color: #6074d5;">PHP 8.1 (Laravel 10)</span>***
- **CI\CD**: ***<span style="color: #6074d5;">Jenkins</span>***

## Настройка окружения.
### Создать/настроить конфиг, как минимум надо настроить подключение к БД:
```bash
  (sudo) cp .example.env .env
```

### Поднять docker:
```bash
  (sudo) docker-compose up --build -d
```

### Создать/настроить конфиг, для работы Laravel:
```bash
  (sudo) cp ./sources/.env.example ./sources/.env
```

### Установить зависимости:
```bash
  (sudo) docker-compose run php-fpm composer install
```

### Создать миграции: (Нужно проверить есть ли база)
```bash
  (sudo) docker-compose run php-fpm php artisan migrate
```

### Сгенерировать CSRF токен:
```bash
  (sudo) docker-compose run php-fpm php artisan key:generate
```

### Установить права и группу на директорию с проектом:
```bash
  sudo chmod -R 777 $PWD
  sudo chown -R my-group:my-group $PWD
```

### Создать ссылку на storage:
```bash
  (sudo) docker-compose run php-fpm php artisan storage:link
```

### Зайти в контейнер и применить дамп базы

### Документация API
***Доступна по адресу: http://localhost:1080/***

### Схема Базы данных
***Доступна по адресу: https://dbdiagram.io/d/67c92a64263d6cf9a0650409***

### Мониторинг
```bash
# Запустить все сервисы
docker-compose up -d

# Доступ к приложению:
# Nginx + PHP: http://localhost:1080
# MySQL: localhost:3306
# MongoDB: localhost:27017
# Memcached: localhost:11211

# Мониторинг:
# Prometheus: http://localhost:9090
# Grafana: http://localhost:3000 (admin/admin)
# Node Exporter: http://localhost:9100
# Nginx Exporter: http://localhost:9113
```

#### ✅ Активные метрики:
- **Системные**: CPU, память, диск, сеть (Node Exporter)
- **Nginx**: запросы, соединения, статусы ответов
- **Prometheus**: само-мониторинг

#### 🚧 В разработке:
- **MySQL экспортер**: требует настройки подключения
- **Memcached экспортер**: требует настройки подключения


# Poisk Metrov - Мониторинг

## 🚀 Быстрый старт

```bash
# Запуск всех сервисов
docker-compose up -d

# Проверка статуса
docker-compose ps
```

## 📊 Доступ к сервисам

### Основные сервисы
- **Nginx + PHP**: http://localhost:1080
- **MySQL**: localhost:13306
- **MongoDB**: localhost:37017
- **RabbitMQ**: localhost:15673

### Мониторинг
- **Prometheus**: http://localhost:9090
- **Grafana**: http://localhost:3000 (admin/admin)
- **MySQL Exporter**: http://localhost:9104/metrics
- **Nginx Exporter**: http://localhost:9113/metrics

## 📈 Метрики

### Собираемые метрики:
- **Nginx**: соединения, запросы, статус
- **MySQL**: подключения, запросы, производительность
- **PHP-FPM**: процессы, очередь
- **RabbitMQ**: соединения, очереди, сообщения

### Dashboard в Grafana:
1. Перейдите в http://localhost:3000
2. Логин: admin, Пароль: admin
3. Dashboard: "System Overview - Poisk Metrov (Exporters)"

## 🛠 Технические детали

- **Экспортеры**: Используются готовые Prometheus экспортеры
- **Сбор метрик**: Каждые 30 секунд
- **Хранение**: Настраивается в Prometheus
- **Визуализация**: Grafana с автоматической настройкой

## 🏙️ Работа с сокращениями городов

### Проблема
При парсинге фидов в таблицу `locations` записывались сокращения городов (НСК, СПБ, МСК) вместо полных названий на латинице.

### Решение
Система автоматически преобразует сокращения в полные названия при парсинге фидов.

### Команды

#### Тестирование преобразования сокращений
```bash
docker-compose exec php-fpm php artisan app:test-abbreviation-expansion
```
Проверяет корректность преобразования всех поддерживаемых сокращений:
- НСК → novosibirsk
- СПБ → st-petersburg  
- МСК → moscow
- КРД → krasnodar
- РСТ → rostov
- КЗН → kazan
- ЕКБ → ekaterinburg
- И другие...

#### Исправление существующих данных
```bash
docker-compose exec php-fpm php artisan app:fix-location-abbreviations
```
Исправляет уже существующие записи в таблице `locations` с сокращениями на полные названия.

### Поддерживаемые сокращения
| Сокращение | Регион | Столица | Код |
|------------|--------|---------|-----|
| НСК | Новосибирская область | Новосибирск | novosibirsk |
| СПБ | Санкт-Петербург | Санкт-Петербург | st-petersburg |
| МСК | Москва | Москва | moscow |
| КРД | Краснодарский край | Краснодар | krasnodar |
| РСТ | Ростовская область | Ростов-на-Дону | rostov |
| КЗН | Республика Татарстан | Казань | kazan |
| ЕКБ | Свердловская область | Екатеринбург | ekaterinburg |
| ЧЛБ | Челябинская область | Челябинск | chelyabinsk |
| КЛГ | Калининградская область | Калининград | kaliningrad |
| ВРН | Воронежская область | Воронеж | voronezh |
| КРМ | Республика Крым | Симферополь | crimea |
| СЧ | Сочи | Сочи | black-sea |
| УФА | Башкортостан Республика | Уфа | ufa |
| ДВ | Приморский край | Владивосток | far-east |
| ТАЙ | Таиланд | Пхукет | thailand |