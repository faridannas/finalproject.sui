<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Ubah Profil - Seblak Umi AI</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Shopee-style Header -->
    <div class="bg-orange-600 text-white sticky top-0 z-50 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="flex items-center">
                <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('dashboard') }}" class="mr-4 p-1 rounded-full hover:bg-orange-700 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                </a>
                <h1 class="text-lg font-medium">Ubah Profil</h1>
            </div>
            <div class="flex items-center space-x-3">
                <span class="text-sm font-semibold hidden sm:block">Seblak Umi AI</span>
                <img src="{{ asset('images/logoseblak.jpeg') }}" alt="Logo" class="h-8 w-8 rounded-full border-2 border-white object-cover">
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto pb-20">
        <!-- Success Message -->
        @if(session('success'))
            <div class="mx-4 mt-4 p-4 bg-green-100 border border-green-200 text-green-700 rounded-lg flex items-center shadow-sm">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('success') }}
            </div>
        @endif

        <!-- Profile Picture Section -->
        <div class="bg-gradient-to-b from-orange-600 to-orange-500 pb-10 pt-6 px-4 text-center text-white rounded-b-3xl shadow-sm mb-6">
            <div class="relative inline-block">
                <div class="h-24 w-24 rounded-full bg-white p-1 mx-auto shadow-lg">
                    @if (Auth::user()->avatar)
                        <img src="{{ Storage::url(Auth::user()->avatar) }}" alt="{{ Auth::user()->name }}" class="h-full w-full rounded-full object-cover">
                    @else
                        <div class="h-full w-full rounded-full bg-orange-100 flex items-center justify-center text-orange-600 text-3xl font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
                <div class="mt-3">
                    <h2 class="text-xl font-bold">{{ Auth::user()->name }}</h2>
                    <p class="text-orange-100 text-sm">{{ Auth::user()->email }}</p>
                </div>
            </div>
        </div>

        <!-- Forms Container -->
        <div class="px-4 space-y-6 -mt-8">
            <!-- Update Profile Info Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-gray-800 font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    Data Diri
                </h3>
                
                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Avatar Upload (Hidden but functional) -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Ganti Foto Profil</label>
                        <input type="file" name="avatar" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                        @error('avatar') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5">
                        @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input type="email" name="email" value="{{ old('email', $user->email) }}" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5">
                        @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                        <input type="text" name="phone" value="{{ old('phone', $user->phone) }}" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5" 
                               placeholder="Contoh: 08123456789">
                        @error('phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <!-- Address -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat</label>
                        <textarea name="address" rows="3" 
                                  style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                                  class="w-full rounded-xl px-4 py-2.5" 
                                  placeholder="Alamat lengkap pengiriman">{{ old('address', $user->address) }}</textarea>
                        @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-orange-600 text-white py-2 rounded-lg font-semibold hover:bg-orange-700 transition-colors shadow-md">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Update Password Form -->
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-gray-800 font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                    Ubah Password
                </h3>

                <form method="POST" action="{{ route('profile.password.update') }}" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                        <input type="password" name="current_password" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5">
                        @error('current_password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                        <input type="password" name="password" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5">
                        @error('password') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password Baru</label>
                        <input type="password" name="password_confirmation" 
                               style="border: 2px solid #d1d5db !important; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;" 
                               class="w-full rounded-xl px-4 py-2.5">
                    </div>

                    <div class="pt-2">
                        <button type="submit" class="w-full bg-gray-800 text-white py-2 rounded-lg font-semibold hover:bg-gray-900 transition-colors shadow-md">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- Logout Section --}}
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
                <h3 class="text-gray-800 font-semibold mb-4 flex items-center">
                    <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                    Keluar Akun
                </h3>
                <p class="text-sm text-gray-600 mb-4">Keluar dari akun Anda dan kembali ke halaman login.</p>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg font-semibold hover:bg-red-700 transition-colors shadow-md flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Logout
                    </button>
                </form>
            </div>
        </div>
        
        <div class="mt-8 text-center text-gray-400 text-xs pb-8">
            &copy; {{ date('Y') }} Seblak Umi AI. All rights reserved.
        </div>
    </div>
</body>
</html>
