# TrendAgent Scraper

Скраппер для получения данных из TrendAgent API с использованием очередей RabbitMQ для обработки больших объемов данных.

## Архитектура

### Основные компоненты:

1. **TrendAgentScrapperService** - основной сервис-оркестратор
2. **CachedDownloadManager** - менеджер загрузки с кэшированием
3. **RabbitMQQueueProcessor** - процессор очередей RabbitMQ
4. **TrendAgentUrlManager** - менеджер URL для отслеживания прогресса
5. **DataProcessor** - процессор данных для сохранения в БД
6. **Job классы** - для асинхронной обработки чанков

### Паттерны проектирования:

- **Queue-based Processing** - обработка через очереди
- **Batch Processing** - пакетная обработка данных
- **Repository Pattern** - для абстракции данных
- **Strategy Pattern** - для разных типов процессоров

## Установка и настройка

### 1. Переменные окружения

Добавьте в `.env` файл:

```env
# RabbitMQ
RABBITMQ_HOST=127.0.0.1
RABBITMQ_PORT=5672
RABBITMQ_USER=guest
RABBITMQ_PASSWORD=guest
RABBITMQ_VHOST=/

# TrendAgent Scraper
TREND_AGENT_CHUNK_SIZE=1000
TREND_AGENT_WORKERS=5
TREND_AGENT_CACHE_TTL=3600
TREND_AGENT_MAX_RETRIES=3
TREND_AGENT_TIMEOUT=300

# Города
TREND_AGENT_SPB_ENABLED=true
TREND_AGENT_MSK_ENABLED=true
TREND_AGENT_NSK_ENABLED=true
```

### 2. Скраппинг данных (загрузка в очереди)

```bash
# Загрузить все данные для СПб в очереди
docker-compose exec php-fpm php artisan trend-agent:scrape spb

# Загрузить все данные для всех городов
docker-compose exec php-fpm php artisan trend-agent:scrape --all

# Посмотреть статистику
docker-compose exec php-fpm php artisan trend-agent:scrape --stats
```

### 3. Обработка очередей (сохранение в БД)

**Рекомендуемая последовательность:**

#### **Шаг 1: Обработать локации (регионы)**
```bash
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.locations --limit=50
```

#### **Шаг 2: Обработать застройщиков**
```bash
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.builders --limit=100
```

#### **Шаг 3: Обработать жилые комплексы**
```bash
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.complexes --limit=100
```

#### **Шаг 4: Обработать здания**
```bash
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.buildings --limit=100
```

#### **Шаг 5: Обработать квартиры**
```bash
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.apartments --limit=1000
```

#### **Шаг 6: Массовое связывание квартир с комплексами**
```bash
docker-compose exec php-fpm php artisan trend-agent:link-apartments --limit=100000
```

### 4. Проверка результатов

```bash
# Посмотреть количество записей в БД
docker-compose exec php-fpm php artisan tinker --execute="
echo '=== СТАТИСТИКА ДАННЫХ ===' . PHP_EOL;
echo 'Квартиры: ' . DB::table('apartments')->count() . PHP_EOL;
echo 'Квартиры с complex_key: ' . DB::table('apartments')->whereNotNull('complex_key')->count() . PHP_EOL;
echo 'Комплексы: ' . App\Models\ResidentialComplex::count() . PHP_EOL;
echo 'Комплексы с застройщиками: ' . App\Models\ResidentialComplex::whereNotNull('builder')->where('builder', '!=', '')->count() . PHP_EOL;
echo 'Комплексы с названиями метро: ' . App\Models\ResidentialComplex::whereNotNull('metro_station')->where('metro_station', 'not like', '58c665%')->count() . PHP_EOL;
echo 'Застройщики: ' . App\Models\Builder::count() . PHP_EOL;
echo 'Локации: ' . App\Models\Location::count() . PHP_EOL;
echo 'Здания: ' . DB::table('buildings')->count() . PHP_EOL;
echo PHP_EOL . '=== ПРОЦЕНТ СВЯЗЫВАНИЯ ===' . PHP_EOL;
\$total = DB::table('apartments')->count();
\$linked = DB::table('apartments')->whereNotNull('complex_key')->count();
\$percentage = \$total > 0 ? round((\$linked / \$total) * 100, 2) : 0;
echo 'Связанные квартиры: ' . \$linked . ' / ' . \$total . ' (' . \$percentage . '%)' . PHP_EOL;
"

# Проверить пример связанных данных
docker-compose exec php-fpm php artisan tinker --execute="
echo '=== ПРИМЕР СВЯЗАННЫХ ДАННЫХ ===' . PHP_EOL;
\$apartment = DB::table('apartments')->whereNotNull('complex_key')->first();
if (\$apartment) {
    echo 'Квартира: ' . \$apartment->key . PHP_EOL;
    echo 'Комплекс: ' . \$apartment->complex_key . PHP_EOL;
    \$complex = App\Models\ResidentialComplex::where('key', \$apartment->complex_key)->first();
    if (\$complex) {
        echo 'Название комплекса: ' . \$complex->name . PHP_EOL;
        echo 'Метро: ' . \$complex->metro_station . PHP_EOL;
        echo 'Застройщик: ' . \$complex->builder . PHP_EOL;
        echo 'Адрес: ' . \$complex->address . PHP_EOL;
    }
}
"

### 5. Дополнительные команды

```bash
# Тестирование компонентов
docker-compose exec php-fpm php artisan trend-agent:test

