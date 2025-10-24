cp .env.example .env
composer install
php artisan key:generate
php artisan storage:link --force
php artisan migrate --seed
npm install