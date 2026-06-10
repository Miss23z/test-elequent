# Создание модели Eloquent для трека

Реализация модели `Track` для MVP музыкального плеера наподобие «Яндекс Музыки» или «Spotify». Сервис предназначен для использования в России.

## Контекст

Модель спроектирована как ядро каталога треков. Помимо самой модели создан полный пайплайн: миграция → factory → seeder → контроллер → blade-выдача.

## Быстрый старт

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan serve
```

Открыть `http://localhost:8000` — таблица треков с пагинацией, связями, текстами песен.

Без внешней БД — SQLite (`database/database.sqlite`).

---

## Модель Track — описание полей

| Поле | Тип | Зачем нужно |
|------|-----|-------------|
| `title` | string(255) | Название трека |
| `lyric` | json (nullable) | Текст песни. JSON выбран чтобы в будущем хранить субтитры с таймкодами (`[{time: 0.0, text: "..."}]`). Пока что одна строка |
| `audio_url` | string(255) (nullable) | Ссылка на аудио. Сейчас поле — при росте проекта можно заменить полиморфной связью (разные форматы/качества) |
| `cover_url` | string(255) (nullable) | Ссылка на обложку. Аналогично — под замену на полиморфную связь |
| `duration` | unsignedInteger | Длительность в миллисекундах. |
| `age_rating` | enum (AgeRating) | Возрастной рейтинг. Enum `0+`, `6+`, `12+`, `16+`, `18+`. При выходе в другие страны — замена на `belongsToMany` (разные системы рейтингов) |
| `play_count` | unsignedBigInteger | Счётчик прослушиваний. Позволяет сортировку по популярности |
| `copyright_holder_id` | foreignId → holder | Правообладатель (лейбл, артист и т.д.). Отдельная сущность чтобы у одного трека был один правообладатель, но у одного правообладателя — много треков |
| `licensed_at` | timestamp (nullable) | Когда оформлена лицензия. Может отличаться от даты загрузки |
| `license_expires_at` | timestamp (nullable) | Когда истекает лицензия. Позволяет автоматически скрывать треки с истёкшими правами |
| `version` | string(10) | Версия трека (1.0.0, 2.0.0 и т.д.) |
| `released_at` | timestamp (nullable) | Дата релиза песни. Отличается от `created_at` (дата загрузки в систему) |
| `is_active` | boolean | Доступен ли трек пользователям. Позволяет скрывать трек без удаления |
| `timestamps` | | `created_at` / `updated_at` |
| `softDeletes` | | Мягкое удаление — трек снят с площадки, но данные сохранены (история, плейлисты, статистика) |

### Связи

| Связь | Тип | Таблица | Обоснование |
|-------|-----|---------|-------------|
| artists | belongsToMany | artist_track | У трека может быть несколько соавторов |
| albums | belongsToMany | album_track | Трек может входить в несколько альбомов (каждый соавтор добавил совместный трек в свой альбом) |
| genres | belongsToMany | genre_track | Простая реализация жанров для MVP. Для качественного поиска по похожим трекам в будущем потребуется более сложная система |
| copyrightHolder | belongsTo | tracks.copyright_holder_id | Правообладатель трека |

### Индексы

| Индекс | Зачем |
|--------|-------|
| `title` | Поиск по названию |
| `(is_active, released_at)` | Выдача активных треков с сортировкой по дате релиза |
| `(is_active, play_count)` | Выдача активных треков с сортировкой по популярности |
| `license_expires_at` | Мониторинг истекающих лицензий |
| pivot-таблицы: индексы на `artist_id`, `album_id`, `genre_id` | Обратный поиск (треки артиста / альбома / жанра) |

---

## Что может быть улучшено при развитии проекта

### Поля и типы
- **audio_url / cover_url** → замена на полиморфную связь `media()` (Laravel MediaLibrary или своя), чтобы хранить файлы в разных форматах и качествах
- **age_rating** → замена enum на `belongsToMany` с таблицей возрастных рейтингов — для поддержки разных систем рейтингов при экспансии в другие страны
- **lyric** → структура с таймкодами для субтитров (уже учтено в типе `json`)
- **play_count** → вынести в отдельную таблицу `track_plays` с аналитикой (кто, когда, откуда), а текущее поле использовать как кешированный агрегат
- **version** → отдельная таблица `track_versions` если потребуется хранить историю версий

### Связи
- **genres** → иерархические жанры (дерево) + система тегов для качественного поиска похожих треков
- Добавить связь `playlists` (belongsToMany) — плейлисты пользователей
- Добавить связь `listens` (hasMany) — история прослушиваний для персональных рекомендаций

### Инфраструктура
- Перейти с SQLite на PostgreSQL — полнотекстовый поиск по `title` и `lyric` через `tsvector`, поддержка конкурентной записи
- Кеширование счётчиков (`play_count`) через Redis — чтобы не обновлять запись трека при каждом прослушивании
- Очереди для асинхронного инкремента `play_count`

### Администрирование
- Filament / Nova — админка для управления треками, правообладателями, лицензиями
- Добавить `licensed_at` / `license_expires_at` в планировщик (`php artisan schedule`) для автоматической деактивации треков с истёкшей лицензией

---

## Структура проекта

```
app/
├── Enums/
│   └── AgeRating.php
├── Http/Controllers/
│   └── TrackController.php
└── Models/
    ├── Track.php
    ├── Artist.php
    ├── Album.php
    ├── Genre.php
    └── Holder.php

database/
├── migrations/
│   ├── 2026_06_09_195437_create_artists_table.php
│   ├── 2026_06_09_195755_create_albums_table.php
│   ├── 2026_06_09_200034_create_genres_table.php
│   ├── 2026_06_09_200156_create_holder_table.php
│   ├── 2026_06_09_200306_create_tracks_table.php
│   ├── 2026_06_09_200307_create_artist_track_table.php
│   ├── 2026_06_09_200308_create_album_track_table.php
│   └── 2026_06_09_200309_create_genre_track_table.php
├── factories/
│   ├── TrackFactory.php
│   ├── ArtistFactory.php
│   ├── AlbumFactory.php
│   ├── GenreFactory.php
│   └── HolderFactory.php
└── seeders/
    ├── DatabaseSeeder.php
    ├── ArtistSeeder.php
    ├── AlbumSeeder.php
    ├── GenreSeeder.php
    ├── HolderSeeder.php
    └── TrackSeeder.php

resources/views/
└── tracks.blade.php
```

### Детали реализации
- **AgeRating** — string-backed enum, используется в миграции и касте модели
- **Soft Deletes** — у всех 5 моделей, чтобы не терять данные при удалении
- **PHP-атрибуты** — `#[Fillable]`, метод `casts()`
