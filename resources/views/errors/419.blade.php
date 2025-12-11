<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Session Expired - Seblak UMI</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta http-equiv="refresh" content="2;url={{ route('welcome') }}">
</head>
<body class="bg-gradient-to-br from-slate-50 via-orange-50 to-red-50 min-h-screen flex items-center justify-center">
    <div class="text-center">
        <div class="mb-8">
            <svg class="mx-auto h-24 w-24 text-orange-500 animate-pulse" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
        <h1 class="text-4xl font-bold text-gray-900 mb-4">Session Expired</h1>
        <p class="text-lg text-gray-600 mb-8">Sesi Anda telah berakhir. Mengalihkan ke halaman login...</p>
        <div class="flex justify-center gap-4">
            <a href="{{ route('login') }}" class="px-6 py-3 bg-gradient-to-r from-orange-500 to-red-500 text-white font-semibold rounded-lg hover:from-orange-600 hover:to-red-600 transition-all duration-200 shadow-lg">
                Login Sekarang
            </a>
            <a href="{{ route('welcome') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition-all duration-200">
                Kembali ke Beranda
            </a>
        </div>
    </div>

    <script>
        // Auto redirect after 2 seconds
        setTimeout(function() {
            window.location.href = "{{ route('welcome') }}";
        }, 2000);
    </script>
</body>
</html>
