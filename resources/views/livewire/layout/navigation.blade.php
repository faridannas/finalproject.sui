<nav class="bg-seblak-700 shadow-xl">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            <div class="flex items-center">
                <!-- Logo -->
                <div class="flex-shrink-0">
                    <x-application-logo class="block h-10 w-auto text-white" />
                </div>
                <!-- Navigation Links -->
                <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="text-white hover:bg-seblak-600 px-3 py-2 rounded-md">
                        {{ __('dashboard') }}
                    </x-nav-link>
                    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')" class="text-white hover:bg-seblak-600 px-3 py-2 rounded-md">
                        {{ __('Products') }}
                    </x-nav-link>
                    <x-nav-link :href="route('orders.index')" :active="request()->routeIs('orders.*')" class="text-white hover:bg-seblak-600 px-3 py-2 rounded-md">
                        {{ __('My Orders') }}
                    </x-nav-link>
                </div>
            </div>
        </div>
    </div>
</nav>
