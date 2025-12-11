<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- Total Orders Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-orange-50 text-orange-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Orders</p>
                        <h4 class="text-2xl font-bold text-gray-900" id="total-orders">{{ $totalOrders }}</h4>
                        <p class="text-xs text-orange-600 mt-1 font-medium">+{{ \App\Models\Order::where('created_at', '>=', now()->startOfWeek())->count() }} minggu ini</p>
                    </div>
                </div>

                <!-- Products Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-blue-50 text-blue-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Active Products</p>
                        <h4 class="text-2xl font-bold text-gray-900">{{ \App\Models\Product::count() }}</h4>
                        <p class="text-xs text-blue-600 mt-1 font-medium">{{ \App\Models\Product::where('stock', '>', 0)->count() }} ready stock</p>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 flex items-center hover:shadow-md transition-shadow">
                    <div class="p-3 rounded-full bg-emerald-50 text-emerald-600 mr-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Total Revenue</p>
                        <h4 class="text-2xl font-bold text-gray-900" id="total-revenue">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        <p class="text-xs text-emerald-600 mt-1 font-medium">+Rp {{ number_format(\App\Models\Order::where('status', 'completed')->where('created_at', '>=', now()->startOfWeek())->sum('total_price'), 0, ',', '.') }} minggu ini</p>
                    </div>
                </div>
            </div>
                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <!-- Sales Trend Chart -->
                    <div class="bg-white overflow-hidden shadow-lg sm:rounded-2xl border border-gray-100">
                        <div class="p-8">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-blue-600 uppercase tracking-wide">Tren Penjualan</h3>
                                <p class="text-sm text-gray-500 mt-2">Grafik penjualan 14 hari terakhir</p>
                            </div>
                            <div class="relative" style="height: 350px;">
                                <canvas id="orderChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Top Selling Products Chart -->
                    <div class="bg-gradient-to-br from-orange-50 to-white overflow-hidden shadow-lg sm:rounded-2xl border border-orange-100">
                        <div class="p-8">
                            <div class="text-center mb-6">
                                <h3 class="text-2xl font-bold text-orange-600 uppercase tracking-wide">Produk Paling Populer</h3>
                                <p class="text-sm text-gray-600 mt-2">Top 5 menu favorit pelanggan</p>
                            </div>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <!-- Chart -->
                                <div class="lg:col-span-2">
                                    <div class="relative" style="height: 350px;">
                                        <canvas id="topProductsChart"></canvas>
                                    </div>
                                </div>
                                
                                <!-- Circular Progress Indicators -->
                                <div class="flex flex-col justify-center space-y-6">
                                    @php
                                        $totalSold = array_sum($topProductQuantities->toArray());
                                        $topTwo = $topProductQuantities->take(2);
                                    @endphp
                                    
                                    @foreach($topTwo as $index => $quantity)
                                        @php
                                            $percentage = $totalSold > 0 ? round(($quantity / $totalSold) * 100) : 0;
                                            $productName = $topProductNames[$index] ?? 'Produk';
                                        @endphp
                                        <div class="text-center">
                                            <div class="relative inline-flex items-center justify-center">
                                                <svg class="transform -rotate-90 w-24 h-24">
                                                    <circle cx="48" cy="48" r="40" stroke="#E5E7EB" stroke-width="8" fill="none" />
                                                    <circle cx="48" cy="48" r="40" 
                                                        stroke="{{ $index === 0 ? '#F97316' : '#EC4899' }}" 
                                                        stroke-width="8" 
                                                        fill="none"
                                                        stroke-dasharray="{{ 2 * 3.14159 * 40 }}"
                                                        stroke-dashoffset="{{ 2 * 3.14159 * 40 * (1 - $percentage / 100) }}"
                                                        stroke-linecap="round" />
                                                </svg>
                                                <span class="absolute text-2xl font-bold {{ $index === 0 ? 'text-orange-600' : 'text-pink-600' }}">
                                                    {{ $percentage }}%
                                                </span>
                                            </div>
                                            <div class="mt-3">
                                                <p class="text-xs font-semibold text-gray-700">{{ $index === 0 ? 'Terlaris' : 'Populer' }}</p>
                                                <p class="text-xs text-gray-500 mt-1">{{ Str::limit($productName, 20) }}</p>
                                                <p class="text-xs text-gray-400">{{ $quantity }} porsi terjual</p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Orders Table -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Orders</h3>
                            <a href="{{ route('admin.orders.index') }}" class="text-orange-600 hover:text-orange-700 text-sm font-medium">
                                View All →
                            </a>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order ID</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="recent-orders">
                                    @foreach(App\Models\Order::with('user')->latest()->take(5)->get() as $order)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                #{{ $order->id }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->user->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($order->status === 'completed') bg-green-100 text-green-800
                                                    @elseif($order->status === 'pending') bg-yellow-100 text-yellow-800
                                                    @else bg-gray-100 text-gray-800 @endif">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $order->created_at->format('d/m/Y H:i') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Recent Testimonials -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                    <div class="p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Recent Testimonials</h3>
                            <a href="{{ route('admin.testimonials.index') }}" class="text-indigo-600 hover:text-indigo-700 text-sm font-medium">
                                View All →
                            </a>
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="recent-testimonials">
                            @foreach(\App\Models\Testimonial::with(['user', 'product'])->latest()->take(4)->get() as $testimonial)
                                <div class="flex space-x-4 p-4 hover:bg-gray-50 rounded-lg transition-colors border border-gray-100">
                                    <div class="flex-shrink-0">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white font-bold">
                                            {{ strtoupper(substr($testimonial->user->name ?? 'U', 0, 1)) }}
                                        </div>
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex justify-between items-start">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $testimonial->user->name ?? 'User Dihapus' }}</p>
                                            <span class="text-xs text-gray-500">{{ $testimonial->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-500 mb-1">on <span class="font-medium text-indigo-600">{{ $testimonial->product->name ?? 'Produk Dihapus' }}</span></p>
                                        <div class="flex items-center mb-1">
                                            @for($i = 1; $i <= 5; $i++)
                                                <svg class="h-4 w-4 {{ $i <= $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }} fill-current" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                            @endfor
                                        </div>
                                        <p class="text-sm text-gray-600 line-clamp-2">{{ $testimonial->comment }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>



            <script>
                // Wait for Chart.js to be fully loaded
                function initializeCharts() {
                    // Check if Chart.js is loaded
                    if (typeof Chart === 'undefined') {
                        console.log('Chart.js not loaded yet, retrying...');
                        setTimeout(initializeCharts, 100);
                        return;
                    }

                    console.log('Chart.js loaded, initializing charts...');
                    
                    // Common Chart Options
                    Chart.defaults.font.family = "'Figtree', sans-serif";
                    Chart.defaults.color = '#6B7280';
                    
                    // Order Trends Chart (Modern Line/Area Chart)
                    const ctx = document.getElementById('orderChart');
                    if (!ctx) {
                        console.error('orderChart canvas not found');
                        return;
                    }
                    
                    const orderChart = new Chart(ctx.getContext('2d'), {
                        type: 'line',
                        data: {
                            labels: @json($dates),
                            datasets: [
                                {
                                    label: 'Total Order',
                                    data: @json($orderCounts),
                                    borderColor: '#F97316', // Orange-500
                                    backgroundColor: (context) => {
                                        const ctx = context.chart.ctx;
                                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                        gradient.addColorStop(0, 'rgba(249, 115, 22, 0.2)');
                                        gradient.addColorStop(1, 'rgba(249, 115, 22, 0)');
                                        return gradient;
                                    },
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#FFFFFF',
                                    pointBorderColor: '#F97316',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Pendapatan (Rp)',
                                    data: @json($revenues),
                                    borderColor: '#10B981', // Emerald-500
                                    backgroundColor: (context) => {
                                        const ctx = context.chart.ctx;
                                        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                                        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.2)');
                                        gradient.addColorStop(1, 'rgba(16, 185, 129, 0)');
                                        return gradient;
                                    },
                                    borderWidth: 3,
                                    fill: true,
                                    tension: 0.4,
                                    pointBackgroundColor: '#FFFFFF',
                                    pointBorderColor: '#10B981',
                                    pointBorderWidth: 2,
                                    pointRadius: 4,
                                    pointHoverRadius: 6,
                                    yAxisID: 'y1'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                intersect: false,
                                mode: 'index',
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                    align: 'end',
                                    labels: {
                                        usePointStyle: true,
                                        boxWidth: 8,
                                        padding: 20,
                                        font: { size: 12 }
                                    }
                                },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.9)',
                                    titleColor: '#1F2937',
                                    bodyColor: '#4B5563',
                                    borderColor: '#E5E7EB',
                                    borderWidth: 1,
                                    padding: 10,
                                    displayColors: true,
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label) label += ': ';
                                            if (context.datasetIndex === 1) {
                                                label += new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(context.raw);
                                            } else {
                                                label += context.raw;
                                            }
                                            return label;
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: { maxTicksLimit: 7 }
                                },
                                y: {
                                    type: 'linear',
                                    display: true,
                                    position: 'left',
                                    grid: { borderDash: [4, 4], color: '#F3F4F6' },
                                    beginAtZero: true,
                                    title: { display: true, text: 'Jumlah Order' },
                                    ticks: {
                                        stepSize: 1,
                                        precision: 0,
                                        callback: function(value) {
                                            // Hanya tampilkan bilangan bulat
                                            if (Number.isInteger(value)) {
                                                return value;
                                            }
                                            return null;
                                        }
                                    }
                                },
                                y1: {
                                    type: 'linear',
                                    display: true,
                                    position: 'right',
                                    grid: { display: false },
                                    beginAtZero: true,
                                    ticks: {
                                        stepSize: 10000,
                                        callback: function(value) {
                                            // Format ke ribuan (K) untuk revenue
                                            if (value >= 1000) {
                                                return 'Rp ' + Math.floor(value/1000) + 'k';
                                            }
                                            return 'Rp ' + value;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Top Selling Products Chart (Vertical Bar - Easier to Understand)
                    const topCtx = document.getElementById('topProductsChart');
                    if (!topCtx) {
                        console.error('topProductsChart canvas not found');
                        return;
                    }
                    
                    const topProductsChart = new Chart(topCtx.getContext('2d'), {
                        type: 'bar',
                        data: {
                            labels: @json($topProductNames),
                            datasets: [{
                                label: 'Terjual (Porsi)',
                                data: @json($topProductQuantities),
                                backgroundColor: [
                                    'rgba(249, 115, 22, 0.85)',  // Orange
                                    'rgba(236, 72, 153, 0.85)',  // Pink
                                    'rgba(20, 184, 166, 0.85)',  // Teal
                                    'rgba(168, 85, 247, 0.85)',  // Purple
                                    'rgba(234, 179, 8, 0.85)',   // Yellow
                                ],
                                borderColor: [
                                    'rgb(249, 115, 22)',   // Orange
                                    'rgb(236, 72, 153)',   // Pink
                                    'rgb(20, 184, 166)',   // Teal
                                    'rgb(168, 85, 247)',   // Purple
                                    'rgb(234, 179, 8)',    // Yellow
                                ],
                                borderWidth: 2,
                                borderRadius: 8,
                                barThickness: 50,
                            }]
                        },
                        options: {
                            indexAxis: 'x', // Vertical bar (default)
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: 'rgba(255, 255, 255, 0.95)',
                                    titleColor: '#1F2937',
                                    bodyColor: '#4B5563',
                                    borderColor: '#F97316',
                                    borderWidth: 2,
                                    padding: 12,
                                    displayColors: false,
                                    callbacks: {
                                        title: function(context) {
                                            return context[0].label;
                                        },
                                        label: function(context) {
                                            return context.raw + ' Porsi Terjual';
                                        }
                                    }
                                }
                            },
                            scales: {
                                x: {
                                    grid: { display: false },
                                    ticks: {
                                        font: { size: 11, weight: '500' },
                                        color: '#6B7280'
                                    }
                                },
                                y: {
                                    grid: { 
                                        borderDash: [4, 4], 
                                        color: '#F3F4F6' 
                                    },
                                    beginAtZero: true,
                                    ticks: {
                                        font: { size: 11 },
                                        stepSize: 1,
                                        precision: 0,
                                        callback: function(value) {
                                            // Hanya tampilkan bilangan bulat
                                            if (Number.isInteger(value)) {
                                                return value + ' porsi';
                                            }
                                            return null;
                                        }
                                    }
                                }
                            }
                        }
                    });

                    // Function to update dashboard data
                    function updateDashboard() {
                        fetch('/admin/dashboard-data')
                            .then(response => response.json())
                            .then(data => {
                                // Update statistics
                                document.getElementById('total-orders').textContent = data.totalOrders;
                                document.getElementById('total-revenue').textContent = 'Rp ' + data.totalRevenue.toLocaleString('id-ID');

                                // Update Order Chart
                                orderChart.data.labels = data.dates;
                                orderChart.data.datasets[0].data = data.orderCounts;
                                orderChart.data.datasets[1].data = data.revenues;
                                orderChart.update();

                                // Update Top Products Chart
                                topProductsChart.data.labels = data.topProductNames;
                                topProductsChart.data.datasets[0].data = data.topProductQuantities;
                                topProductsChart.update();

                                // Update recent orders
                                const recentOrdersTable = document.getElementById('recent-orders');
                                if (recentOrdersTable) recentOrdersTable.innerHTML = data.recentOrdersHtml;

                                // Update recent testimonials
                                const recentTestimonialsContainer = document.getElementById('recent-testimonials');
                                if (recentTestimonialsContainer && data.recentTestimonialsHtml) {
                                    recentTestimonialsContainer.innerHTML = data.recentTestimonialsHtml;
                                }
                            })
                            .catch(error => console.error('Error updating dashboard:', error));
                    }

                    // Update dashboard every 30 seconds
                    setInterval(updateDashboard, 30000);
                    
                    console.log('Charts initialized successfully!');
                }

                // Start initialization when DOM is ready
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', initializeCharts);
                } else {
                    // DOM already loaded
                    initializeCharts();
                }
            </script>
        </div>
    </div>
</x-admin-layout>
