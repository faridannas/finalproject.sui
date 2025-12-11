# 🧪 Manual Testing Guide - Seblak Umi AI

## 📋 Panduan Testing Manual untuk User & Admin

**Server**: http://127.0.0.1:8000  
**Tanggal**: 9 Desember 2025

---

## 🚀 PERSIAPAN TESTING

### 1. Pastikan Server Berjalan
```bash
php artisan serve
```
Server akan berjalan di: http://127.0.0.1:8000

### 2. Buka Browser
- Chrome, Firefox, atau Edge
- Buka Developer Tools (F12) untuk melihat console errors

### 3. Siapkan Data Testing
- Email user test: user@test.com
- Email admin test: admin@test.com
- Password: (sesuai database Anda)

---

## 📝 TESTING CHECKLIST - USER SIDE

### ✅ BAGIAN 1: Landing Page (Guest)

**URL**: http://127.0.0.1:8000

#### Test Steps:
1. [ ] Buka landing page
2. [ ] Cek navbar tampil dengan benar
3. [ ] Klik logo → refresh page
4. [ ] Klik "Produk" → redirect ke /products
5. [ ] Klik "Kategori" → redirect ke /categories
6. [ ] Klik "Testimoni" → redirect ke /testimonials
7. [ ] Klik "Login" → redirect ke /login
8. [ ] Klik "Register" → redirect ke /register
9. [ ] Scroll ke bawah, cek footer tampil
10. [ ] Test mobile menu (resize browser < 768px)

**Expected Result**: ✅ Semua link berfungsi, tidak ada error 404

---

### ✅ BAGIAN 2: Registrasi & Login

#### A. Test Registrasi
**URL**: http://127.0.0.1:8000/register

1. [ ] Buka halaman register
2. [ ] Isi form:
   - Name: Test User
   - Email: testuser@example.com
   - Password: password123
   - Confirm Password: password123
3. [ ] Klik "Register"
4. [ ] Cek redirect ke dashboard
5. [ ] Cek user muncul di navbar

**Expected Result**: ✅ User berhasil terdaftar dan login otomatis

#### B. Test Login
**URL**: http://127.0.0.1:8000/login

1. [ ] Logout dulu (jika sudah login)
2. [ ] Buka halaman login
3. [ ] Isi email dan password
4. [ ] Klik "Login"
5. [ ] Cek redirect ke dashboard

**Expected Result**: ✅ Login berhasil, redirect ke dashboard

#### C. Test Login Error
1. [ ] Login dengan password salah
2. [ ] Cek error message muncul

**Expected Result**: ✅ Error message: "These credentials do not match our records"

---

### ✅ BAGIAN 3: Browse Products

#### A. Products Index
**URL**: http://127.0.0.1:8000/products

1. [ ] Buka halaman products
2. [ ] Cek semua produk tampil
3. [ ] Cek gambar produk loading
4. [ ] Cek harga tampil dengan benar
5. [ ] Test search (jika ada)
6. [ ] Test filter kategori (jika ada)
7. [ ] Klik salah satu produk

**Expected Result**: ✅ Produk tampil, gambar loading, klik berfungsi

#### B. Product Detail
**URL**: http://127.0.0.1:8000/products/{id}

1. [ ] Buka detail produk
2. [ ] Cek gambar produk tampil
3. [ ] Cek nama, harga, deskripsi, stok tampil
4. [ ] Cek button "Tambah ke Keranjang" ada
5. [ ] Cek button "Beli Sekarang" ada

**Expected Result**: ✅ Detail lengkap tampil

---

### ✅ BAGIAN 4: Shopping Cart

#### A. Add to Cart
**Di halaman product detail**:

1. [ ] Pilih quantity (misal: 2)
2. [ ] Klik "Tambah ke Keranjang"
3. [ ] Cek success message muncul
4. [ ] Cek redirect ke /cart
5. [ ] Cek produk muncul di cart
6. [ ] Cek quantity dan total harga benar

**Expected Result**: ✅ Produk masuk cart, kalkulasi benar

#### B. Update Cart
**Di halaman cart**:

