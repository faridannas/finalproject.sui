@echo off
echo ========================================
echo   PREPARE LARAVEL FOR HOSTING
echo ========================================
echo.

REM 1. Export Database
echo [1/5] Exporting database...
php artisan db:show
echo.
echo NOTE: Silakan export database manual via phpMyAdmin
echo Database: WebSeblak_umi_ai
echo.
pause

REM 2. Build Assets
echo [2/5] Building production assets...
call npm run build
echo.

REM 3. Clear all cache
echo [3/5] Clearing cache...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo.

REM 4. Create deployment folder
echo [4/5] Creating deployment package...
if not exist "deployment" mkdir deployment
echo.

REM 5. Show checklist
echo [5/5] CHECKLIST UNTUK UPLOAD KE HOSTING:
echo.
echo [✓] 1. Export database dari phpMyAdmin (WebSeblak_umi_ai)
echo [✓] 2. Folder 'public/build' sudah di-build
echo [ ] 3. Upload semua file KECUALI:
echo        - node_modules/
echo        - vendor/
echo        - .env
echo        - .git/
echo.
echo [ ] 4. Upload folder storage/app/public/ dengan SEMUA isinya
echo [ ] 5. Di hosting, jalankan: composer install --no-dev
echo [ ] 6. Di hosting, buat file .env dan sesuaikan database
echo [ ] 7. Di hosting, jalankan: php artisan key:generate
echo [ ] 8. Di hosting, jalankan: php artisan storage:link
echo [ ] 9. Di hosting, set permission: chmod -R 775 storage bootstrap/cache
echo [ ] 10. Import database .sql ke hosting
echo.
echo ========================================
echo   SELESAI!
echo ========================================
echo.
echo Baca panduan lengkap di: .agent\workflows\deploy-to-hosting.md
echo.
pause
