# 🎯 Quick Testing Guide - Seblak Umi AI

## ⚡ Cara Cepat Test Hamburger Menu

### 1. Buka Browser
```
http://127.0.0.1:8000
```

### 2. Test di Mobile View
- Tekan `F12` (Developer Tools)
- Tekan `Ctrl + Shift + M` (Toggle Device Toolbar)
- Pilih "iPhone SE" atau set custom 375x667

### 3. Klik Hamburger Icon (☰)
**Yang Harus Terjadi:**
- ✅ Menu slide down smooth
- ✅ Icon berubah jadi X
- ✅ Tampil: Home, Products, Reviews, Login/Register
- ✅ Background body tidak bisa di-scroll

### 4. Tutup Menu
**3 Cara:**
- Klik icon X
- Klik di luar menu
- Tekan ESC

### 5. Test di Semua Halaman
- `/` (Landing)
- `/products` (Products)
- `/categories` (Categories)
- `/testimonials` (Testimonials)

---

## ⚡ Cara Cepat Test Product Hover

### 1. Buka Landing Page
```
http://127.0.0.1:8000
```

### 2. Scroll ke "Menu Favorit Pelanggan"

### 3. Hover Mouse pada Product Card

**Yang Harus Terjadi:**
- ✅ Gambar zoom in (smooth)
- ✅ Teks judul berubah **ORANGE**
- ✅ Shadow card lebih dalam
- ✅ Semua transisi smooth

---

## 🔍 Troubleshooting

### Hamburger Menu Tidak Berfungsi?

**Check 1: JavaScript Loaded?**
```
F12 → Console → Cek error
F12 → Network → Cari navbar-optimized.js (harus status 200)
```

**Check 2: Clear Cache**
```
Ctrl + Shift + Delete → Clear cache
Atau
Ctrl + F5 (Hard refresh)
```

**Check 3: Verify Script**
```
View Page Source → Cari:
<script src="http://127.0.0.1:8000/js/navbar-optimized.js" defer></script>
```

### Product Hover Tidak Berubah Orange?

**Check 1: Tailwind Compiled?**
```
Terminal → Check npm run dev masih running
```

**Check 2: Clear Browser Cache**
```
Ctrl + Shift + Delete
```

**Check 3: Inspect Element**
```
Right click pada judul → Inspect
Cek class: "group-hover:text-orange-600"
```

---

## ✅ Quick Checklist

### Mobile (375x667)
- [ ] Hamburger icon terlihat
- [ ] Klik hamburger → menu muncul
- [ ] Menu smooth animation
- [ ] Klik X → menu hilang
- [ ] Klik outside → menu hilang
- [ ] ESC → menu hilang

### Desktop (1920x1080)
- [ ] Hamburger icon TIDAK terlihat
- [ ] Desktop nav terlihat
- [ ] Product hover → teks orange
- [ ] Product hover → gambar zoom
- [ ] Smooth transitions

---

## 🎨 Expected Behavior

### Hamburger Menu Animation:
```
Closed → Click → Open (300ms slide down)
Open → Click → Close (300ms slide up)
```

### Product Card Hover:
```
Normal → Hover → Orange text (300ms)
Normal → Hover → Image scale 1.1 (700ms)
```

---

## 📱 Test Devices

### Recommended:
- iPhone SE (375x667)
- iPhone 12 Pro (390x844)
- iPad (768x1024)
- Desktop (1920x1080)

### Quick Switch:
```
F12 → Device Toolbar → Dropdown pilih device
```

---

## 🚨 Common Issues

### Issue: Menu tidak muncul
**Solution:** Hard refresh (Ctrl + F5)

### Issue: Teks tidak berubah orange
**Solution:** Clear cache + restart npm run dev

### Issue: Animation patah-patah
**Solution:** Check GPU acceleration di browser

---

## 💡 Tips

1. **Always test di Incognito** untuk avoid cache issues
2. **Check console** untuk JavaScript errors
3. **Test di multiple browsers** (Chrome, Firefox, Edge)
4. **Test di real device** jika memungkinkan

---

**Happy Testing! 🎉**
