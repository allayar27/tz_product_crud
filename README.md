# 🛒 Tz Product Crud

## 📖 Описание
**Tz Product Crud** — это тестовый проект CRUD API для управления продуктами с поиском на **Elasticsearch**.  
Разработан на **Laravel 11** с использованием **PostgreSQL** и **Docker**.

## 🛠 Технологии
- **PHP 8.2**
- **Laravel 11**
- **PostgreSQL**
- **Elasticsearch 8.17.0**
- **Docker + Docker Compose**


---

## 📌 Установка и настройка

1. Клонировать репозиторию из GitHub:

```bash
git clone https://github.com/allayar27/tz_product_crud.git
```

```bash
cd tz-product-crud
```

2. Установите зависимости для Laravel, используя Composer:

```bash
 composer install
```

## 📌 Запуск

3. Поднимаем докер контейнеры
   
```bash
docker-compose up -d --build
```

## ⚙️ Работа с Elasticsearch

Создать индекс:
   
```bash
docker exec -it app php artisan elastic:create-index
```
Удалить индекс:

```bash
docker exec -it app php artisan elastic:delete-index
```