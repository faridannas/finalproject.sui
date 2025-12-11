# Testing Checklist - Seblak Umi AI Website

## 🔍 Status Pengujian: SEDANG BERLANGSUNG
**Tanggal**: 9 Desember 2025  
**Tester**: AI Assistant

---

## 📋 BAGIAN 1: PENGUJIAN LANDING PAGE (Guest/Tidak Login)

### 1.1 Navbar & Navigation
- [ ] Logo website tampil dan klik berfungsi (kembali ke home)
- [ ] Link "Beranda" berfungsi
- [ ] Link "Produk" berfungsi → redirect ke `/products`
- [ ] Link "Kategori" berfungsi → redirect ke `/categories`
- [ ] Link "Testimoni" berfungsi → redirect ke `/testimonials`
- [ ] Button "Login" tampil dan berfungsi
- [ ] Button "Register" tampil dan berfungsi
- [ ] Mobile menu (hamburger) berfungsi di layar kecil

### 1.2 Hero Section
- [ ] Banner/slider tampil dengan benar
- [ ] Gambar banner loading dengan baik
- [ ] CTA button di hero section berfungsi

### 1.3 Products Section
- [ ] Produk tampil dengan gambar yang benar
- [ ] Nama produk, harga, dan deskripsi tampil
- [ ] Button "Lihat Detail" berfungsi
- [ ] Link "Lihat Semua Produk" berfungsi

### 1.4 Footer
- [ ] Informasi kontak tampil
- [ ] Social media links berfungsi (jika ada)
- [ ] Copyright text tampil

---

## 📋 BAGIAN 2: PENGUJIAN AUTENTIKASI

### 2.1 Registrasi User
- [ ] Form registrasi tampil lengkap (name, email, password, confirm password)
- [ ] Validasi form berfungsi (email format, password match, dll)
- [ ] Button "Register" berfungsi
- [ ] Redirect ke dashboard setelah registrasi berhasil
- [ ] Error message tampil jika ada kesalahan

### 2.2 Login User
- [ ] Form login tampil (email, password)
- [ ] Button "Login" berfungsi
- [ ] Redirect ke dashboard user setelah login berhasil
- [ ] Error message tampil jika kredensial salah
- [ ] Link "Forgot Password" berfungsi (jika ada)

### 2.3 Login Admin
- [ ] Admin bisa login dengan kredensial admin
- [ ] Redirect ke admin dashboard setelah login
- [ ] Middleware admin berfungsi (user biasa tidak bisa akses admin)

---

## 📋 BAGIAN 3: PENGUJIAN FITUR USER (Setelah Login)

### 3.1 Dashboard User
- [ ] Dashboard tampil setelah login
- [ ] Informasi user tampil (nama, email)
- [ ] Menu navigasi user tampil (Profile, Orders, Cart, dll)

### 3.2 Browse & View Products
- [ ] Halaman produk (`/products`) tampil semua produk
- [ ] Filter kategori berfungsi (jika ada)
- [ ] Search produk berfungsi (jika ada)
- [ ] Klik produk → detail produk tampil (`/products/{id}`)
- [ ] Gambar produk tampil dengan benar
- [ ] Informasi produk lengkap (nama, harga, deskripsi, stok)

### 3.3 Shopping Cart
- [ ] Button "Tambah ke Keranjang" berfungsi
- [ ] Button "Beli Sekarang" berfungsi
- [ ] Halaman cart (`/cart`) tampil item yang ditambahkan
- [ ] Update quantity di cart berfungsi (+/-)
- [ ] Hapus item dari cart berfungsi
- [ ] Total harga dihitung dengan benar
- [ ] Button "Checkout" berfungsi

### 3.4 Checkout & Order
- [ ] Halaman checkout (`/checkout`) tampil
- [ ] Form alamat pengiriman berfungsi
- [ ] Pilih metode pembayaran berfungsi
- [ ] Review order sebelum submit
- [ ] Button "Buat Pesanan" berfungsi
- [ ] Order berhasil dibuat dan tersimpan di database
- [ ] Redirect ke halaman payment setelah order dibuat
- [ ] Stok produk berkurang setelah order dibuat

