-- ========================================
-- CEK DATA SEBELUM EXPORT
-- ========================================
-- Jalankan query ini di phpMyAdmin untuk memastikan data lengkap

-- 1. Cek jumlah produk
SELECT COUNT(*) as total_products FROM products;

-- 2. Cek semua produk dan gambarnya
SELECT id, name, image, price, stock FROM products ORDER BY id;

-- 3. Cek jumlah testimonial
SELECT COUNT(*) as total_testimonials FROM testimonials;

-- 4. Cek testimonial
SELECT id, user_id, rating, comment FROM testimonials ORDER BY id DESC LIMIT 10;

-- 5. Cek jumlah user
SELECT COUNT(*) as total_users FROM users;

-- 6. Cek user (tanpa password)
SELECT id, name, email, role, created_at FROM users ORDER BY id;

-- 7. Cek kategori
SELECT id, name, image FROM categories;

-- 8. Cek banner
SELECT id, title, image FROM banners;

-- 9. Cek orders
SELECT COUNT(*) as total_orders FROM orders;

-- 10. Cek storage files yang digunakan
SELECT 
    'products' as type,
    image as filename
FROM products
WHERE image IS NOT NULL
UNION ALL
SELECT 
    'categories' as type,
    image as filename
FROM categories
WHERE image IS NOT NULL
UNION ALL
SELECT 
    'banners' as type,
    image as filename
FROM banners
WHERE image IS NOT NULL
UNION ALL
SELECT 
    'avatars' as type,
    avatar as filename
FROM users
WHERE avatar IS NOT NULL;
