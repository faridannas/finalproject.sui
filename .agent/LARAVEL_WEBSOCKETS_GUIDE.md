# 🔌 LARAVEL WEBSOCKETS - IMPLEMENTATION GUIDE

## 📋 **Overview**

Menggunakan Laravel WebSockets (self-hosted) sebagai alternatif Pusher untuk real-time notifications.

---

## ✅ **Advantages**

- ✅ **No External Service** - Tidak perlu Pusher account
- ✅ **Unlimited Connections** - Tidak ada limit
- ✅ **Free Forever** - Gratis selamanya
- ✅ **Full Control** - Server sendiri
- ✅ **Laravel Native** - Terintegrasi dengan Laravel

---

## 📦 **Installation**

### **1. Install Package**
```bash
composer require beyondcode/laravel-websockets
```

### **2. Publish Config**
```bash
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="migrations"
php artisan migrate
php artisan vendor:publish --provider="BeyondCode\LaravelWebSockets\WebSocketsServiceProvider" --tag="config"
```

---

## ⚙️ **Configuration**

### **`.env` Configuration:**

```env
BROADCAST_DRIVER=pusher

PUSHER_APP_ID=seblak-umi-ai
PUSHER_APP_KEY=seblakumikey
PUSHER_APP_SECRET=seblakumisecret
PUSHER_HOST=127.0.0.1
PUSHER_PORT=6001
PUSHER_SCHEME=http
PUSHER_APP_CLUSTER=mt1

VITE_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
VITE_PUSHER_HOST="${PUSHER_HOST}"
VITE_PUSHER_PORT="${PUSHER_PORT}"
VITE_PUSHER_SCHEME="${PUSHER_SCHEME}"
VITE_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"
```

### **`config/broadcasting.php`:**

Update pusher connection:
```php
'pusher' => [
    'driver' => 'pusher',
    'key' => env('PUSHER_APP_KEY'),
    'secret' => env('PUSHER_APP_SECRET'),
    'app_id' => env('PUSHER_APP_ID'),
    'options' => [
        'cluster' => env('PUSHER_APP_CLUSTER'),
        'host' => env('PUSHER_HOST', '127.0.0.1'),
        'port' => env('PUSHER_PORT', 6001),
        'scheme' => env('PUSHER_SCHEME', 'http'),
        'encrypted' => true,
        'useTLS' => env('PUSHER_SCHEME') === 'https',
    ],
],
```

### **`config/websockets.php`:**

Update apps configuration:
```php
'apps' => [
    [
        'id' => env('PUSHER_APP_ID'),
        'name' => env('APP_NAME'),
        'key' => env('PUSHER_APP_KEY'),
        'secret' => env('PUSHER_APP_SECRET'),
        'path' => env('PUSHER_APP_PATH'),
        'capacity' => null,
        'enable_client_messages' => false,
        'enable_statistics' => true,
    ],
],
```

---

## 🔧 **Laravel Echo Configuration**

### **`resources/js/bootstrap.js`:**

```javascript
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    wsHost: import.meta.env.VITE_PUSHER_HOST ?? '127.0.0.1',
    wsPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    wssPort: import.meta.env.VITE_PUSHER_PORT ?? 6001,
    forceTLS: false,
    encrypted: true,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
});
```

---

## 🚀 **Running WebSocket Server**

### **Start WebSocket Server:**

```bash
php artisan websockets:serve
```

Server will run on: `http://127.0.0.1:6001`

### **For Production (with Supervisor):**

Create supervisor config: `/etc/supervisor/conf.d/websockets.conf`

```ini
[program:websockets]
command=php /path/to/artisan websockets:serve
numprocs=1
autostart=true
autorestart=true
user=www-data
```

Then:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start websockets
```

---

## 📊 **WebSocket Dashboard**

Access dashboard at: `http://127.0.0.1:8000/laravel-websockets`

Features:
- ✅ Real-time connection monitoring
- ✅ Message statistics
- ✅ Debug console
- ✅ Connection testing

---

## 🧪 **Testing**

### **Terminal Setup:**

```bash
# Terminal 1: Laravel Server
php artisan serve

# Terminal 2: WebSocket Server
php artisan websockets:serve

# Terminal 3: Vite (for assets)
npm run dev
```

### **Test Real-Time:**

1. **Browser 1:** Login as Admin
   - Open: http://127.0.0.1:8000/admin/dashboard
   - Open console (F12)
   - Should see: "WebSocket connected"

2. **Browser 2:** Login as User
   - Add products to cart
   - Checkout

3. **Browser 1:** Should receive notification!

---

## 🔍 **Debugging**

### **Check WebSocket Connection:**

Open browser console (F12):
```javascript
// Should see connection
Echo.connector.pusher.connection.state
// Should return: "connected"
```

### **Test Broadcasting:**

```bash
php artisan tinker
```

```php
broadcast(new App\Events\OrderCreated($order));
```

### **Common Issues:**

**1. Connection Failed:**
- Check WebSocket server running: `php artisan websockets:serve`
- Check port 6001 not blocked
- Check `.env` configuration

**2. Event Not Received:**
- Check BroadcastServiceProvider uncommented
- Check event implements `ShouldBroadcast`
- Check channel name matches

**3. CORS Issues:**
- Update `config/websockets.php` allowed origins
- Add your domain to allowed origins

---

## 📝 **Files to Create/Update**

### **1. Event: OrderCreated**
```bash
php artisan make:event OrderCreated
```

### **2. Update OrderController**
Add event broadcast after order creation

### **3. Update Admin Navigation**
Add notification dropdown with real-time listener

### **4. Update bootstrap.js**
Configure Laravel Echo

---

## 🎯 **Workflow**

```
User Checkout
    ↓
OrderController creates order
    ↓
Broadcast OrderCreated event
    ↓
WebSocket Server receives event
    ↓
Push to admin-notifications channel
    ↓
Admin browser receives via Echo
    ↓
Show notification + sound + update UI
```

---

## 📊 **Performance**

- **Connections:** Unlimited (limited by server resources)
- **Messages:** Unlimited
- **Latency:** < 100ms (local network)
- **CPU Usage:** Low (~5% per 100 connections)
- **Memory:** ~50MB base + ~1MB per 100 connections

---

## 🔒 **Security**

### **Production Recommendations:**

1. **Use SSL/TLS:**
   ```env
   PUSHER_SCHEME=https
   PUSHER_PORT=6001
   ```

2. **Restrict Dashboard:**
   ```php
   // routes/web.php
   WebSockets::routes(['middleware' => ['auth', 'admin']]);
   ```

3. **Enable Authentication:**
   ```php
   // config/websockets.php
   'statistics' => [
       'enabled' => true,
       'middleware' => ['auth'],
   ],
   ```

---

## ✅ **Checklist**

Before going live:

- [ ] WebSocket server running
- [ ] `.env` configured correctly
- [ ] BroadcastServiceProvider uncommented
- [ ] Laravel Echo configured
- [ ] Event created and broadcasting
- [ ] Admin navigation updated
- [ ] Tested real-time notifications
- [ ] Dashboard accessible
- [ ] Production server configured (if deploying)

---

## 🎊 **Summary**

**What You Get:**
- ✅ Self-hosted WebSocket server
- ✅ Real-time notifications
- ✅ No external dependencies
- ✅ Unlimited connections
- ✅ Full control
- ✅ Free forever

**Requirements:**
- 3 terminals running (Laravel, WebSocket, Vite)
- Port 6001 available
- Modern browser with WebSocket support

---

**Status:** 🚧 IN PROGRESS  
**Last Updated:** 2025-11-26 13:29
