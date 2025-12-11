@echo off
echo ========================================
echo   CEK FILE UNTUK HOSTING
echo ========================================
echo.

echo [1] Cek Gambar Produk di Storage...
echo.
dir /b storage\app\public\products\*.jpg 2>nul
dir /b storage\app\public\products\*.png 2>nul
dir /b storage\app\public\products\*.webp 2>nul
echo.

echo [2] Cek Gambar Kategori...
echo.
dir /b storage\app\public\categories\*.jpg 2>nul
dir /b storage\app\public\categories\*.png 2>nul
echo.

echo [3] Cek Build Assets...
echo.
if exist "public\build" (
    echo [OK] Folder public\build sudah ada
    dir /b public\build\assets\*.js 2>nul | find /c /v "" > nul
    if errorlevel 1 (
        echo [WARNING] Tidak ada file JS di public\build\assets
        echo [ACTION] Jalankan: npm run build
    ) else (
        echo [OK] File JS ditemukan
    )
) else (
    echo [ERROR] Folder public\build TIDAK ADA!
    echo [ACTION] Jalankan: npm run build
)
echo.

echo [4] Cek File Penting...
echo.
if exist ".env" (
    echo [OK] .env ada (JANGAN upload file ini!)
) else (
    echo [WARNING] .env tidak ada
)

if exist ".env.example" (
    echo [OK] .env.example ada
) else (
    echo [ERROR] .env.example tidak ada
)

if exist "composer.json" (
    echo [OK] composer.json ada
) else (
    echo [ERROR] composer.json tidak ada
)

if exist "package.json" (
    echo [OK] package.json ada
) else (
    echo [ERROR] package.json tidak ada
)
echo.

echo [5] Estimasi Ukuran Upload...
echo.
echo Menghitung ukuran folder...
for /f "tokens=3" %%a in ('dir /s storage\app\public ^| find "File(s)"') do set storage_size=%%a
echo - storage/app/public: %storage_size% bytes
echo.

echo ========================================
echo   SUMMARY
echo ========================================
echo.
echo YANG HARUS DI-UPLOAD:
echo [✓] Semua file Laravel (app, config, routes, resources, dll)
echo [✓] Folder storage/app/public/ (SEMUA ISI)
echo [✓] Folder public/ (termasuk public/build)
echo [✓] File .env.example (untuk template)
echo.
echo JANGAN DI-UPLOAD:
echo [✗] node_modules/
echo [✗] vendor/
echo [✗] .env (buat baru di hosting)
echo [✗] .git/
echo [✗] database/*.sqlite (jika pakai MySQL)
echo.
echo ========================================
echo   NEXT STEPS
echo ========================================
echo.
echo 1. Pastikan npm run build sudah dijalankan
echo 2. Export database via phpMyAdmin
echo 3. Compress project (zip) kecuali folder yang tidak perlu
echo 4. Upload ke hosting
echo 5. Ikuti panduan di: TROUBLESHOOTING-HOSTING.md
echo.
pause
