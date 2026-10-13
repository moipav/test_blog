# Блог на чистом PHP

Блог с категориями и статьями на PHP 8.1+, MySQL и Smarty без использования фреймворков.

## Технологии

- PHP 8.1+
- MySQL 8.0
- Smarty 4
- Docker
- FastRoute
- Faker

## Структура проекта

blog/
├── bin/
│ └── seed.php # Скрипт сидинга БД
├── cache/
│ └── smarty/ # Кэш шаблонов (создаётся автоматически)
├── database/
│ └── migrations/ # SQL-миграции
├── docker/
│ ├── nginx/ # Конфигурация Nginx
│ └── php/ # Dockerfile для PHP
├── public/ # Web root
│ ├── css/
│ ├── index.php # Фронт-контроллер
│ └── uploads/ # Изображения
├── src/
│ ├── Controllers/ # Контроллеры
│ ├── Models/ # Модели
│ └── Services/ # Сервисы (Database, TemplateEngine)
├── templates/
│ ├── layouts/ # Базовые шаблоны
│ └── pages/ # Шаблоны страниц
├── docker-compose.yml
├── composer.json
└── .env.example


## Установка

### 1. Клонировать репозиторий

git clone https://github.com/moipav/test_blog.git
cd test_blog

### 2.настроить окружение
cp .env.example .env

### 3.Запустить докер
docker-compose up -d --build

### 4. Установить зависимости
docker-compose exec php composer install

### 5.Зпустить миграции
docker exec -i blog_mysql mysql -u root -proot_secret blog < database/migrations/001_create_tables.sql
### 6.Запустить сиды
docker-compose exec php php bin/seed.php
