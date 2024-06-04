# Kommo Widget

## Установка

1. Установите веб-сервер с поддержкой PHP и MySQL (например, XAMPP, WAMP, MAMP).
2. Создайте новую базу данных MySQL с именем `kommo_widget`.
3. Создайте таблицу `tasks` в базе данных со следующими столбцами:
    - `id` (INT, AUTO_INCREMENT, PRIMARY KEY)
    - `user_id` (INT)
    - `date_time` (DATETIME)
4. Установите Composer.
5. Перейдите в директорию проекта в терминале.
6. Выполните команду `composer install` для установки зависимостей проекта.
7. Обновите учетные данные базы данных в файле `src/Widget.php` в соответствии с вашей средой.
8. Обновите конфигурацию OAuth в файле `public/api.php` с вашими учетными данными Kommo CRM.

## Запуск

1. Запустите веб-сервер.
2. Убедитесь, что база данных MySQL запущена.
3. Откройте виджет в веб-браузере, перейдя по адресу `http://localhost/kommo-widget/public/index.html`.

## Размещение виджета в Kommo CRM

1. Войдите в ваш аккаунт Kommo CRM.
2. Перейдите в раздел "Настройки и интеграции".
3. Выберите "Приватная интеграция".
4. Следуйте инструкциям для добавления нового виджета, используя URL вашего виджета (например, `http://localhost/kommo-widget/public/index.html`).
5. Сохраните изменения и убедитесь, что виджет отображается в разделе "Настройки и интеграции".

