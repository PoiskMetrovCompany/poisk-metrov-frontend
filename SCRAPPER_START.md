# Руководство по запуску TrendAgent Scraper

## Обзор системы

TrendAgent Scraper - это система для сбора данных о жилых комплексах, квартирах и связанной информации из различных городов России. Система работает в гибридном режиме: базовые данные обрабатываются синхронно, а квартиры - асинхронно через RabbitMQ.

## Поддерживаемые города

- `nsk` - Новосибирск
- `spb` - Санкт-Петербург  
- `msk` - Москва
- `krasnodar` - Краснодар
- `rostov` - Ростов-на-Дону
- `kzn` - Казань
- `ekb` - Екатеринбург

## Последовательность запуска

### 1. Добавление данных для каждого города

Запускайте скрапер для каждого города отдельно:

```bash
# Новосибирск
docker-compose exec php-fpm php artisan trend-agent:scrape nsk

# Санкт-Петербург
docker-compose exec php-fpm php artisan trend-agent:scrape spb

# Москва
docker-compose exec php-fpm php artisan trend-agent:scrape msk

# Краснодар
docker-compose exec php-fpm php artisan trend-agent:scrape krasnodar

# Ростов-на-Дону
docker-compose exec php-fpm php artisan trend-agent:scrape rostov

# Казань
docker-compose exec php-fpm php artisan trend-agent:scrape kzn

# Екатеринбург
docker-compose exec php-fpm php artisan trend-agent:scrape ekb
```

**Что происходит:**
- Собираются данные: `regions`, `builders`, `blocks` (жилые комплексы), `buildings` - синхронно
- Квартиры (`apartments`) добавляются в очередь RabbitMQ для асинхронной обработки

### 2. Обработка очереди квартир

После запуска скрапера для всех городов, обработайте очередь квартир:

```bash
# Обработка квартир (запускать в отдельном терминале)
docker-compose exec php-fpm php artisan queue:work rabbitmq --queue=trend_agent.apartments --tries=3 --verbose
```

**Примечание:** Оставьте этот процесс работать до полной обработки всех квартир.

### 3. Обновление кэша приложения

После загрузки всех данных обновите кэш:

```bash
docker-compose exec php-fpm php artisan app:update-cache-application-command
```

**Что обновляется:**
- Кэш жилых комплексов для всех городов
- Кэш квартир
- Проверка корректности кэширования

### 4. Обновление лучших предложений

Создайте лучшие предложения для всех городов:

```bash
# Для всех городов (12 предложений на город, квартиры от 7М рублей)
docker-compose exec php-fpm php artisan app:add-best-offers --limit=12 --min-price=7000000

# Для конкретного города
docker-compose exec php-fpm php artisan app:add-best-offers --city=st-petersburg --limit=12 --min-price=7000000

# Очистить существующие и создать новые
docker-compose exec php-fpm php artisan app:add-best-offers --clear --limit=12 --min-price=7000000
```

**Критерии отбора:**
- Квартиры от 7,000,000 рублей
- Максимум 12 комплексов на город
- Сортировка по количеству квартир (по убыванию)

## Проверка результатов

### Проверка количества данных

```bash
# Проверка через tinker
docker-compose exec php-fpm php artisan tinker --execute="
echo 'Locations: ' . App\Models\Location::count() . PHP_EOL;
echo 'Builders: ' . App\Models\Builder::count() . PHP_EOL;
echo 'Residential Complexes: ' . App\Models\ResidentialComplex::count() . PHP_EOL;
echo 'Buildings: ' . App\Models\Building::count() . PHP_EOL;
echo 'Apartments: ' . App\Models\Apartment::count() . PHP_EOL;
echo 'Best Offers: ' . App\Models\BestOffer::whereNull('deleted_at')->count() . PHP_EOL;
"
```

### Проверка best offers по городам

```bash
docker-compose exec php-fpm php artisan tinker --execute="
App\Models\BestOffer::whereNull('deleted_at')
    ->groupBy('location_code')
    ->selectRaw('location_code, count(*) as count')
    ->get()
    ->each(function(\$item) { 
        echo \$item->location_code . ': ' . \$item->count . ' записей' . PHP_EOL; 
    });
"
```

## Дополнительные команды

### Исправление сокращений в названиях городов

```bash
docker-compose exec php-fpm php artisan app:fix-location-abbreviations
```

### Проверка статуса очереди RabbitMQ

```bash
docker-compose exec php-fpm php artisan tinker --execute="
\$processor = app(App\Scrapper\TrendAgentScrapper\Queue\RabbitMQQueueProcessor::class);
\$status = \$processor->getQueueStatus();
foreach(\$status as \$queue => \$info) {
    echo \$queue . ': ' . \$info['messages'] . ' сообщений, ' . \$info['consumers'] . ' потребителей' . PHP_EOL;
}
"
```

## Устранение проблем

### Если данные не загружаются

1. Проверьте подключение к RabbitMQ
2. Убедитесь, что очередь обрабатывается
3. Проверьте логи: `docker-compose logs php-fpm`

### Если кэш не обновляется

1. Проверьте настройки Memcached в `.env`
2. Убедитесь, что `CACHE_DRIVER=memcached`
3. Проверьте доступность сервиса memcached

### Если best offers не создаются

1. Убедитесь, что есть жилые комплексы с квартирами
2. Проверьте связь квартир с комплексами через `complex_key`
3. Проверьте, что квартиры имеют цену >= 7,000,000 рублей

## Структура данных

- **locations** - города и регионы
- **builders** - застройщики  
- **residential_complexes** - жилые комплексы
- **buildings** - здания/корпуса
- **apartments** - квартиры
- **best_offers** - лучшие предложения (с soft delete)

## Важные замечания

- Всегда запускайте скрапер для каждого города отдельно
- Дождитесь полной обработки очереди квартир перед обновлением кэша
- Обновляйте best offers после обновления кэша
- Используйте `--clear` для полной пересборки best offers