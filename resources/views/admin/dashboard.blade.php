<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} - Admin Dashboard</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Chart.js and plugins -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">
        @include('layouts.admin-navigation')

        <!-- Page Heading -->
        <header class="bg-white shadow">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    {{ __('Admin Dashboard') }}
                </h2>
            </div>
        </header>

        <!-- Page Content -->
        <main>
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Statistics Cards -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Overview Statistics</h3>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                    <!-- Orders Stats -->
                                    <div class="bg-gradient-to-br from-orange-500 to-orange-600 rounded-lg p-4 text-white">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm opacity-80">Total Orders</p>
                                                <h4 class="text-2xl font-bold">{{ App\Models\Order::count() }}</h4>
                                            </div>
                                            <div class="text-3xl">📦</div>
                                        </div>
                                        @php
                                            $orderGrowth = App\Models\Order::whereBetween('created_at', [now()->subWeek(), now()])->count();
                                        @endphp
                                        <p class="text-sm mt-2">
                                            <span class="opacity-80">This Week: </span>
                                            <span class="font-semibold">{{ $orderGrowth }}</span>
                                        </p>
                                    </div>

                                    <!-- Products Stats -->
                                    <div class="bg-gradient-to-br from-green-500 to-green-600 rounded-lg p-4 text-white">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm opacity-80">Products</p>
                                                <h4 class="text-2xl font-bold">{{ App\Models\Product::count() }}</h4>
                                            </div>
                                            <div class="text-3xl">🍜</div>
                                        </div>
                                        <p class="text-sm mt-2">
                                            <span class="opacity-80">Active: </span>
                                            <span class="font-semibold">{{ App\Models\Product::where('stock', '>', 0)->count() }}</span>
                                        </p>
                                    </div>

                                    <!-- Revenue Stats -->
                                    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-lg p-4 text-white">
                                        <div class="flex items-center justify-between">
                                            <div>
                                                <p class="text-sm opacity-80">Revenue</p>
                                                <h4 class="text-2xl font-bold">
                                                    {{ 'Rp ' . number_format(App\Models\Order::where('status', 'completed')->sum('total_price'), 0, ',', '.') }}
                                                </h4>
                                            </div>
                                            <div class="text-3xl">💰</div>
                                        </div>
                                        @php
                                            $weeklyRevenue = App\Models\Order::where('status', 'completed')
                                                ->whereBetween('created_at', [now()->subWeek(), now()])
                                                ->sum('total_price');
                                        @endphp
                                        <p class="text-sm mt-2">
                                            <span class="opacity-80">This Week: </span>
                                            <span class="font-semibold">{{ 'Rp ' . number_format($weeklyRevenue, 0, ',', '.') }}</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Line Chart -->
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg lg:col-span-2">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Orders and Revenue Trends</h3>
                                <div style="height: 400px;">
                                    <canvas id="orderChart" class="w-full h-full"></canvas>
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
                                        <tbody class="bg-white divide-y divide-gray-200">
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
                                                        {{ $order->created_at->format('d M Y H:i') }}
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    @php
                        $orderData = App\Models\Order::selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_price) as revenue')
                            ->where('created_at', '>=', now()->subDays(14))
                            ->groupBy('date')
                            ->orderBy('date')
                            ->get();

                        $dates = $orderData->pluck('date');
                        $counts = $orderData->pluck('count');
                        $revenues = $orderData->pluck('revenue');
                    @endphp

                    <script>
                        // Create the chart when the page loads
                        document.addEventListener('DOMContentLoaded', function() {
                            const ctx = document.getElementById('orderChart').getContext('2d');
                            new Chart(ctx, {
                                type: 'line',
                                data: {
                                    labels: @json($dates),
                                    datasets: [
                                        {
                                            label: 'Number of Orders',
                                            data: @json($counts),
                                            borderColor: 'rgba(249, 115, 22, 1)',
                                            backgroundColor: (context) => {
                                                const chart = context.chart;
                                                const {ctx, chartArea} = chart;
                                                if (!chartArea) return null;
                                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                                gradient.addColorStop(0, 'rgba(249, 115, 22, 0)');
                                                gradient.addColorStop(1, 'rgba(249, 115, 22, 0.2)');
                                                return gradient;
                                            },
                                            borderWidth: 3,
                                            fill: true,
                                            tension: 0.4,
                                            yAxisID: 'y',
                                            pointBackgroundColor: 'rgba(249, 115, 22, 1)',
                                            pointBorderColor: 'rgba(255, 255, 255, 1)',
                                            pointBorderWidth: 2,
                                            pointRadius: 4,
                                            pointHoverRadius: 6,
                                        },
                                        {
                                            label: 'Revenue (Rp)',
                                            data: @json($revenues),
                                            borderColor: 'rgba(234, 88, 12, 1)',
                                            backgroundColor: (context) => {
                                                const chart = context.chart;
                                                const {ctx, chartArea} = chart;
                                                if (!chartArea) return null;
                                                const gradient = ctx.createLinearGradient(0, chartArea.bottom, 0, chartArea.top);
                                                gradient.addColorStop(0, 'rgba(234, 88, 12, 0)');
                                                gradient.addColorStop(1, 'rgba(234, 88, 12, 0.2)');
                                                return gradient;
                                            },
                                            borderWidth: 3,
                                            fill: true,
                                            tension: 0.4,
                                            yAxisID: 'y1',
                                            pointBackgroundColor: 'rgba(234, 88, 12, 1)',
                                            pointBorderColor: 'rgba(255, 255, 255, 1)',
                                            pointBorderWidth: 2,
                                            pointRadius: 4,
                                            pointHoverRadius: 6,
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
                                            labels: {
                                                usePointStyle: true,
                                                padding: 20,
                                                font: {
                                                    size: 13
                                                }
                                            }
                                        },
                                        tooltip: {
                                            callbacks: {
                                                label: function(context) {
                                                    let label = context.dataset.label || '';
                                                    if (label) {
                                                        label += ': ';
                                                    }
                                                    if (context.datasetIndex === 1) {
                                                        // Format revenue as currency
                                                        label += new Intl.NumberFormat('id-ID', {
                                                            style: 'currency',
                                                            currency: 'IDR'
                                                        }).format(context.raw);
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
                                            grid: {
                                                display: false,
                                                drawBorder: false,
                                            },
                                            type: 'time',
                                            time: {
                                                unit: 'day',
                                                displayFormats: {
                                                    day: 'MMM d'
                                                }
                                            },
                                            title: {
                                                display: true,
                                                text: 'Date',
                                                font: {
                                                    size: 13,
                                                    family: "'Figtree', sans-serif"
                                                }
                                            },
                                            ticks: {
                                                font: {
                                                    size: 12,
                                                    family: "'Figtree', sans-serif"
                                                },
                                                color: '#6B7280'
                                            }
                                        },
                                        y: {
                                            type: 'linear',
                                            display: true,
                                            position: 'left',
                                            title: {
                                                display: true,
                                                text: 'Number of Orders',
                                                font: {
                                                    size: 13,
                                                    family: "'Figtree', sans-serif"
                                                }
                                            },
                                            grid: {
                                                borderDash: [4, 4],
                                                color: '#E5E7EB',
                                                drawBorder: false
                                            },
                                            ticks: {
                                                font: {
                                                    size: 12,
                                                    family: "'Figtree', sans-serif"
                                                },
                                                color: '#6B7280',
                                                padding: 8
                                            },
                                            beginAtZero: true
                                        },
                                        y1: {
                                            type: 'linear',
                                            display: true,
                                            position: 'right',
                                            title: {
                                                display: true,
                                                text: 'Revenue (Rp)',
                                                font: {
                                                    size: 13,
                                                    family: "'Figtree', sans-serif"
                                                }
                                            },
                                            grid: {
                                                display: false,
                                                drawBorder: false
                                            },
                                            ticks: {
                                                font: {
                                                    size: 12,
                                                    family: "'Figtree', sans-serif"
                                                },
                                                color: '#6B7280',
                                                padding: 8,
                                                callback: function(value) {
                                                    return 'Rp ' + value.toLocaleString('id-ID');
                                                }
                                            },
                                            beginAtZero: true
                                        }
                                    },
                                    animation: {
                                        duration: 2000,
                                        easing: 'easeOutQuart'
                                    }
                                }
                            });
                        });
                    </script>
                </div>
            </div>
        </main>
    </div>

    @livewireScripts
</body>
</html>