### 3.5 Payment
- [ ] Halaman payment (`/payments/{order}`) tampil
- [ ] Informasi order dan total pembayaran tampil
- [ ] Metode pembayaran tampil (Midtrans/Transfer Bank)
- [ ] Button "Bayar Sekarang" berfungsi (Midtrans)
- [ ] Upload bukti pembayaran berfungsi (Transfer Bank)
- [ ] Status pembayaran update setelah upload bukti
- [ ] Redirect setelah pembayaran berhasil

### 3.6 Order History
- [ ] Halaman order history (`/orders`) tampil
- [ ] Semua order user tampil dengan status yang benar
- [ ] Filter order by status berfungsi (jika ada)
- [ ] Klik order → detail order tampil (`/orders/{id}`)
- [ ] Button "Batalkan Pesanan" berfungsi (untuk status pending/paid)
- [ ] Stok produk kembali setelah order dibatalkan
- [ ] Button "Hapus Pesanan" berfungsi (untuk status cancelled/done)
- [ ] Konfirmasi delete tampil sebelum hapus

### 3.7 User Profile
- [ ] Halaman profile (`/profile`) tampil
- [ ] Form edit profile tampil (name, email, phone, address, avatar)
- [ ] Upload avatar berfungsi
- [ ] Update profile berfungsi
- [ ] Update password berfungsi
- [ ] Validasi form berfungsi
- [ ] Success message tampil setelah update

### 3.8 Testimonials
- [ ] Halaman testimonials (`/testimonials`) tampil
- [ ] Semua testimonial tampil dengan rating bintang
- [ ] Form tambah testimonial berfungsi (untuk user yang sudah order)
- [ ] Rating bintang bisa dipilih (1-5)
- [ ] Button "Submit Testimonial" berfungsi
- [ ] Testimonial baru tampil setelah submit

### 3.9 Logout User
- [ ] Button "Logout" berfungsi
- [ ] Session user dihapus
- [ ] Redirect ke halaman login

---

## 📋 BAGIAN 4: PENGUJIAN FITUR ADMIN

### 4.1 Admin Dashboard
- [ ] Dashboard admin (`/admin/dashboard`) tampil
- [ ] Statistik tampil (total orders, revenue, products, users)
- [ ] Chart/grafik tampil dengan benar
- [ ] Data real-time dari database

### 4.2 Products Management (CRUD)
- [ ] Halaman list products (`/admin/products`) tampil
- [ ] Button "Tambah Produk" berfungsi
- [ ] Form create product tampil lengkap
- [ ] Upload gambar produk berfungsi
- [ ] Button "Simpan" berfungsi → produk baru tersimpan
- [ ] Button "Edit" berfungsi → form edit tampil
- [ ] Update produk berfungsi
- [ ] Button "Hapus" berfungsi
- [ ] Konfirmasi delete tampil
- [ ] Produk terhapus dari database
- [ ] Pagination berfungsi (jika ada banyak produk)
- [ ] Search produk berfungsi (jika ada)

### 4.3 Categories Management (CRUD)
- [ ] Halaman list categories (`/admin/categories`) tampil
- [ ] Button "Tambah Kategori" berfungsi
- [ ] Form create category tampil
- [ ] Button "Simpan" berfungsi → kategori baru tersimpan
- [ ] Button "Edit" berfungsi
- [ ] Update kategori berfungsi
- [ ] Button "Hapus" berfungsi
- [ ] Kategori terhapus dari database

### 4.4 Orders Management
- [ ] Halaman list orders (`/admin/orders`) tampil
- [ ] Semua order dari semua user tampil
- [ ] Filter by status berfungsi
- [ ] Filter by date berfungsi
- [ ] Klik order → detail order tampil
- [ ] Update status order berfungsi (pending → paid → processing → shipped → delivered)
- [ ] Button "Update Status" berfungsi
- [ ] Email notifikasi terkirim saat status berubah (jika ada)

### 4.5 Payments Management
- [ ] Admin bisa lihat semua payment
- [ ] Bukti pembayaran tampil (jika user upload)
- [ ] Button "Konfirmasi Pembayaran" berfungsi
- [ ] Button "Tolak Pembayaran" berfungsi
- [ ] Status payment update setelah konfirmasi/tolak
- [ ] Order status update setelah payment dikonfirmasi