1. [ ] Klik tombol + untuk tambah quantity
2. [ ] Cek total harga update otomatis
3. [ ] Klik tombol - untuk kurangi quantity
4. [ ] Cek total harga update otomatis
5. [ ] Hapus item dari cart (jika ada button hapus)

**Expected Result**: ✅ Quantity update, harga recalculate

#### C. Buy Now
**Di halaman product detail**:

1. [ ] Pilih quantity
2. [ ] Klik "Beli Sekarang"
3. [ ] Cek redirect langsung ke checkout
4. [ ] Cek cart hanya berisi produk yang di-buy now

**Expected Result**: ✅ Langsung ke checkout, cart cleared

---

### ✅ BAGIAN 5: Checkout & Order

#### A. Checkout Page
**URL**: http://127.0.0.1:8000/checkout

1. [ ] Buka halaman checkout
2. [ ] Cek cart items tampil
3. [ ] Cek subtotal, discount (jika ada), total
4. [ ] Isi alamat pengiriman
5. [ ] Pilih metode pembayaran
6. [ ] Test apply promo code (jika ada)
7. [ ] Klik "Buat Pesanan"

**Expected Result**: ✅ Order berhasil dibuat

#### B. Validation Testing
1. [ ] Coba checkout dengan cart kosong
2. [ ] Coba checkout tanpa isi alamat
3. [ ] Coba checkout tanpa pilih payment method

**Expected Result**: ✅ Validation error muncul

---

### ✅ BAGIAN 6: Payment

#### A. Payment Page
**URL**: http://127.0.0.1:8000/payments/{order_id}

1. [ ] Setelah create order, redirect ke payment page
2. [ ] Cek order details tampil
3. [ ] Cek total pembayaran benar
4. [ ] Cek metode pembayaran tampil

**For Transfer Bank**:
1. [ ] Upload bukti pembayaran (gambar)
2. [ ] Isi nama bank (optional)
3. [ ] Isi nama rekening (optional)
4. [ ] Klik "Upload Bukti Pembayaran"
5. [ ] Cek success message
6. [ ] Cek redirect ke order detail

**Expected Result**: ✅ Upload berhasil, status pending

**For Midtrans** (jika aktif):
1. [ ] Klik "Bayar Sekarang"
2. [ ] Cek redirect ke Midtrans
3. [ ] Test payment flow

---

### ✅ BAGIAN 7: Order History

#### A. View Orders
**URL**: http://127.0.0.1:8000/orders

1. [ ] Buka halaman orders
2. [ ] Cek semua order user tampil
3. [ ] Cek status order tampil (pending, paid, dll)
4. [ ] Klik salah satu order

**Expected Result**: ✅ Order list tampil

#### B. Order Detail
**URL**: http://127.0.0.1:8000/orders/{id}

1. [ ] Buka detail order
2. [ ] Cek order items tampil
3. [ ] Cek total harga benar
4. [ ] Cek status order
5. [ ] Cek payment info

**Expected Result**: ✅ Detail lengkap tampil

#### C. Cancel Order
**Di halaman order detail** (status: pending atau paid):

1. [ ] Klik button "Batalkan Pesanan"
2. [ ] Cek konfirmasi muncul
3. [ ] Konfirmasi cancel
4. [ ] Cek success message
5. [ ] Cek status berubah jadi "cancelled"
6. [ ] Cek stok produk kembali (buka product detail)

**Expected Result**: ✅ Order cancelled, stock restored

#### D. Delete Order
**Di halaman orders** (status: cancelled atau done):

1. [ ] Klik button "Hapus Pesanan"
2. [ ] Cek konfirmasi muncul
3. [ ] Konfirmasi delete
4. [ ] Cek order hilang dari list

**Expected Result**: ✅ Order terhapus

---

### ✅ BAGIAN 8: User Profile

#### A. View Profile
**URL**: http://127.0.0.1:8000/profile

1. [ ] Buka halaman profile
2. [ ] Cek data user tampil (name, email)
3. [ ] Cek form edit profile

**Expected Result**: ✅ Profile tampil

#### B. Update Profile
1. [ ] Edit nama
2. [ ] Edit phone
3. [ ] Edit address
4. [ ] Upload avatar (gambar)
5. [ ] Klik "Update Profile"
6. [ ] Cek success message
7. [ ] Cek data terupdate

