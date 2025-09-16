# Запуск скрапера TrendAgent

## Введение

Этот документ описывает, как запускать и управлять скрапером данных TrendAgent. Скрапер предназначен для сбора информации о жилых комплексах, квартирах, застройщиках, метро и других данных из фидов TrendAgent.

## 🚀 Предварительные требования

Перед запуском скрапера убедитесь, что все необходимые Docker-сервисы запущены. В частности, скрапер активно использует **RabbitMQ** для обработки задач в очереди.

1.  **Запустите все Docker-сервисы:**
    Убедитесь, что ваш `docker-compose.yml` файл находится в корне проекта и содержит все необходимые сервисы, включая `php-fpm` и `rabbitmq`.
    ```bash
    docker-compose up -d
    ```

2.  **Проверьте статус сервисов:**
    ```bash
    docker-compose ps
    ```
    Убедитесь, что `poisk-metrov_rabbitmq` запущен и его порты доступны.

## 🏃‍♂️ Запуск скрапера

Основная команда для запуска скрапера: `docker-compose exec php-fpm php artisan trend-agent:scrape`

### Запуск для одного города

Вы можете указать конкретный город для скрапинга. Поддерживаемые города: `spb`, `msk`, `krd`, `nsk`, `rst`, `kzn`, `ekb`.

```bash
docker-compose exec php-fpm php artisan trend-agent:scrape <city_code> --verbose
```
**Пример:** Запуск для Санкт-Петербурга
```bash
docker-compose exec php-fpm php artisan trend-agent:scrape spb --verbose
```

### Запуск для всех городов

Используйте опцию `--all` для запуска скрапера по всем доступным городам.

```bash
docker-compose exec php-fpm php artisan trend-agent:scrape --all --verbose
```
Эта команда пройдет по каждому городу, определенному в конфигурации, и запустит процесс скрапинга.

## 📥 Обработка отсканированных данных

**⚠️ ВАЖНО:** Скрапер теперь работает в **гибридном режиме**:
- **Синхронно** обрабатывает: локации, застройщики, комплексы, здания (сразу сохраняет в БД)
- **Асинхронно** обрабатывает: квартиры (через RabbitMQ очереди)

### 🔄 Последовательность выполнения команд

#### 1. Запуск скрапера (сканирование данных)
```bash
# Для одного города
docker-compose exec php-fpm php artisan trend-agent:scrape <city_code> --verbose

# Для всех городов
docker-compose exec php-fpm php artisan trend-agent:scrape --all --verbose
```

**Что происходит:** Скрапер загружает фиды и **сразу сохраняет** в БД:
- ✅ Локации (regions.json)
- ✅ Застройщики (builders.json) 
- ✅ Комплексы (blocks.json)
- ✅ Здания (buildings.json)
- ⏳ Квартиры (apartments.json) → помещаются в очередь RabbitMQ

#### 2. Обработка квартир (единственная очередь)
После завершения скрапинга запустите обработчик квартир:

```bash
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.apartments --tries=3 --verbose
```

**Что происходит:** Обработчик забирает квартиры из очереди и сохраняет их в БД.

#### 3. Проверка результатов
```bash
# Проверить количество записей в таблицах
docker-compose exec php-fpm php artisan tinker --execute="
echo 'Локации: ' . App\Models\Location::count() . PHP_EOL;
echo 'Застройщики: ' . App\Models\Builder::count() . PHP_EOL;
echo 'Комплексы: ' . App\Models\ResidentialComplex::count() . PHP_EOL;
echo 'Здания: ' . App\Models\Building::count() . PHP_EOL;
echo 'Квартиры: ' . App\Models\Apartment::count() . PHP_EOL;
"
```

### 🚫 Устаревшие команды (НЕ ИСПОЛЬЗУЙТЕ)

Следующие команды больше **НЕ РАБОТАЮТ**, так как эти данные теперь обрабатываются синхронно:

```bash
# ❌ НЕ РАБОТАЕТ - комплексы обрабатываются синхронно
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.complexes

# ❌ НЕ РАБОТАЕТ - застройщики обрабатываются синхронно  
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.builders

# ❌ НЕ РАБОТАЕТ - локации обрабатываются синхронно
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.locations

# ❌ НЕ РАБОТАЕТ - здания обрабатываются синхронно
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.buildings
```

### 📊 Мониторинг очередей

Для проверки состояния очередей используйте:

```bash
# Статистика скрапера (включая состояние очередей)
docker-compose exec php-fpm php artisan trend-agent:scrape --stats
```

**Важно:** Перед запуском обработчиков убедитесь, что ваш RabbitMQ-сервис запущен и работает корректно:

```bash
docker-compose restart rabbitmq
```

## ⚙️ Дополнительные опции

К основной команде `trend-agent:scrape` можно добавить следующие опции:

*   **`--stats`**: Показывает статистику скрапера, включая состояние очередей RabbitMQ, кэша и URL-менеджера.
    ```bash
    docker-compose exec php-fpm php artisan trend-agent:scrape --stats
    ```

*   **`--clear-cache`**: Очищает все кэши, используемые скрапером (кэш загруженных URL, кэш маппингов и т.д.).
    ```bash
    docker-compose exec php-fpm php artisan trend-agent:scrape --clear-cache
    ```
    Вам будет предложено подтвердить действие.

*   **`--retry-failed`**: Перемещает все неудачные URL обратно в очередь для повторной попытки.
    ```bash
    docker-compose exec php-fpm php artisan trend-agent:scrape --retry-failed
    ```
    Вам будет предложено подтвердить действие.

*   **`--failed-urls`**: Показывает список URL, при обработке которых произошли ошибки.
    ```bash
    docker-compose exec php-fpm php artisan trend-agent:scrape --failed-urls
    ```

## ⚠️ Устранение неполадок

### Ошибки RabbitMQ (`NOT_FOUND - no exchange 'trend_agent_exchange'`)

Если вы видите ошибки, связанные с отсутствием `exchange` или `queue` в RabbitMQ, это может означать, что RabbitMQ не был правильно настроен или перезапущен после изменения конфигурации.

**Возможные решения:**
1.  **Перезапустите RabbitMQ-сервис:**
    ```bash
    docker-compose restart rabbitmq
    ```
    Иногда это помогает, если RabbitMQ был запущен до того, как скрапер пытался создать необходимые обмены и очереди.

2.  **Проверьте логи RabbitMQ:**
    ```bash
    docker-compose logs rabbitmq
    ```
    Это может дать дополнительную информацию о проблемах с запуском или конфигурацией RabbitMQ.

3.  **Проверьте конфигурацию TrendAgentScrapperServiceProvider**:
    Убедитесь, что сервис-провайдер корректно регистрирует `RabbitMQQueueProcessor` и что настройки очередей в `config/trend-agent.php` (или других файлах конфигурации очередей) верны.

---

Надеемся, эта документация поможет вам эффективно работать со скрапером TrendAgent!
