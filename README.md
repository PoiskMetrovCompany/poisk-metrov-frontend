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
