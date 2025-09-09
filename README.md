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