**Expected Result**: ✅ Profile updated

#### C. Change Password
1. [ ] Isi current password
2. [ ] Isi new password
3. [ ] Isi confirm password
4. [ ] Klik "Change Password"
5. [ ] Cek success message
6. [ ] Logout dan login dengan password baru

**Expected Result**: ✅ Password berhasil diubah

---

### ✅ BAGIAN 9: Testimonials

#### A. View Testimonials
**URL**: http://127.0.0.1:8000/testimonials

1. [ ] Buka halaman testimonials
2. [ ] Cek semua testimonial tampil
3. [ ] Cek rating bintang tampil
4. [ ] Cek nama user dan komentar

**Expected Result**: ✅ Testimonials tampil

#### B. Submit Testimonial
**Prerequisite**: User sudah pernah order

1. [ ] Scroll ke form testimonial
2. [ ] Pilih rating (1-5 bintang)
3. [ ] Tulis komentar
4. [ ] Klik "Submit"
5. [ ] Cek success message
6. [ ] Cek testimonial baru muncul

**Expected Result**: ✅ Testimonial submitted

---

### ✅ BAGIAN 10: Logout

1. [ ] Klik dropdown profile di navbar
2. [ ] Klik "Logout"
3. [ ] Cek redirect ke login page
4. [ ] Coba akses /dashboard (should redirect to login)

**Expected Result**: ✅ Logout berhasil, session cleared

---

## 🔐 TESTING CHECKLIST - ADMIN SIDE

### ✅ BAGIAN 1: Admin Login

**URL**: http://127.0.0.1:8000/login

1. [ ] Login dengan akun admin
2. [ ] Cek redirect ke /admin/dashboard
3. [ ] Cek admin navbar tampil

**Expected Result**: ✅ Admin login berhasil

---

### ✅ BAGIAN 2: Admin Dashboard

**URL**: http://127.0.0.1:8000/admin/dashboard

1. [ ] Buka admin dashboard
2. [ ] Cek statistik tampil:
   - Total Orders
   - Total Revenue
   - Total Products
   - Total Users
3. [ ] Cek chart/grafik tampil
4. [ ] Cek data real-time

**Expected Result**: ✅ Dashboard tampil dengan data

---

### ✅ BAGIAN 3: Products Management

#### A. View Products
**URL**: http://127.0.0.1:8000/admin/products

1. [ ] Buka halaman admin products
2. [ ] Cek semua produk tampil
3. [ ] Cek pagination (jika ada banyak produk)
4. [ ] Cek search berfungsi (jika ada)

**Expected Result**: ✅ Products list tampil

#### B. Create Product
1. [ ] Klik "Tambah Produk"
2. [ ] Isi form:
   - Nama produk
   - Kategori
   - Harga
   - Stok
   - Deskripsi
3. [ ] Upload gambar produk
4. [ ] Klik "Simpan"
5. [ ] Cek success message
6. [ ] Cek produk baru muncul di list
7. [ ] Cek produk tampil di public products page

**Expected Result**: ✅ Produk berhasil dibuat

#### C. Edit Product
1. [ ] Klik "Edit" pada salah satu produk
2. [ ] Ubah nama produk
3. [ ] Ubah harga
4. [ ] Upload gambar baru (optional)
5. [ ] Klik "Update"
6. [ ] Cek success message
7. [ ] Cek perubahan tersimpan

**Expected Result**: ✅ Produk berhasil diupdate

#### D. Delete Product
1. [ ] Klik "Delete" pada salah satu produk
2. [ ] Cek konfirmasi muncul
3. [ ] Konfirmasi delete
4. [ ] Cek success message
5. [ ] Cek produk hilang dari list

**Expected Result**: ✅ Produk terhapus

---

### ✅ BAGIAN 4: Categories Management

**URL**: http://127.0.0.1:8000/admin/categories

#### A. Create Category
1. [ ] Klik "Tambah Kategori"
2. [ ] Isi nama kategori
3. [ ] Isi deskripsi (optional)
4. [ ] Klik "Simpan"
5. [ ] Cek kategori baru muncul

**Expected Result**: ✅ Kategori berhasil dibuat

