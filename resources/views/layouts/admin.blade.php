<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.admin-navigation')

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            {{ $slot }}
        </main>
    </div>

    <!-- Toast Notifications -->
    @if (session('success'))
        <div id="toast-success" class="fixed bottom-8 right-8 bg-green-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3" style="animation: slideUp 0.3s ease-out;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    @if (session('error'))
        <div id="toast-error" class="fixed bottom-8 right-8 bg-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-3" style="animation: slideUp 0.3s ease-out;">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span class="font-medium">{{ session('error') }}</span>
        </div>
    @endif

    <!-- Auto-hide toast notifications -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toasts = document.querySelectorAll('[id^="toast-"]');
            toasts.forEach(toast => {
                setTimeout(() => {
                    toast.style.opacity = '0';
                    toast.style.transform = 'translateY(100%)';
                    toast.style.transition = 'all 0.3s ease-out';
                    setTimeout(() => toast.remove(), 300);
                }, 5000);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3.0.0/dist/chartjs-adapter-date-fns.bundle.min.js"></script>
    @livewireScripts

    <!-- Real-Time Notification Listener -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Echo !== 'undefined') {
            Echo.channel('admin-notifications')
                .listen('.order.created', (e) => {
                    // Show toast
                    const toast = document.createElement('div');
                    toast.className = 'fixed bottom-8 right-8 bg-gradient-to-r from-orange-500 to-red-500 text-white px-6 py-4 rounded-xl shadow-2xl z-50';
                    toast.innerHTML = `<p class="font-bold">Pesanan Baru dari ${e.user_name}!</p><p class="text-sm">Rp ${e.total_price.toLocaleString('id-ID')}</p>`;
                    document.body.appendChild(toast);
                    
                    // Reload after 2 seconds
                    setTimeout(() => window.location.reload(), 2000);
                });
        }
    });
</script>
</body>
</html>