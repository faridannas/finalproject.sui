<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Orders') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Search and Filter -->
            <div class="mb-8">
                <div class="flex flex-col sm:flex-row gap-4 justify-between items-center">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <input type="text" id="search-input" placeholder="Search orders..." value="{{ request('search') }}"
                               class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                        <select id="status-filter" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                            <option value="">All Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Shipped</option>
                            <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                        <button id="search-btn" class="bg-orange-600 text-white px-4 py-2 rounded-md hover:bg-orange-700">
                            Search
                        </button>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="flex flex-col sm:flex-row gap-4">
                            <input type="date" id="start-date" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="Start Date">
                            <input type="date" id="end-date" class="rounded-md border-gray-300 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50" placeholder="End Date">
                        </div>
                        <a href="#" id="export-orders-btn" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                            Export Orders PDF
                        </a>
                    </div>
                </div>
            </div>

            <!-- Orders Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($orders as $order)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            #{{ $order->id }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $order->user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $order->user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $order->created_at->format('M d, Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                @if($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                @elseif($order->status === 'paid') bg-blue-100 text-blue-800
                                                @elseif($order->status === 'shipped') bg-purple-100 text-purple-800
                                                @elseif($order->status === 'done') bg-green-100 text-green-800
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($order->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex space-x-2">
                                                <a href="{{ route('admin.orders.show', $order) }}" class="text-blue-600 hover:text-blue-900">View</a>
                                                @if($order->status === 'pending')
                                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="paid">
                                                        <button type="submit" class="text-green-600 hover:text-green-900">Mark Paid</button>
                                                    </form>
                                                @elseif($order->status === 'paid')
                                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="shipped">
                                                        <button type="submit" class="text-purple-600 hover:text-purple-900">Ship</button>
                                                    </form>
                                                @elseif($order->status === 'shipped')
                                                    <form method="POST" action="{{ route('admin.orders.update', $order) }}" class="inline">
                                                        @csrf
                                                        @method('PUT')
                                                        <input type="hidden" name="status" value="done">
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900">Complete</button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            No orders found.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4">
                        {{ $orders->appends(request()->query())->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('search-btn').addEventListener('click', function() {
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;
            let url = '{{ route("admin.orders.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (status) url += 'status=' + status;
            window.location.href = url;
        });

        document.getElementById('status-filter').addEventListener('change', function() {
            const search = document.getElementById('search-input').value;
            const status = this.value;
            let url = '{{ route("admin.orders.index") }}?';
            if (search) url += 'search=' + encodeURIComponent(search) + '&';
            if (status) url += 'status=' + status;
            window.location.href = url;
        });

        document.getElementById('export-orders-btn').addEventListener('click', function(e) {
            e.preventDefault();
            const startDate = document.getElementById('start-date').value;
            const endDate = document.getElementById('end-date').value;
            const status = document.getElementById('status-filter').value;

            let url = '{{ route("admin.reports.orders.export") }}?';
            if (startDate) url += 'start_date=' + startDate + '&';
            if (endDate) url += 'end_date=' + endDate + '&';
            if (status) url += 'status=' + status;

            window.location.href = url;
        });
    </script>
</x-app-layout>