#### B. Edit Category
1. [ ] Klik "Edit"
2. [ ] Ubah nama
3. [ ] Klik "Update"
4. [ ] Cek perubahan tersimpan

**Expected Result**: ✅ Kategori berhasil diupdate

#### C. Delete Category
1. [ ] Klik "Delete"
2. [ ] Konfirmasi
3. [ ] Cek kategori terhapus

**Expected Result**: ✅ Kategori terhapus

---

### ✅ BAGIAN 5: Orders Management

**URL**: http://127.0.0.1:8000/admin/orders

#### A. View All Orders
1. [ ] Buka halaman admin orders
2. [ ] Cek SEMUA order dari SEMUA user tampil
3. [ ] Cek filter by status berfungsi
4. [ ] Cek filter by date berfungsi

**Expected Result**: ✅ Semua order tampil

#### B. View Order Detail
1. [ ] Klik salah satu order
2. [ ] Cek order items tampil
3. [ ] Cek customer info tampil
4. [ ] Cek payment info tampil
5. [ ] Cek bukti pembayaran tampil (jika ada)

**Expected Result**: ✅ Detail lengkap tampil

#### C. Update Order Status
1. [ ] Pilih status baru (misal: processing)
2. [ ] Klik "Update Status"
3. [ ] Cek success message
4. [ ] Cek status terupdate

**Expected Result**: ✅ Status berhasil diupdate

#### D. Quick Actions (di list orders)
1. [ ] Test "Mark Paid" button
2. [ ] Test "Ship" button
3. [ ] Test "Complete" button

**Expected Result**: ✅ Quick actions berfungsi

---

### ✅ BAGIAN 6: Payment Management

**Di halaman admin order detail**:

#### A. Confirm Payment
1. [ ] Cek bukti pembayaran yang diupload user
2. [ ] Klik "Konfirmasi Pembayaran"
3. [ ] Cek payment status jadi "success"
4. [ ] Cek order status jadi "paid"

**Expected Result**: ✅ Payment confirmed

#### B. Reject Payment
1. [ ] Klik "Tolak Pembayaran"
2. [ ] Konfirmasi
3. [ ] Cek payment status jadi "failed"

**Expected Result**: ✅ Payment rejected

---

### ✅ BAGIAN 7: Banners Management

**URL**: http://127.0.0.1:8000/admin/banners

#### A. Create Banner
1. [ ] Klik "Tambah Banner"
2. [ ] Isi title
3. [ ] Upload gambar banner
4. [ ] Isi link (optional)
5. [ ] Set active status
6. [ ] Klik "Simpan"
7. [ ] Cek banner tampil di landing page

**Expected Result**: ✅ Banner berhasil dibuat

#### B. Edit & Delete
1. [ ] Test edit banner
2. [ ] Test delete banner

**Expected Result**: ✅ Edit & delete berfungsi

---

### ✅ BAGIAN 8: Promos Management

**URL**: http://127.0.0.1:8000/admin/promos

#### A. Create Promo
1. [ ] Klik "Tambah Promo"
2. [ ] Isi promo code (misal: DISKON10)
3. [ ] Isi discount percentage (misal: 10)
4. [ ] Set valid until date
5. [ ] Klik "Simpan"

**Expected Result**: ✅ Promo berhasil dibuat

#### B. Test Promo
1. [ ] Logout dari admin
2. [ ] Login sebagai user
3. [ ] Add product to cart
4. [ ] Di checkout, masukkan promo code
5. [ ] Klik "Apply"
6. [ ] Cek discount teraplikasi

**Expected Result**: ✅ Promo code berfungsi

---

### ✅ BAGIAN 9: Contents Management

**URL**: http://127.0.0.1:8000/admin/contents

1. [ ] Test create content
2. [ ] Test edit content
3. [ ] Test delete content
4. [ ] Cek content tampil di public page

**Expected Result**: ✅ CRUD content berfungsi

---

### ✅ BAGIAN 10: Testimonials Management

**URL**: http://127.0.0.1:8000/admin/testimonials

1. [ ] Buka halaman admin testimonials
2. [ ] Cek SEMUA testimonial dari user tampil
3. [ ] Cek TIDAK ADA button "Tambah Testimonial"
4. [ ] Klik "Delete" pada testimonial tidak pantas
5. [ ] Konfirmasi delete
6. [ ] Cek testimonial terhapus