# Очистка кэша
docker-compose exec php-fpm php artisan trend-agent:scrape --clear-cache

# Повторная обработка неудачных URL
docker-compose exec php-fpm php artisan trend-agent:scrape --retry-failed

# Массовое связывание квартир с комплексами (dry-run)
docker-compose exec php-fpm php artisan trend-agent:link-apartments --limit=1000 --dry-run

# Массовое связывание квартир с комплексами (реальное выполнение)
docker-compose exec php-fpm php artisan trend-agent:link-apartments --limit=100000

# Обработка конкретной очереди
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.buildings --limit=50
```

### 6. Полный цикл загрузки

1. `trend-agent:scrape spb` - загрузить данные в очереди
2. `process-queue --queue=trend_agent.locations` - обработать локации
3. `process-queue --queue=trend_agent.builders` - обработать застройщиков  
4. `process-queue --queue=trend_agent.complexes` - обработать ЖК
5. `process-queue --queue=trend_agent.buildings` - обработать здания
6. `process-queue --queue=trend_agent.apartments` - обработать квартиры
7. `trend-agent:link-apartments --limit=100000` - связать квартиры с комплексами

**Важно:** Соблюдайте последовательность, так как ЖК ссылаются на локации, квартиры ссылаются на ЖК, а здания ссылаются на комплексы!

## ✅ Решенные проблемы

### Проблема: "Данные всё ещё не связаны"

**Статус:** ✅ ПОЛНОСТЬЮ РЕШЕНА

**Что было исправлено:**

1. **Связывание квартир с комплексами**
   - Добавлены поля `complex_key` и `building_key` в `$fillable` модели `Apartment`
   - Создана команда `trend-agent:link-apartments` для массового связывания
   - Результат: **74.57% квартир** связаны с комплексами

2. **Названия метро вместо ID**
   - Исправлена обработка `block_subway_name` в данных квартир
   - Результат: **1,939 комплексов** имеют названия метро (например, "Проспект Просвещения")

3. **Загрузка данных о зданиях**
   - Добавлена обработка фида `buildings.json` в скраппере
   - Исправлена модель `Building` (убран `SoftDeletes`, отключены `timestamps`)
   - Результат: **10,426 зданий** загружено и связано с комплексами

4. **Заполнение данных комплексов**
   - Автоматическое обновление комплексов данными из квартир
   - Результат: **1,135 комплексов** имеют названия застройщиков

### Технические исправления:

- ✅ Добавлен метод `processComplexesBatch` в `DataProcessor`
- ✅ Исправлена обработка массивов в данных зданий
- ✅ Добавлена проверка скалярных значений для предотвращения ошибок
- ✅ Исправлены ограничения NOT NULL в таблице `buildings`

## Конфигурация

Основные настройки в `config/trend-agent.php`:

- **processing** - настройки обработки (размер чанка, количество воркеров)
- **cache** - настройки кэширования
- **queue** - настройки очередей RabbitMQ
- **cities** - список городов для скрейпинга
- **feeds** - настройки для разных типов фидов

## Мониторинг

### Метрики

Скраппер предоставляет следующие метрики:

- `trend_agent_scraper_processing_time_seconds` - время обработки
- `trend_agent_scraper_processed_records_total` - количество обработанных записей
- `trend_agent_scraper_errors_total` - количество ошибок
- `scraper_rabbitmq_messages_processed_total` - обработанные сообщения в RabbitMQ

### Логирование

Все операции логируются в канал `trend-agent` с дополнительной информацией:
- Session ID для отслеживания прогресса
- Chunk индексы для отладки
- Время выполнения операций

## API

### Основные методы TrendAgentScrapperService:

```php
// Скрейпинг города
$result = $scrapperService->scrapeCity('spb');

// Скрейпинг всех городов
$results = $scrapperService->scrapeAllCities();

// Получение статистики
$stats = $scrapperService->getScraperStats();

// Очистка кэша
$scrapperService->clearCache();

