<!DOCTYPE html>
<html lang="id" style="margin: 0; padding: 0; width: 100%; overflow-x: hidden;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- SEO Meta Tags -->
    <title>@yield('title', 'Seblak UMI - Pedas Mantap!')</title>
    <meta name="description" content="@yield('description', 'Seblak premium autentik & pedas dari Seblak UMI! Rasakan sensasi pedas yang bikin nagih dengan bahan premium.')">
    <meta name="keywords" content="seblak, seblak umi, seblak pedas, kuliner indonesia, makanan pedas, seblak jakarta, seblak autentik">
    <meta name="author" content="Seblak UMI">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:title" content="@yield('og_title', 'Seblak UMI - Pedas Mantap!')">
    <meta property="og:description" content="@yield('og_description', 'Seblak premium autentik & pedas dari Seblak UMI! Rasakan sensasi pedas yang bikin nagih.')">
    <meta property="og:image" content="@yield('og_image', asset('images/seblak-umi-og.jpg'))">
    <meta property="og:site_name" content="Seblak UMI">

    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ url()->current() }}">
    <meta property="twitter:title" content="@yield('twitter_title', 'Seblak UMI - Pedas Mantap!')">
    <meta property="twitter:description" content="@yield('twitter_description', 'Seblak premium autentik & pedas dari Seblak UMI! Rasakan sensasi pedas yang bikin nagih.')">
    <meta property="twitter:image" content="@yield('twitter_image', asset('images/seblak-umi-twitter.jpg'))">

    <!-- Performance & Mobile -->
    <meta name="theme-color" content="#ea580c">
    <meta name="msapplication-navbutton-color" content="#ea580c">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-title" content="Seblak UMI">

    <!-- Preconnect for performance -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="dns-prefetch" href="//fonts.bunny.net">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">

    {{-- Panggil Tailwind & JS lewat Vite --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles

    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('custom/css/custom-auth-enhanced.css') }}">
    <link rel="stylesheet" href="{{ asset('css/additional-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('css/hero-styles.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navbar-animations.css') }}">


    <!-- Structured Data - Organization -->
    @verbatim
<script type="application/ld+json">
{
    "@context": "https://schema.org",
    "@type": "Organization",
    "name": "Seblak UMI",
    "url": "{{ url('/') }}",
    "logo": "{{ asset('images/logoseblak.jpeg') }}",
    "description": "Seblak premium autentik & pedas dari Seblak UMI! Rasakan sensasi pedas yang bikin nagih dengan bahan premium.",
    "address": {
        "@type": "PostalAddress",
        "addressLocality": "Jakarta",
        "addressCountry": "ID"
    },
    "contactPoint": {
        "@type": "ContactPoint",
        "telephone": "+62-812-3456-7890",
        "contactType": "customer service"
    },
    "sameAs": [
        "https://facebook.com/seblakumi",
        "https://instagram.com/seblakumi",
        "https://twitter.com/seblakumi"
    ]
}
</script>
@endverbatim

</head>

<body class="antialiased text-gray-900" style="margin: 0; padding: 0; width: 100%; overflow-x: hidden;">
    <!-- Background wrapper -->
    <div class="fixed inset-0 bg-gradient-to-b from-orange-50 to-yellow-100 -z-10"></div>

    {{-- Notifikasi sukses --}}
    @if (session('success'))
        <div class="fixed top-4 right-4 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in">
            {{ session('success') }}
        </div>
    @endif

    {{-- Notifikasi error --}}
    @if ($errors->any())
        <div class="fixed top-4 right-4 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg z-50 animate-slide-in">
              Login failed! Please check your credentials.
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    {{-- Konten utama (slot Blade) --}}
    {{ $slot }}

    {{-- Animasi notifikasi --}}
    <style>
        @keyframes slide-in {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-slide-in {
            animation: slide-in 0.4s ease-out;
        }
    </style>

    {{-- Auto hilang notifikasi --}}
    <script>
        setTimeout(() => {
            document.querySelectorAll('.animate-slide-in').forEach(el => {
                el.style.transition = 'opacity 0.5s';
                el.style.opacity = '0';
                setTimeout(() => el.remove(), 500);
            });
        }, 3000);
    </script>

    @livewireScripts
</body>
</html>
