# ✅ LARAVEL REVERB - SETUP COMPLETE!

## 🎉 **INSTALLATION SUCCESSFUL!**

Laravel Reverb sudah terinstall di Laravel 12! Sekarang tinggal konfigurasi.

---

## 📋 **MANUAL SETUP STEPS**

### **STEP 1: Update `.env`**

Buka file `.env` dan **tambahkan/update** lines berikut:

```env
BROADCAST_CONNECTION=reverb

REVERB_APP_ID=seblak-umi-ai
REVERB_APP_KEY=seblakumikey
REVERB_APP_SECRET=seblakumisecret
REVERB_HOST=localhost
REVERB_PORT=8080
REVERB_SCHEME=http

VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
VITE_REVERB_HOST="${REVERB_HOST}"
VITE_REVERB_PORT="${REVERB_PORT}"
VITE_REVERB_SCHEME="${REVERB_SCHEME}"
```

**Cari line ini:**
```env
BROADCAST_CONNECTION=log
```

**Ganti jadi:**
```env
BROADCAST_CONNECTION=reverb
```

---

### **STEP 2: Run Reverb Server**

Buka **Terminal baru** (jangan tutup yang lain), jalankan:

```bash
php artisan reverb:start
```

Server akan jalan di: `http://localhost:8080`

**Output yang benar:**
```
  INFO  Starting server on 0.0.0.0:8080.  

  ┌ Application ─────────────────────────┐
  │ ID: seblak-umi-ai                    │
  │ Key: seblakumikey                    │
  │ Secret: seblakumisecret              │
  └──────────────────────────────────────┘

  Server running...
```

---

### **STEP 3: Update `bootstrap.js`**

File `resources/js/bootstrap.js` sudah otomatis di-update oleh installer.

**Verify isinya ada ini:**
```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
});
```

---

### **STEP 4: Restart Vite**

Karena `.env` berubah, restart Vite:

**Terminal npm run dev:**
1. Tekan `Ctrl+C` untuk stop
2. Jalankan lagi: `npm run dev`

---

## 🔧 **NEXT: CREATE EVENT & LISTENER**

Sekarang saya akan create:

1. **Event `OrderCreated`** - Broadcast ketika ada order baru
2. **Update `OrderController`** - Trigger event saat checkout
3. **Update Admin Navigation** - UI notifikasi yang responsive
4. **Add JavaScript Listener** - Listen untuk notifikasi real-time

---

## 🧪 **TESTING SETUP**

### **Cek Reverb Running:**

Setelah jalankan `php artisan reverb:start`, buka browser:

**http://localhost:8080**

Kalau muncul error atau blank page = **NORMAL** ✅  
(Reverb WebSocket server, bukan web page)

### **Cek Connection di Browser:**

1. Login sebagai admin
2. Buka console (F12)
3. Lihat network tab
4. Harusnya ada connection ke `ws://localhost:8080`

---

## 📊 **TERMINAL SETUP**

Untuk development, Anda perlu **3 terminals running**:

```
Terminal 1: php artisan serve          (Port 8000)
Terminal 2: php artisan reverb:start   (Port 8080)
Terminal 3: npm run dev                (Vite)
```

**Jangan tutup terminal-terminal ini!**

---

## ✅ **CHECKLIST**

Sebelum lanjut ke coding:

- [ ] `.env` sudah di-update dengan config Reverb
- [ ] `BROADCAST_CONNECTION=reverb` (bukan `log`)
- [ ] `php artisan reverb:start` running di terminal
- [ ] `npm run dev` sudah di-restart
- [ ] `php artisan serve` masih running

---

## 🚀 **READY?**

Setelah semua checklist ✅, beritahu saya:

**"Reverb sudah running"**

Lalu saya akan:
1. Create Event `OrderCreated`
2. Update `OrderController`
3. Fix Admin Navigation UI (responsive)
4. Add real-time listener
5. Test notifikasi!

---

**Status:** ⏳ WAITING FOR MANUAL SETUP  
**Next:** Create Event & Implement Real-Time Notifications
