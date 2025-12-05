# 🚨 QUICK FIX - EDIT .ENV MANUAL

## ❌ **MASALAH:**

Saya tidak bisa edit file `.env` karena di-gitignore.

## ✅ **SOLUSI MUDAH (2 MENIT):**

### **Buka file `.env` di editor Anda**

Cari line ini:
```env
BROADCAST_CONNECTION=log
```

**Ganti jadi:**
```env
BROADCAST_CONNECTION=reverb
```

### **Tambahkan di paling bawah file `.env`:**

```env
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

### **Save file `.env`**

---

## 🚀 **SETELAH SAVE:**

Beritahu saya:
```
".env sudah di-update"
```

Lalu saya akan:
1. ✅ Start Reverb server
2. ✅ Create Event
3. ✅ Update OrderController
4. ✅ Fix Admin Navigation UI
5. ✅ Add real-time listener
6. ✅ Test!

---

**COPY-PASTE INI KE `.env`:**

```
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

Dan ganti:
```
BROADCAST_CONNECTION=log
```
Jadi:
```
BROADCAST_CONNECTION=reverb
```

**DONE!** ✅
