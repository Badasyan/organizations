# Запуск проекта локально 

#### Установить зависимости
` composer install `

#### Запустить Docker-контейнеры
` docker-compose up -d `

#### Выполнить миграции и сидеры

` docker-compose exec app php artisan migrate --seed `

#### Генерация Swagger документации

` php artisan l5-swagger:generate `
