

### 1. Как связывать объекты между собой

Данные по квартирам находятся в фиде https://dataout.trendagent.ru/spb/apartments.json

Связать их с ЖК из фида https://dataout.trendagent.ru/spb/blocks.json можно по block_id

### 2. Какую информацию можно использовать для вывода на карту

Координаты ЖК есть в фиде с ЖК https://dataout.trendagent.ru/spb/blocks.json

Координаты корпусов есть в фиде с корпусами https://dataout.trendagent.ru/spb/buildings.json

Связать их с ЖК из фида https://dataout.trendagent.ru/spb/blocks.json можно по block_id

### 3. Описание полей в фиде
| Название раздела | Описание | Свойства | Ссылка на файл | Дата последнего обновления |
|------------------|----------|----------|----------------|----------------------------|
| ЖК | Жилые комплексы | district: ID региона<br>locations: ID локаций<br>subway: Метро<br>subway_id: ID метро<br>distance_time: Расстояние (в минутах)<br>distance_type: Тип расстояния (1 - пешком, 2 - транспортом)<br>geometry: Геометка ЖК<br>renderer: Рендеры ЖК<br>progress: Ход строительства<br>plan: Ген. план | https://dataout.trendagent.ru/spb/blocks.json | 2025-01-13T07:57:55.087Z |
| Застройщики | Застройщики | - | https://dataout.trendagent.ru/spb/builders.json | 2025-01-13T07:57:55.087Z |
| Регионы | Районы | - | https://dataout.trendagent.ru/spb/regions.json | 2025-01-13T07:57:55.087Z |
| Метро | Метро | - | https://dataout.trendagent.ru/spb/subways.json | 2025-01-13T07:57:55.087Z |
| Комнатность | Комнатность | - | https://dataout.trendagent.ru/spb/rooms.json | 2025-01-13T07:57:55.087Z |
| Отделка | Отделка | - | https://dataout.trendagent.ru/spb/finishings.json | 2025-01-13T07:57:55.087Z |
| Технология строительства | Технология строительства | - | https://dataout.trendagent.ru/spb/buildingtypes.json | 2025-01-13T07:57:55.087Z |
| Корпуса | Корпуса | name: Название корпуса<br>queue: Очередь<br>address: Адрес<br>deadline: Срок сдачи<br>building_type: ID Технологии строительства<br>mortgages: ID Ипотечных программ | https://dataout.trendagent.ru/spb/buildings.json | 2025-01-13T07:57:55.087Z |
| Квартиры | Квартиры | area_given: Приведённая площадь<br>area_total: Общая площадь<br>area_rooms_total: Жилая площадь<br>finishing: ID Отделки<br>floor: Этаж<br>floors: Этажей в секции<br>number: Номер квартиры (по ПИБ/БТИ)<br>plan: Планировка<br>price: Цена при 100% оплате<br>price_base: Базовая цена<br>room: ID Комнатности | https://dataout.trendagent.ru/spb/apartments.json | 2025-01-13T07:57:55.087Z |

### 4. Как выглядит фид

Корневой объект
```
[
  {
    "name": "blocks",
    "description": "Жилые комплексы",
    "props_description": {
      "district": "ID региона",
      "locations": "ID локаций",
      "subway": {
        "subway_id": "ID метро",
        "distance_time": "Расстояние (в минутах)",
        "distance_type": "Тип расстояния (1 - пешком, 2 - транспортом)"
      },
      "geometry": "Геометка ЖК",
      "renderer": "Рендеры ЖК",
      "progress": "Ход строительства",
      "plan": "Ген. план"
    },
    "url": "https://dataout.trendagent.ru/spb/blocks.json",
    "exported_at": "2022-08-01T01:00:09.002Z"
  },
  {
    "name": "builders",
    "description": "Застройщики",
    "url": "https://dataout.trendagent.ru/spb/builders.json",
    "exported_at": "2022-08-01T01:00:09.185Z"
  },
  {
    "name": "regions",
    "description": "Районы",
    "url": "https://dataout.trendagent.ru/spb/regions.json",
    "exported_at": "2022-08-01T01:00:09.329Z"
  },
  {
    "name": "subways",
    "description": "Метро",
    "url": "https://dataout.trendagent.ru/spb/subways.json",
    "exported_at": "2022-08-01T01:00:09.455Z"
  },
  {
    "name": "buildings",
    "description": "Корпуса",
    "props_description": {
      "name": "Наименование корпуса",
      "queue": "Очередь",
      "address": "Адрес",
      "deadline": "Срок сдачи",
      "building_type": "ID Технологии строительства",
      "mortgages": "ID Ипотечных программ"
    },
    "url": "https://dataout.trendagent.ru/spb/buildings.json",
    "exported_at": "2022-08-01T01:00:10.124Z"
  },
  {
    "name": "apartments",
    "description": "Квартиры",
    "props_description": {
      "area_given": "Приведённая площадь",
      "area_total": "Общая площадь",
      "area_rooms_total": "Жилая площадь",
      "finishing": "ID Отделки",
      "floor": "Этаж",
      "floors": "Этажей в секции",
      "number": "Номер квартиры (по ПИБ/БТИ)",
      "plan": "Планировка",
      "price": "Цена при 100% оплате",
      "price_base": "Базовая цена",
      "room": "ID Комнатности"
    },
    "url": "https://dataout.trendagent.ru/spb/apartments.json",
    "exported_at": "2022-08-01T01:00:47.895Z"
  },
  {
    "name": "room",
    "description": "Комнатность",
    "url": "https://dataout.trendagent.ru/spb/room.json",
    "exported_at": "2022-08-01T01:00:47.972Z"
  },
  {
    "name": "finishings",
    "description": "Отделка",
    "url": "https://dataout.trendagent.ru/spb/finishings.json",
    "exported_at": "2022-08-01T01:00:48.154Z"
  },
  {
    "name": "buildingtypes",
    "description": "Технология строительства",
    "url": "https://dataout.trendagent.ru/spb/buildingtypes.json",
    "exported_at": "2022-08-01T01:00:48.238Z"
  }
]
```