### 4.6 Banners Management (CRUD)
- [ ] Halaman list banners (`/admin/banners`) tampil
- [ ] Button "Tambah Banner" berfungsi
- [ ] Upload gambar banner berfungsi
- [ ] Button "Simpan" berfungsi
- [ ] Button "Edit" berfungsi
- [ ] Button "Hapus" berfungsi
- [ ] Banner tampil di landing page

### 4.7 Promos Management (CRUD)
- [ ] Halaman list promos (`/admin/promos`) tampil
- [ ] Button "Tambah Promo" berfungsi
- [ ] Form create promo tampil
- [ ] Button "Simpan" berfungsi
- [ ] Button "Edit" berfungsi
- [ ] Button "Hapus" berfungsi

### 4.8 Contents Management (CRUD)
- [ ] Halaman list contents (`/admin/contents`) tampil
- [ ] Button "Tambah Content" berfungsi
- [ ] Form create content tampil (title, body, dll)
- [ ] Button "Simpan" berfungsi
- [ ] Button "Edit" berfungsi
- [ ] Button "Hapus" berfungsi

### 4.9 Testimonials Management
- [ ] Halaman list testimonials (`/admin/testimonials`) tampil
- [ ] Semua testimonial dari user tampil
- [ ] Button "Hapus" berfungsi (untuk hapus testimonial tidak pantas)
- [ ] Admin TIDAK bisa tambah/edit testimonial (hanya user)

### 4.10 Reports & Export
- [ ] Button "Export Orders" berfungsi
- [ ] File Excel/PDF orders ter-download
- [ ] Button "Export Products" berfungsi
- [ ] File Excel/PDF products ter-download
- [ ] Data export sesuai dengan filter yang dipilih

### 4.11 Admin Profile
- [ ] Halaman admin profile (`/admin/profile`) tampil
- [ ] Form edit profile admin tampil
- [ ] Update profile admin berfungsi
- [ ] Update password admin berfungsi

### 4.12 Logout Admin
- [ ] Button "Logout" berfungsi
- [ ] Session admin dihapus
- [ ] Redirect ke halaman login

---

## 📋 BAGIAN 5: PENGUJIAN KEAMANAN & MIDDLEWARE

### 5.1 Authentication Middleware
- [ ] Guest tidak bisa akses halaman yang butuh login
- [ ] Redirect ke login jika akses halaman protected
- [ ] User yang sudah login bisa akses halaman user

### 5.2 Admin Middleware
- [ ] User biasa tidak bisa akses halaman admin
- [ ] Redirect atau error 403 jika user biasa coba akses admin
- [ ] Admin bisa akses semua halaman admin

### 5.3 CSRF Protection
- [ ] Semua form punya CSRF token
- [ ] Form tidak bisa disubmit tanpa CSRF token

---

## 📋 BAGIAN 6: PENGUJIAN RESPONSIVENESS

### 6.1 Mobile View
- [ ] Navbar mobile berfungsi
- [ ] Hamburger menu berfungsi
- [ ] Layout responsive di mobile (< 768px)
- [ ] Semua button bisa diklik di mobile
- [ ] Form input bisa diisi di mobile

### 6.2 Tablet View
- [ ] Layout responsive di tablet (768px - 1024px)
- [ ] Navigasi berfungsi dengan baik

### 6.3 Desktop View
- [ ] Layout tampil sempurna di desktop (> 1024px)
- [ ] Semua fitur berfungsi dengan baik

---

## 📋 BAGIAN 7: PENGUJIAN ERROR HANDLING

### 7.1 Validation Errors
- [ ] Error message tampil jika form tidak valid
- [ ] Error message jelas dan informatif
- [ ] Old input tetap ada setelah error

### 7.2 404 Not Found
- [ ] Halaman 404 custom tampil untuk route tidak ada
- [ ] Link kembali ke home berfungsi

### 7.3 500 Server Error
- [ ] Error handling untuk server error
- [ ] Log error tersimpan

---

## 📊 HASIL PENGUJIAN

### ✅ Fitur yang Berfungsi Normal
(Akan diisi setelah pengujian)

### ❌ Bug/Error yang Ditemukan
(Akan diisi setelah pengujian)

### 🔧 Rekomendasi Perbaikan
(Akan diisi setelah pengujian)

---

## 📝 Catatan Tambahan
- Testing dilakukan pada environment: Local Development (Laravel Artisan Serve)
- Database: MySQL
- Browser: (Akan diisi)
- OS: Windows
