<x-admin-layout>


    <div class="py-12 bg-gray-50/50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if (session('success'))
                <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm animate-fade-in-down" role="alert">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-green-700 font-medium">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Kolom Kiri: Profil & Password -->
                <div class="lg:col-span-1 space-y-8">
                    <!-- Kartu Profil -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden relative group hover:shadow-xl transition-all duration-300">
                        <div class="h-32 bg-gradient-to-r from-orange-400 to-red-500 relative">
                            <div class="absolute -bottom-12 left-1/2 transform -translate-x-1/2">
                                <div class="relative">
                                    @if($user->avatar)
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover border-4 border-white shadow-lg">
                                    @else
                                        <div class="w-24 h-24 rounded-full bg-white border-4 border-white shadow-lg flex items-center justify-center text-orange-500">
                                            <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                        </div>
                                    @endif
                                    <label for="avatar-upload" class="absolute bottom-0 right-0 bg-white rounded-full p-1.5 shadow-md cursor-pointer hover:bg-gray-50 transition-colors border border-gray-200" title="Ubah Foto">
                                        <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="pt-16 pb-8 px-6 text-center">
                            <h3 class="text-xl font-bold text-gray-900">{{ $user->name }}</h3>
                            <p class="text-sm text-gray-500">{{ $user->email }}</p>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 mt-2">
                                Administrator
                            </span>
                        </div>
                        
                        <div class="px-6 pb-6">
                            <form method="post" action="{{ route('admin.profile.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')
                                <input type="file" name="avatar" id="avatar-upload" class="hidden" onchange="this.form.submit()">
                                
                                <div class="space-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" 
                                               style="border: 2px solid #d1d5db !important;"
                                               class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 text-sm">
                                        <x-input-error class="mt-1" :messages="$errors->get('name')" />
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" 
                                               style="border: 2px solid #d1d5db !important;"
                                               class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 text-sm">
                                        <x-input-error class="mt-1" :messages="$errors->get('email')" />
                                    </div>
                                    <button type="submit" class="w-full bg-gray-900 hover:bg-gray-800 text-white font-medium py-2.5 px-4 rounded-xl transition-all duration-200 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                                        Simpan Perubahan
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Kartu Ganti Password -->
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300">
                        <div class="p-6 border-b border-gray-50 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                                <svg class="w-5 h-5 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Keamanan Akun
                            </h3>
                        </div>
                        <div class="p-6">
                            <form method="post" action="{{ route('admin.profile.password') }}" class="space-y-4">
                                @csrf
                                @method('put')
                                <div>
                                    <label for="current_password" class="block text-sm font-medium text-gray-700 mb-1">Password Saat Ini</label>
                                    <input type="password" name="current_password" id="current_password" 
                                           style="border: 2px solid #d1d5db !important;"
                                           class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 text-sm">
                                    <x-input-error :messages="$errors->get('current_password')" class="mt-1" />
                                </div>
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="password" id="password" 
                                           style="border: 2px solid #d1d5db !important;"
                                           class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 text-sm">
                                    <x-input-error :messages="$errors->get('password')" class="mt-1" />
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation" 
                                           style="border: 2px solid #d1d5db !important;"
                                           class="w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 px-4 py-2.5 text-sm">
                                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1" />
                                </div>
                                <button type="submit" class="w-full bg-white border-2 border-gray-200 hover:border-orange-500 hover:text-orange-600 text-gray-700 font-medium py-2.5 px-4 rounded-xl transition-all duration-200">
                                    Update Password
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Kolom Kanan: Pengaturan Website -->
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 h-full">
                        <div class="p-6 border-b border-gray-50 bg-gradient-to-r from-gray-50 to-white">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900 flex items-center gap-2">
                                        <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                        Pengaturan Website
                                    </h3>

                                </div>
                                <div class="hidden sm:block">
                                    <span class="inline-flex items-center justify-center p-3 bg-orange-50 rounded-xl text-orange-600">
                                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="p-8">
                            <form method="post" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
                                @csrf
                                @method('patch')

                                <div class="grid grid-cols-1 gap-8">
                                    <!-- Site Name Section -->
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <label for="site_name" class="block text-lg font-semibold text-gray-900 mb-2">Nama Website</label>

                                        <div class="relative">
                                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            </div>
                                            <input type="text" name="site_name" id="site_name" value="{{ old('site_name', $site_name) }}" 
                                                style="border: 2px solid #d1d5db !important;"
                                                class="pl-10 block w-full rounded-xl border-2 border-gray-300 shadow-sm focus:border-orange-500 focus:ring-2 focus:ring-orange-200 focus:ring-opacity-50 transition-colors duration-200 py-3 text-lg font-medium" 
                                                placeholder="Contoh: Seblak UMI AI">
                                        </div>
                                        <x-input-error class="mt-2" :messages="$errors->get('site_name')" />
                                    </div>

                                    <!-- Logo Section -->
                                    <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100">
                                        <label class="block text-lg font-semibold text-gray-900 mb-2">Logo Website</label>

                                        
                                        <div class="flex flex-col sm:flex-row items-center gap-6">
                                            <div class="flex-shrink-0">
                                                <div class="w-32 h-32 bg-white rounded-2xl border-2 border-dashed border-gray-300 flex items-center justify-center relative overflow-hidden group">
                                                    @if($site_logo)
                                                        <img src="{{ asset('storage/' . $site_logo) }}" alt="Site Logo" class="w-24 h-24 object-contain transition-transform duration-300 group-hover:scale-110">
                                                    @else
                                                        <div class="text-center p-4">
                                                            <svg class="mx-auto h-10 w-10 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1 w-full">
                                                <div class="flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-xl hover:border-orange-400 hover:bg-orange-50 transition-all cursor-pointer relative">
                                                    <div class="space-y-1 text-center">
                                                        <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                                            <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <div class="flex text-sm text-gray-600 justify-center">
                                                            <label for="site_logo" class="relative cursor-pointer bg-white rounded-md font-medium text-orange-600 hover:text-orange-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-orange-500">
                                                                <span>Upload file baru</span>
                                                                <input id="site_logo" name="site_logo" type="file" class="sr-only">
                                                            </label>
                                                            <p class="pl-1">atau drag and drop</p>
                                                        </div>
                                                        <p class="text-xs text-gray-500">PNG, JPG, GIF up to 2MB</p>
                                                    </div>
                                                </div>
                                                <x-input-error class="mt-2" :messages="$errors->get('site_logo')" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mt-8 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-xl shadow-sm text-white bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transform transition-all hover:scale-105">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        Simpan Pengaturan Website
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Fixed Back Button - Bottom Right -->
    <button onclick="window.history.back()" class="fixed bottom-8 right-8 inline-flex items-center px-4 py-3 bg-gray-800 hover:bg-gray-900 text-white rounded-full shadow-lg transition-all duration-200 hover:scale-110 z-50">
        <span class="font-semibold mr-2">Kembali</span>
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
        </svg>
    </button>
</x-admin-layout>
