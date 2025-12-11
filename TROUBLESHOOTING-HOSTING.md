# 🚨 TROUBLESHOOTING HOSTING ISSUES

## Masalah yang Anda Alami:

### ❌ **1. Gambar Produk Tidak Muncul**
**Penyebab:**
- Folder `storage/app/public/products/` tidak ter-upload
- Symbolic link `public/storage` belum dibuat
- Permission folder storage salah

**Solusi:**

**A. Pastikan File Gambar Ter-upload:**
```bash
# Cek di hosting, folder ini harus ada dan berisi file gambar:
storage/app/public/products/
storage/app/public/avatars/
storage/app/public/categories/
storage/app/public/banners/
storage/app/public/contents/
storage/app/public/background/
```

**B. Buat Storage Link:**
```bash
# Via SSH
php artisan storage:link
```

**Jika error "symlink not supported"**, gunakan cara manual:

**Cara 1: Via cPanel File Manager**
1. Masuk ke **File Manager**
2. Hapus folder `public/storage` jika ada
3. Klik **Create Symbolic Link**
4. From: `/home/username/domains/domain.com/storage/app/public`
5. To: `/home/username/domains/domain.com/public/storage`

**Cara 2: Edit .htaccess**

Tambahkan di file `public/.htaccess` (setelah `RewriteEngine On`):

```apache
# Storage redirect
RewriteCond %{REQUEST_URI} ^/storage/(.*)$
RewriteRule ^storage/(.*)$ ../storage/app/public/$1 [L]
```

**C. Set Permission:**
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Jika masih error:
chmod -R 777 storage
chmod -R 777 bootstrap/cache
```

---

### ❌ **2. Data Produk/Testimonial Kosong**

**Penyebab:**
- Database hosting kosong (belum di-import)
- Koneksi database salah di `.env`

**Solusi:**

**A. Export Database dari Local:**

1. **Via phpMyAdmin:**
   - Buka http://localhost/phpmyadmin
   - Pilih database `WebSeblak_umi_ai`
   - Tab **Export** → Quick → SQL → **Go**
   - Download file `.sql`

2. **Via Command Line:**
```bash
# Windows (XAMPP)
cd C:\xampp\mysql\bin
mysqldump -u root WebSeblak_umi_ai > C:\backup.sql

# Atau via Laravel
php artisan db:show  # Cek database name
```

**B. Import ke Hosting:**

1. **Via phpMyAdmin Hosting:**
   - Login ke cPanel → phpMyAdmin
   - Pilih database hosting
   - Tab **Import**
   - Upload file `.sql`
   - Klik **Go**

2. **Via SSH:**
```bash
mysql -u username_db -p nama_database < backup.sql
```

**C. Cek Koneksi Database di `.env`:**

```env
DB_CONNECTION=mysql
DB_HOST=localhost          # Biasanya localhost
DB_PORT=3306
DB_DATABASE=nama_db_hosting    # Sesuaikan!
DB_USERNAME=user_db_hosting    # Sesuaikan!
DB_PASSWORD=pass_db_hosting    # Sesuaikan!
```

**D. Test Koneksi:**
```bash
php artisan migrate:status
# Jika berhasil, akan muncul list migration
```

---

### ❌ **3. Register/Login Error**

**Penyebab:**
- Email verification error
- Session driver error
- CSRF token mismatch

**Solusi:**

**A. Disable Email Verification (Sementara):**

Edit `app/Models/User.php`:
```php
class User extends Authenticatable // Hapus: implements MustVerifyEmail
{
    // ...
}
```

**B. Set Session Driver di `.env`:**
```env
SESSION_DRIVER=database
SESSION_LIFETIME=120
```

**C. Clear Cache:**
```bash
php artisan config:clear
php artisan cache:clear
php artisan session:table  # Pastikan table sessions ada
php artisan migrate        # Jika belum
```

**D. Cek APP_KEY:**
```bash
php artisan key:generate
```

**E. Set APP_URL yang Benar:**
```env
APP_URL=https://domain-anda.com  # Harus HTTPS jika hosting pakai SSL
```

---

### ❌ **4. CSS/JS Tidak Load**

**Penyebab:**
- Asset belum di-build untuk production
- Path asset salah
- APP_URL salah

**Solusi:**

**A. Build Production Assets:**
```bash
# Di local
npm run build

