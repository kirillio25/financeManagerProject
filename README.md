## Запуск докера
docker compose up -d


## Первый запуск проекта 
composer install
npm intsall
npm run dev
cp .env.example .env

Настроить .env и .env.testing.
Пример:
DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=my_database
DB_USERNAME=user
DB_PASSWORD=user_password

php artisan key:generate


php artisan migrate
npm run build



## Заполнения данных и тестирование
php artisan migrate --env=testing

php artisan db:seed
php artisan test
