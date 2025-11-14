<?php

namespace App\Services;

use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;

class MidtransService
{
    public function __construct()
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$clientKey = config('midtrans.client_key');
        Config::$isProduction = config('midtrans.is_production');
        Config::$is3ds = config('midtrans.is_3ds');
        Config::$isSanitized = config('midtrans.is_sanitized');
    }

    public function createTransaction(Order $order)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => (int)$order->total_price,
            ],
            'customer_details' => [
                'first_name' => $order->user->name,
                'email' => $order->user->email,
            ],
            'item_details' => $this->getItemDetails($order),
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Update or create payment record
            $payment = Payment::updateOrCreate(
                ['order_id' => $order->id],
                [
                    'user_id' => $order->user_id,
                    'amount' => $order->total_price,
                    'payment_method' => 'midtrans',
                    'payment_status' => 'pending',
                    'snap_token' => $snapToken,
                ]
            );

            return $snapToken;
        } catch (\Exception $e) {
            Log::error('Midtrans Error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getItemDetails(Order $order)
    {
        $items = [];
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'name' => $item->product->name,
            ];
        }
        return $items;
    }

    public function handleNotification(array $notification)
    {
        $orderId = $notification['order_id'];
        $transactionStatus = $notification['transaction_status'];
        $payment = Payment::where('order_id', $orderId)->first();

        if (!$payment) {
            throw new \Exception('Payment not found');
        }

        // Update payment status based on transaction status
        switch ($transactionStatus) {
            case 'capture':
            case 'settlement':
                $payment->payment_status = 'success';
                $payment->order->status = 'paid';
                break;
            case 'pending':
                $payment->payment_status = 'pending';
                break;
            case 'deny':
            case 'expire':
            case 'cancel':
                $payment->payment_status = 'failed';
                $payment->order->status = 'cancelled';
                break;
        }

        $payment->transaction_id = $notification['transaction_id'] ?? null;
        $payment->save();
        $payment->order->save();

        return $payment;
    }
}