<?php

namespace App\Livewire;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class TransactionHistory extends Component
{
    public $orders;

    public function mount()
    {
        $this->loadOrders();
    }

    public function loadOrders()
    {
        $this->orders = Order::where('user_id', Auth::id())
            ->with('orderItems.product')
            ->latest()
            ->get();
    }

    public function deleteOrder($orderId)
    {
        $order = Order::where('user_id', Auth::id())->find($orderId);

        if ($order) {
            $order->delete();
            $this->loadOrders(); // Refresh the list
            session()->flash('success', 'Riwayat pesanan berhasil dihapus.');
        } else {
            session()->flash('error', 'Pesanan tidak ditemukan.');
        }
    }

    public function render()
    {
        return view('livewire.transaction-history');
    }
}