// Повтор неудачных URL
$scrapperService->retryFailedUrls();
```

## Обработка ошибок

### Circuit Breaker Pattern

- Автоматическое отключение при множественных ошибках
- Повторные попытки с экспоненциальной задержкой
- Dead Letter Queue для необрабатываемых сообщений

### Graceful Shutdown

- Правильное закрытие соединений RabbitMQ
- Сохранение состояния обработки
- Возможность продолжения с места остановки

## Производительность

### Оптимизации:

1. **Chunked Processing** - данные делятся на чанки по 1000 записей
2. **Batch Operations** - массовые операции вставки в БД
3. **Caching** - кэширование HTTP ответов на 1 час
4. **Connection Pooling** - переиспользование соединений БД
5. **Async Processing** - асинхронная обработка через очереди

### Рекомендуемые настройки для production:

```env
TREND_AGENT_CHUNK_SIZE=500
TREND_AGENT_WORKERS=10
TREND_AGENT_CACHE_TTL=7200
TREND_AGENT_MAX_RETRIES=5
```

## Безопасность

- Валидация всех входных данных
- Защита от SQL инъекций через Eloquent ORM
- Ограничение количества одновременных соединений
- Логирование всех операций для аудита

## Тестирование

### Unit тесты:

```bash
# Запуск тестов скраппера
php artisan test --filter=TrendAgentScrapper
```

### Integration тесты:

```bash
# Тестирование с реальным RabbitMQ
php artisan test --filter=TrendAgentIntegration
```

## Troubleshooting

### Распространенные проблемы:

1. **RabbitMQ соединение не устанавливается**
   - Проверьте настройки в `.env`
   - Убедитесь, что RabbitMQ запущен

2. **Медленная обработка**
   - Увеличьте количество воркеров
   - Уменьшите размер чанка

3. **Out of memory**
   - Уменьшите `TREND_AGENT_CHUNK_SIZE`
   - Проверьте лимиты PHP памяти

4. **Дублирование данных**
   - Проверьте логику `updateOrCreate`
   - Очистите кэш URL

### ✅ Решенные проблемы:

5. **"Данные всё ещё не связаны"** - ✅ РЕШЕНО
   - Используйте команду `trend-agent:link-apartments`
   - Проверьте, что поля `complex_key` и `building_key` в `$fillable` модели `Apartment`

6. **"Метро отображается как ID"** - ✅ РЕШЕНО
   - Данные автоматически обновляются из `block_subway_name` в квартирах
   - Проверьте команду связывания квартир

7. **"Здания не загружаются"** - ✅ РЕШЕНО
   - Убедитесь, что модель `Building` не использует `SoftDeletes`
   - Проверьте, что `$timestamps = false` в модели `Building`

8. **"Array to string conversion"** - ✅ РЕШЕНО
   - Добавлена проверка `is_scalar()` для всех полей
   - Исправлена обработка массивов в `formatBuildingData`

## 📋 Примеры использования

### Быстрый старт для СПб:

```bash
# 1. Загрузить данные в очереди
docker-compose exec php-fpm php artisan trend-agent:scrape spb

# 2. Обработать все очереди по порядку
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.locations --limit=50
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.builders --limit=100
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.complexes --limit=100
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.buildings --limit=100
docker-compose exec php-fpm php artisan trend-agent:process-queue --queue=trend_agent.apartments --limit=1000

# 3. Связать квартиры с комплексами
docker-compose exec php-fpm php artisan trend-agent:link-apartments --limit=100000

# 4. Проверить результаты
docker-compose exec php-fpm php artisan trend-agent:scrape spb --stats
```

### Проверка качества данных:

```bash
# Проверить процент связывания
docker-compose exec php-fpm php artisan tinker --execute="
\$total = DB::table('apartments')->count();
\$linked = DB::table('apartments')->whereNotNull('complex_key')->count();
echo 'Связанные квартиры: ' . \$linked . ' / ' . \$total . ' (' . round((\$linked/\$total)*100, 2) . '%)' . PHP_EOL;
"

# Проверить пример связанных данных
docker-compose exec php-fpm php artisan tinker --execute="
\$apartment = DB::table('apartments')->whereNotNull('complex_key')->first();
if (\$apartment) {
    \$complex = App\Models\ResidentialComplex::where('key', \$apartment->complex_key)->first();
    if (\$complex) {
        echo 'Квартира: ' . \$apartment->key . PHP_EOL;
        echo 'Комплекс: ' . \$complex->name . PHP_EOL;
        echo 'Метро: ' . \$complex->metro_station . PHP_EOL;
        echo 'Застройщик: ' . \$complex->builder . PHP_EOL;
    }
}
"
```

## Расширение

### Добавление нового типа фида:

1. Добавить конфигурацию в `config/trend-agent.php`
2. Создать Job класс для обработки
3. Добавить маршрут в RabbitMQ
4. Обновить DataProcessor

### Добавление нового города:

1. Добавить URL в `TrendAgentFeedConst`
2. Добавить конфигурацию в `config/trend-agent.php`
3. Обновить `TrendAgentUrlManager`
