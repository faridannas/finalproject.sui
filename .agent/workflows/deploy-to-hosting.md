---
description: Panduan Deploy Laravel ke Hosting
---

# Panduan Deploy Laravel ke Hosting

## Masalah yang Sering Terjadi:
1. ❌ Gambar produk tidak muncul
2. ❌ Data produk/testimonial kosong
3. ❌ Register/Login error
4. ❌ CSS/JS tidak load

---

## ✅ SOLUSI LENGKAP

### **STEP 1: Export Database dari Local**

1. Buka **phpMyAdmin** di local (http://localhost/phpmyadmin)
2. Pilih database `WebSeblak_umi_ai`
3. Klik tab **Export**
4. Pilih **Quick** export method
5. Format: **SQL**
6. Klik **Go** untuk download file `.sql`

**ATAU via Command Line:**

```bash
# Ganti 'root' dengan username MySQL Anda
# Ganti 'WebSeblak_umi_ai' dengan nama database Anda
mysqldump -u root -p WebSeblak_umi_ai > database_backup.sql
```

---

### **STEP 2: Upload File ke Hosting**

**A. Upload Kode Laravel:**

Via **cPanel File Manager** atau **FTP**:

1. **Compress project** (kecuali folder ini):
   - ❌ `/node_modules` (jangan upload)
   - ❌ `/vendor` (jangan upload)
   - ❌ `.env` (jangan upload)
   - ✅ Upload semua file lainnya

2. **Upload ke hosting** di folder `public_html` atau `www`

3. **Extract** file zip di hosting

**B. Upload Folder Storage (PENTING!):**

Upload folder ini dengan **semua isinya**:
```
storage/app/public/products/
storage/app/public/avatars/
storage/app/public/categories/
storage/app/public/banners/
storage/app/public/contents/
storage/app/public/background/
```

---

### **STEP 3: Setup di Hosting**

**A. Install Dependencies:**

Login ke **SSH/Terminal hosting**, lalu jalankan:

```bash
cd public_html  # atau folder project Anda

# Install Composer dependencies
composer install --optimize-autoloader --no-dev

# Install NPM dependencies (jika perlu)
npm install
npm run build
```

**B. Setup Environment (.env):**

1. Copy `.env.example` menjadi `.env`:
```bash
cp .env.example .env
```

2. Edit `.env` sesuaikan dengan hosting:
```env
APP_NAME="Seblak Umi AI"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://domain-anda.com

# Database Hosting
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=nama_database_hosting
DB_USERNAME=username_database_hosting
DB_PASSWORD=password_database_hosting

# Session & Cache
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database

# Filesystem
FILESYSTEM_DISK=public
```

3. Generate APP_KEY:
```bash
php artisan key:generate
```

**C. Import Database:**

1. Buka **phpMyAdmin** di hosting
2. Buat database baru (misal: `seblak_umi_ai`)
3. Pilih database tersebut
4. Klik tab **Import**
5. Upload file `.sql` yang tadi di-export
6. Klik **Go**

**ATAU via SSH:**
```bash
mysql -u username_db -p nama_database < database_backup.sql
```

---

### **STEP 4: Setup Storage Link**

**PENTING!** Ini agar gambar bisa diakses:

```bash
php artisan storage:link
```

Jika error "symlink not allowed", gunakan cara manual:

**Di cPanel File Manager:**
1. Hapus folder `public/storage` jika ada
2. Buat **Symbolic Link**:
   - From: `/home/username/public_html/storage/app/public`
   - To: `/home/username/public_html/public/storage`

**ATAU** edit `.htaccess` di folder `public`:

```apache
# Tambahkan ini di atas
RewriteEngine On

# Redirect storage
RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
```

---

### **STEP 5: Set Permissions (PENTING!)**

Via SSH atau File Manager, set permission:

```bash
# Storage & Bootstrap Cache harus writable
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika masih error, coba:
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

---

### **STEP 6: Setup Public Folder sebagai Root**

**Jika hosting menggunakan `public_html`:**

**Opsi A: Pindahkan isi folder `public` ke `public_html`**

1. Pindahkan semua file dari `public/` ke `public_html/`
2. Edit `public_html/index.php`:

```php
// Ubah baris ini:
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';

// Menjadi:
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
```

3. Pindahkan semua folder Laravel (app, config, routes, dll) ke `public_html/`

**Opsi B: Gunakan .htaccess redirect** (Lebih mudah)

Buat file `.htaccess` di root `public_html`:

```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

---

### **STEP 7: Clear Cache**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

---

### **STEP 8: Cek Register Error**

Jika register masih error, cek:

1. **Mail Configuration** di `.env`:
```env
MAIL_MAILER=log
# Atau gunakan SMTP hosting
```

2. **Cek log error**:
```bash
tail -f storage/logs/laravel.log
```

3. **Disable email verification** (jika tidak perlu):

Edit `app/Models/User.php`:
```php
// Hapus atau comment ini:
// implements MustVerifyEmail
```

---

## 🔍 Troubleshooting

### **Gambar Tidak Muncul:**
- ✅ Pastikan folder `storage/app/public/products` ter-upload
- ✅ Pastikan `php artisan storage:link` sudah dijalankan
- ✅ Cek permission folder `storage` (775 atau 777)
- ✅ Cek URL gambar di browser (klik kanan > open image in new tab)

### **Data Kosong:**
- ✅ Pastikan database sudah di-import
- ✅ Cek koneksi database di `.env`
- ✅ Jalankan: `php artisan migrate:status`

### **500 Internal Server Error:**
- ✅ Set `APP_DEBUG=true` sementara untuk lihat error
- ✅ Cek `storage/logs/laravel.log`
- ✅ Pastikan permission folder `storage` dan `bootstrap/cache`

### **CSS/JS Tidak Load:**
- ✅ Jalankan `npm run build` di local
- ✅ Upload folder `public/build` ke hosting
- ✅ Cek `APP_URL` di `.env` sudah benar

---

## 📋 Checklist Final

- [ ] Database di-export dari local
- [ ] Database di-import ke hosting
- [ ] File Laravel ter-upload (kecuali node_modules & vendor)
- [ ] Folder storage/app/public ter-upload lengkap
- [ ] Composer install dijalankan
- [ ] .env sudah dikonfigurasi
- [ ] APP_KEY sudah di-generate
- [ ] Storage link sudah dibuat
- [ ] Permission storage & bootstrap/cache sudah di-set
- [ ] Cache sudah di-clear
- [ ] Public folder sudah jadi document root
- [ ] Website bisa diakses dan gambar muncul

---

## 🚀 Selesai!

Setelah semua langkah di atas, website Anda di hosting seharusnya sama persis dengan di local.

**Jika masih ada masalah, cek:**
1. `storage/logs/laravel.log` untuk error detail
2. Browser Console (F12) untuk error JavaScript/CSS
3. Network tab untuk lihat file mana yang gagal load
