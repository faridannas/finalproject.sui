<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Order;

class NotificationService
{
    public function notifySuccessLogin($user)
    {
        return Notification::create([
            'user_id' => $user->id,
            'title' => 'Login Berhasil',
            'message' => 'Selamat datang kembali ' . $user->name,
            'type' => 'login'
        ]);
    }

    public function notifyNewOrder(Order $order)
    {
        // Notifikasi untuk admin
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'title' => 'Pesanan Baru',
                'message' => "Ada pesanan baru dari {$order->user->name}",
                'type' => 'order'
            ]);
        }

        // Notifikasi untuk user
        return Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Pesanan Diterima',
            'message' => "Pesanan anda telah kami terima dan sedang diproses",
            'type' => 'order'
        ]);
    }

    public function notifyOrderStatusUpdate(Order $order)
    {
        $statusMessages = [
            'pending' => 'Pesanan anda sedang menunggu konfirmasi',
            'processing' => 'Pesanan anda sedang diproses',
            'completed' => 'Pesanan anda telah selesai',
            'cancelled' => 'Pesanan anda telah dibatalkan'
        ];

        return Notification::create([
            'user_id' => $order->user_id,
            'title' => 'Status Pesanan Update',
            'message' => $statusMessages[$order->status] ?? 'Status pesanan anda telah diupdate',
            'type' => 'order_status'
        ]);
    }
}