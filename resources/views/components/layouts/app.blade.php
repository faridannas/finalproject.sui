<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ $title ?? 'Seblak Umi AI' }}</title>

        <!-- SEO Meta Tags -->
        <meta name="description" content="{{ $description ?? 'Nikmati sensasi seblak prasmanan dengan resep autentik dan bahan-bahan premium di Seblak UMI.' }}">

        <!-- Open Graph Meta Tags -->
        <meta property="og:title" content="{{ $ogTitle ?? $title ?? 'Seblak Umi AI' }}">
        <meta property="og:description" content="{{ $ogDescription ?? $description ?? 'Nikmati sensasi seblak prasmanan dengan resep autentik dan bahan-bahan premium di Seblak UMI.' }}">
        <meta property="og:image" content="{{ $ogImage ?? asset('images/seblak-umi-og.jpg') }}">
        <meta property="og:type" content="website">

        <!-- Twitter Card Meta Tags -->
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $twitterTitle ?? $ogTitle ?? $title ?? 'Seblak Umi AI' }}">
        <meta name="twitter:description" content="{{ $twitterDescription ?? $ogDescription ?? $description ?? 'Nikmati sensasi seblak prasmanan dengan resep autentik dan bahan-bahan premium di Seblak UMI.' }}">
        <meta name="twitter:image" content="{{ $twitterImage ?? $ogImage ?? asset('images/seblak-umi-og.jpg') }}">

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Custom CSS --}}
        <link rel="stylesheet" href="{{ asset('custom/css/custom-auth.css') }}">
        <link rel="stylesheet" href="{{ asset('css/additional-styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
        <link rel="stylesheet" href="{{ asset('css/hero-styles.css') }}">
        <link rel="stylesheet" href="{{ asset('css/auth-styles.css') }}">

        @livewireStyles
    </head>
    <body class="min-h-screen bg-gray-50 font-sans antialiased">
        {{-- Header / Navbar --}}
        <x-navbar />

        <main>
            {{ $slot }}
        </main>

        {{-- Footer --}}
        <x-footer />

        @livewireScripts
    </body>
</html>