# Upload folder public/build/ ke hosting
```

**B. Cek APP_URL di `.env`:**
```env
APP_URL=https://domain-anda.com
```

**C. Clear View Cache:**
```bash
php artisan view:clear
php artisan view:cache
```

---

## 📋 CHECKLIST DEPLOY

Gunakan checklist ini untuk memastikan semua sudah benar:

### **Pre-Deploy (Di Local):**
- [ ] Export database via phpMyAdmin
- [ ] Run `npm run build` untuk build assets
- [ ] Pastikan semua fitur berfungsi di local
- [ ] Catat semua file gambar yang ada di `storage/app/public/`

### **Upload Files:**
- [ ] Upload semua file Laravel (kecuali: node_modules, vendor, .env, .git)
- [ ] Upload folder `storage/app/public/` dengan SEMUA isinya
- [ ] Upload folder `public/build/` hasil npm run build

### **Setup di Hosting:**
- [ ] Buat database baru di cPanel
- [ ] Import file `.sql` ke database hosting
- [ ] Copy `.env.example` → `.env`
- [ ] Edit `.env` sesuaikan DB_HOST, DB_DATABASE, DB_USERNAME, DB_PASSWORD
- [ ] Edit `.env` set APP_URL sesuai domain
- [ ] Run `composer install --optimize-autoloader --no-dev`
- [ ] Run `php artisan key:generate`
- [ ] Run `php artisan storage:link`
- [ ] Set permission: `chmod -R 775 storage bootstrap/cache`
- [ ] Run `php artisan migrate:status` (cek koneksi DB)
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`

### **Setup Public Folder:**
- [ ] Pastikan document root mengarah ke folder `public/`
- [ ] ATAU buat `.htaccess` redirect di root

### **Testing:**
- [ ] Buka website, cek halaman utama load
- [ ] Cek gambar produk muncul
- [ ] Cek data produk/testimonial tampil
- [ ] Test register user baru
- [ ] Test login
- [ ] Test add to cart
- [ ] Test checkout (jangan sampai payment real!)

---

## 🔍 DEBUG TIPS

### **Cek Error Log:**
```bash
# Via SSH
tail -f storage/logs/laravel.log

# Via File Manager
# Download file: storage/logs/laravel.log
```

### **Enable Debug Mode (SEMENTARA!):**
```env
APP_DEBUG=true
```
⚠️ **INGAT:** Set kembali ke `false` setelah selesai debug!

### **Cek URL Gambar:**
1. Buka website
2. Klik kanan pada gambar yang tidak muncul
3. "Open image in new tab"
4. Lihat URL-nya, contoh:
   - ✅ Benar: `https://domain.com/storage/products/seblak.jpg`
   - ❌ Salah: `https://domain.com/storage/app/public/products/seblak.jpg`

Jika URL salah, berarti storage link belum dibuat dengan benar.

### **Cek Database Connection:**
```bash
php artisan tinker
>>> DB::connection()->getPdo();
# Jika berhasil, akan muncul object PDO
# Jika error, cek .env
```

### **Cek Permission:**
```bash
ls -la storage/
ls -la bootstrap/cache/
# Pastikan owner dan permission benar
```

---

## 🆘 MASIH ERROR?

Jika masih error setelah semua langkah di atas:

1. **Screenshot error message** yang muncul
2. **Copy isi file** `storage/logs/laravel.log` (bagian terakhir)
3. **Cek browser console** (F12 → Console tab)
4. **Cek Network tab** (F12 → Network) untuk lihat request yang gagal

Dengan informasi ini, kita bisa debug lebih lanjut!

---

## 📞 QUICK COMMANDS

```bash
# Clear semua cache
php artisan optimize:clear

# Optimize untuk production
php artisan optimize

# Cek routes
php artisan route:list

# Cek database
php artisan db:show

# Cek migration status
php artisan migrate:status

# Recreate storage link
php artisan storage:link

# Generate APP_KEY
php artisan key:generate
```

---

**Good luck! 🚀**
