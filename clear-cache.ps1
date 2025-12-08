Write-Host "Clearing Laravel caches..." -ForegroundColor Cyan
php artisan optimize:clear
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
Write-Host "All caches cleared successfully!" -ForegroundColor Green
Read-Host "Press Enter to continue"



