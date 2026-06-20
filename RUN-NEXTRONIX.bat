@echo off
echo === Nextronix Laravel 11 Setup ^& Run ===
if not exist .env copy .env.example .env
if not exist vendor composer install
if not exist database\database.sqlite type nul > database\database.sqlite
php artisan key:generate --force
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear
echo Website: http://127.0.0.1:8000
echo Admin:   http://127.0.0.1:8000/login
echo Login:   admin@nextronix.test / password
php artisan serve
