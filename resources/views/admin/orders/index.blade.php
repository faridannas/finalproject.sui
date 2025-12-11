<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Toolbar Section (Compact Mobile & Unified Desktop) -->
            <div class="bg-white p-4 rounded-xl shadow-sm border border-gray-100 mb-6">
                <!-- Desktop Layout (Hidden on Mobile) -->
                <div class="hidden lg:flex flex-wrap items-center gap-3">
                    <!-- Search Input -->
                    <div class="relative flex-grow min-w-[200px]">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" id="search-input-desktop" placeholder="Search orders..." value="{{ request('search') }}"
                            class="pl-10 w-full rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">
                    </div>

                    <!-- Status Filter -->
                    <select id="status-filter-desktop" class="w-40 rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">
                        <option value="">Semua Status</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                        <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                        <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                    </select>

                    <!-- Date Filter (Single) -->
                    <input type="date" id="date-filter-desktop" value="{{ request('date') }}" 
                        class="rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">

                    <!-- Search Button -->
                    <button id="search-btn-desktop" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium shadow-sm">
                        Search
                    </button>

                    <div class="h-8 w-px bg-gray-200 mx-1"></div>

                    <!-- Export Button -->
                    <button id="export-orders-btn-desktop" class="bg-white text-green-600 border border-green-200 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors flex items-center justify-center text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export PDF
                    </button>
                </div>

                <!-- Mobile Layout (Visible only on Mobile/Tablet) -->
                <div class="lg:hidden flex flex-col gap-3">
                    <!-- Row 1: Search & Action -->
                    <div class="flex gap-2">
                        <div class="relative flex-grow">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search-input-mobile" placeholder="Search..." value="{{ request('search') }}"
                                class="pl-10 w-full rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">
                        </div>
                        <button id="search-btn-mobile" class="bg-orange-600 text-white px-4 py-2 rounded-lg hover:bg-orange-700 transition-colors text-sm font-medium shadow-sm">
                            Go
                        </button>
                    </div>

                    <!-- Row 2: Filters (Grid 2 Cols) -->
                    <div class="grid grid-cols-2 gap-2">
                        <select id="status-filter-mobile" class="w-full rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">
                            <option value="">Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Menunggu</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Dibayar</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Dikirim</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                        <input type="date" id="date-filter-mobile" value="{{ request('date') }}"
                            class="w-full rounded-lg border-2 border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-500 text-sm py-2.5">
                    </div>

                    <!-- Row 3: Export Button -->
                    <button id="export-orders-btn-mobile" class="w-full bg-white text-green-600 border border-green-200 px-4 py-2 rounded-lg hover:bg-green-50 transition-colors flex items-center justify-center text-sm font-medium shadow-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Export Orders PDF
                    </button>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-xl border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 border border-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">ID Pesanan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">Pelanggan</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">Tanggal</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">Total</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">Status</th>
                                <th class="px-6 py-4 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider border-b-2 border-gray-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($orders as $order)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        #{{ $order->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $order->user->email }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        {{ $order->created_at->format('d M Y') }}
                                        <div class="text-xs text-gray-400">{{ $order->created_at->format('H:i') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                            @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                            @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                            @elseif($order->status === 'processing') bg-indigo-100 text-indigo-800
                                            @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                            @elseif($order->status === 'completed') bg-green-100 text-green-800
                                            @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            @if($order->status === 'pending') Menunggu
                                            @elseif($order->status === 'paid') Dibayar
                                            @elseif($order->status === 'processing') Diproses
                                            @elseif($order->status === 'shipped') Dikirim
                                            @elseif($order->status === 'completed') Selesai
                                            @elseif($order->status === 'completed') Selesai
                                            @elseif($order->status === 'cancelled') Dibatalkan
                                            @else {{ ucfirst($order->status) }}
                                            @endif
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <div class="flex justify-end space-x-2">
                                            <a href="{{ route('admin.orders.show', $order) }}" class="text-gray-600 hover:text-gray-900 bg-gray-100 hover:bg-gray-200 px-3 py-1 rounded-md transition-colors text-xs">Lihat</a>
                                            
                                            @if($order->status === 'pending')
                                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="paid">
                                                    <button type="submit" class="text-blue-600 hover:text-blue-900 bg-blue-50 hover:bg-blue-100 px-3 py-1 rounded-md transition-colors text-xs">Tandai Dibayar</button>
                                                </form>
                                            @elseif($order->status === 'paid')
                                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="shipped">
                                                    <button type="submit" class="text-purple-600 hover:text-purple-900 bg-purple-50 hover:bg-purple-100 px-3 py-1 rounded-md transition-colors text-xs">Kirim</button>
                                                </form>
                                            @elseif($order->status === 'shipped')
                                                <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="status" value="completed">
                                                    <button type="submit" class="text-green-600 hover:text-green-900 bg-green-50 hover:bg-green-100 px-3 py-1 rounded-md transition-colors text-xs">Selesaikan</button>
                                                </form>
                                            @endif
                                            
                                            {{-- Delete Button --}}
                                            <form method="POST" action="{{ route('admin.orders.destroy', $order) }}" class="inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pesanan ini? Tindakan ini tidak dapat dibatalkan.');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 bg-red-50 hover:bg-red-100 px-3 py-1 rounded-md transition-colors text-xs">Hapus</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                            </svg>
                                            <p class="text-gray-500 text-lg font-medium">Tidak ada pesanan ditemukan</p>
                                            <p class="text-gray-400 text-sm">Coba sesuaikan filter Anda</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        function performSearch(isMobile) {
            const searchInputId = isMobile ? 'search-input-mobile' : 'search-input-desktop';
            const statusFilterId = isMobile ? 'status-filter-mobile' : 'status-filter-desktop';
            const dateFilterId = isMobile ? 'date-filter-mobile' : 'date-filter-desktop';
            
            const search = document.getElementById(searchInputId).value;
            const status = document.getElementById(statusFilterId).value;
            const date = document.getElementById(dateFilterId).value;
            
            let url = '{{ route("admin.orders.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (status) url += 'status=' + status + '&';
            if (date) url += 'date=' + date;
            window.location.href = url;
        }

        function performExport(isMobile) {
            const statusFilterId = isMobile ? 'status-filter-mobile' : 'status-filter-desktop';
            const dateFilterId = isMobile ? 'date-filter-mobile' : 'date-filter-desktop';

            const status = document.getElementById(statusFilterId).value;
            const date = document.getElementById(dateFilterId).value;

            let url = '{{ route("admin.reports.orders.export") }}?';
            if (status) url += 'status=' + status + '&';
            if (date) url += 'date=' + date;

            window.location.href = url;
        }

        // Desktop Events
        document.getElementById('search-btn-desktop')?.addEventListener('click', () => performSearch(false));
        document.getElementById('status-filter-desktop')?.addEventListener('change', () => performSearch(false));
        document.getElementById('date-filter-desktop')?.addEventListener('change', () => performSearch(false));
        document.getElementById('export-orders-btn-desktop')?.addEventListener('click', (e) => {
            e.preventDefault();
            performExport(false);
        });

        // Mobile Events
        document.getElementById('search-btn-mobile')?.addEventListener('click', () => performSearch(true));
        document.getElementById('status-filter-mobile')?.addEventListener('change', () => performSearch(true));
        document.getElementById('date-filter-mobile')?.addEventListener('change', () => performSearch(true));
        document.getElementById('export-orders-btn-mobile')?.addEventListener('click', (e) => {
            e.preventDefault();
            performExport(true);
        });

        // Enter key support
        document.getElementById('search-input-desktop')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') performSearch(false);
        });
        document.getElementById('search-input-mobile')?.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') performSearch(true);
        });
    </script>
</x-admin-layout>
