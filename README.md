# API для управления контрагентами

Это API для управления контрагентами, разработанное на Laravel. 
Оно предоставляет возможности для создания, чтения, контрагентов.

---

## Оглавление

- [Установка](#установка)
- [Настройка](#настройка)
- [Использование](#использование)
- [API Endpoints](#api-endpoints)
- [Тестирование](#тестирование)
---

## Установка

1. **Клонируйте репозиторий**:
   ```bash
   git clone https://github.com/klimushkin1942/einzelwerk.git
   cd ваш-репозиторий
   ```

2. **Установите зависимости**:
   ```bash
   composer install
   ```

3. **Создайте файл `.env`**:
   Скопируйте `.env.example` в `.env` и настройте параметры:
   ```bash
   cp .env.example .env
   ```

4. **Сгенерируйте ключ приложения**:
   ```bash
   php artisan key:generate
   ```

5. **Настройте базу данных**:
   Укажите параметры подключения к базе данных в файле `.env`:
   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=your_database_name
   DB_USERNAME=your_database_user
   DB_PASSWORD=your_database_password
   ```

6. **Запустите миграции**:
   ```bash
   php artisan migrate
   ```

7. **Запустите сидеры (опционально)**:
   Если у вас есть сидеры для заполнения базы данных тестовыми данными:
   ```bash
   php artisan db:seed
   ```

8. **Запустите сервер**:
   ```bash
   php artisan serve
   ```

---

## Настройка

### Sanctum (API Аутентификация)

Для использования API необходимо настроить Sanctum:

1. Убедитесь, что Sanctum установлен:
   ```bash
   composer require laravel/sanctum
   ```

2. Опубликуйте конфигурации Sanctum:
   ```bash
   php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
   ```

3. Запустите миграции:
   ```bash
   php artisan migrate
   ```

4. Настройте middleware `auth:sanctum` для защищенных роутов.

---

## Использование

### Регистрация и аутентификация

#### Регистрация

Отправьте POST-запрос на `/api/register` с данными:
```json
{
    "email": "email@example.com",
    "password": "ваш_пароль"
}
```

#### Аутентификация

Отправьте POST-запрос на `/api/login` с данными:
```json
{
    "email": "email@example.com",
    "password": "ваш_пароль"
}
```

#### Использование токена

После успешной аутентификации используйте токен в заголовке `Authorization` для доступа к защищенным роутам:
```
Authorization: Bearer ваш_токен
```

---

## API Endpoints

### Контрагенты

#### Получить список контрагентов

```
GET /api/counterparties
```

#### Создать контрагента

```
POST /api/counterparties
```

Тело запроса:
```json
{
    "name": "Название контрагента",
    "user_id": 1
}
```

---

## Тестирование

Для тестирования API используйте инструменты, подходящие для отправки HTTP-запросов, например, Postman или Curl.

---
