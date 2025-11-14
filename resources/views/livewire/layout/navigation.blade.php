u003cnav class="bg-seblak-700 shadow-xl"u003e
    u003cdiv class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8"u003e
        u003cdiv class="flex justify-between h-16 items-center"u003e
            u003cdiv class="flex items-center"u003e
                u003c!-- Logo --u003e
                u003cdiv class="flex-shrink-0"u003e
                    u003cx-application-logo class="block h-10 w-auto text-white" /u003e
                u003c/divu003e
                u003c!-- Navigation Links --u003e
                u003cdiv class="hidden sm:ml-6 sm:flex sm:space-x-8"u003e
                    u003cx-nav-link :href="route('dashboard')" :active="request()-\u003erouteIs('dashboard')" class="text-white hover:bg-seblak-600 px-3 py-2 rounded-md"u003e
                        {{ __('dashboard') }}
                    u003c/x-nav-linku003e
                u003c/divu003e
            u003c/divu003e
        u003c/divu003e
    u003c/divu003e
u003c/navu003e