**Expected Result**: ✅ Admin bisa delete, tapi tidak bisa create/edit

---

### ✅ BAGIAN 11: Reports & Export

#### A. Export Orders
**URL**: http://127.0.0.1:8000/admin/orders

1. [ ] Set filter date range
2. [ ] Klik "Export Orders"
3. [ ] Cek file Excel/PDF ter-download
4. [ ] Buka file, cek data sesuai filter

**Expected Result**: ✅ Export berhasil

#### B. Export Products
1. [ ] Klik "Export Products"
2. [ ] Cek file ter-download
3. [ ] Buka file, cek data produk lengkap

**Expected Result**: ✅ Export berhasil

---

### ✅ BAGIAN 12: Admin Profile

**URL**: http://127.0.0.1:8000/admin/profile

1. [ ] Test update admin profile
2. [ ] Test change admin password
3. [ ] Test update settings (jika ada)

**Expected Result**: ✅ Admin profile management berfungsi

---

## 🔒 SECURITY TESTING

### ✅ Test Authentication
1. [ ] Logout, coba akses /dashboard → should redirect to login
2. [ ] Logout, coba akses /cart → should redirect to login
3. [ ] Logout, coba akses /admin/dashboard → should redirect to login

**Expected Result**: ✅ Protected routes tidak bisa diakses tanpa login

### ✅ Test Authorization
1. [ ] Login sebagai USER biasa
2. [ ] Coba akses /admin/dashboard → should get 403 or redirect
3. [ ] Coba akses /admin/products → should get 403 or redirect

**Expected Result**: ✅ User biasa tidak bisa akses admin routes

### ✅ Test CSRF
1. [ ] Buka Developer Tools → Console
2. [ ] Submit form tanpa CSRF token → should fail

**Expected Result**: ✅ CSRF protection aktif

---

## 📱 RESPONSIVE TESTING

### ✅ Mobile View (< 768px)
1. [ ] Resize browser ke mobile size
2. [ ] Test hamburger menu berfungsi
3. [ ] Test semua button bisa diklik
4. [ ] Test form bisa diisi
5. [ ] Test checkout flow di mobile

**Expected Result**: ✅ Responsive di mobile

### ✅ Tablet View (768px - 1024px)
1. [ ] Resize browser ke tablet size
2. [ ] Test layout responsive
3. [ ] Test navigation berfungsi

**Expected Result**: ✅ Responsive di tablet

---

## ⚠️ ERROR TESTING

### ✅ Test Validation Errors
1. [ ] Submit form kosong → error message muncul
2. [ ] Upload file > 2MB → error message muncul
3. [ ] Upload file bukan gambar → error message muncul
4. [ ] Add to cart dengan quantity > stock → error message muncul

**Expected Result**: ✅ Validation errors tampil dengan jelas

### ✅ Test 404 Page
1. [ ] Akses URL yang tidak ada (misal: /asdfasdf)
2. [ ] Cek halaman 404 custom tampil
3. [ ] Cek link kembali ke home berfungsi

**Expected Result**: ✅ 404 page tampil

---

## 📊 HASIL TESTING

### Template Laporan:

```
TESTING COMPLETED: [Tanggal]
TESTER: [Nama]

✅ PASSED: [Jumlah]
❌ FAILED: [Jumlah]
⚠️ WARNINGS: [Jumlah]

BUGS FOUND:
1. [Deskripsi bug]
2. [Deskripsi bug]

RECOMMENDATIONS:
1. [Rekomendasi]
2. [Rekomendasi]
```

---

## 💡 TIPS TESTING

1. **Gunakan Incognito Mode** untuk test fresh session
2. **Clear Cache** jika ada perubahan CSS/JS
3. **Check Console** (F12) untuk JavaScript errors
4. **Check Network Tab** untuk failed requests
5. **Test dengan Data Real** (bukan dummy data)
6. **Test Edge Cases** (empty cart, out of stock, dll)
7. **Test Concurrent Actions** (buka 2 tab, test race conditions)

---

**Happy Testing! 🚀**
