Write-Host "=== Nextronix Laravel 11 Setup & Run ===" -ForegroundColor Cyan

if (-Not (Test-Path ".env")) {
    Copy-Item ".env.example" ".env" -Force
    Write-Host ".env dibuat dari .env.example" -ForegroundColor Green
}

if (-Not (Test-Path "vendor")) {
    Write-Host "Menjalankan composer install..." -ForegroundColor Yellow
    composer install
}

if (-Not (Test-Path "database\database.sqlite")) {
    New-Item "database\database.sqlite" -ItemType File -Force | Out-Null
    Write-Host "database.sqlite dibuat" -ForegroundColor Green
}

php artisan key:generate --force
php artisan migrate --seed
php artisan storage:link
php artisan optimize:clear

Write-Host "Website: http://127.0.0.1:8000" -ForegroundColor Cyan
Write-Host "Admin:   http://127.0.0.1:8000/login" -ForegroundColor Cyan
Write-Host "Login:   admin@nextronix.test / password" -ForegroundColor Cyan
php artisan serve
