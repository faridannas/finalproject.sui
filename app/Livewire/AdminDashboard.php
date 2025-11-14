<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Models\Payment;
use Livewire\Component;

class AdminDashboard extends Component
{
    public $totalUsers;
    public $totalProducts;
    public $totalOrders;
    public $totalRevenue;
    public $recentOrders;

    public function mount()
    {
        $this->loadStats();
    }

    public function loadStats()
    {
        $this->totalUsers = User::where('role', 'customer')->count();
        $this->totalProducts = Product::count();
        $this->totalOrders = Order::count();
        $this->totalRevenue = Payment::where('payment_status', 'success')->sum('amount');
        $this->recentOrders = Order::with('user')->latest()->take(5)->get();
    }

    public function render()
    {
        return view('livewire.admin-dashboard');
    }
}
