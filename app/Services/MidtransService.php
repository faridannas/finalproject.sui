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
        
        // Disable SSL verification for development (Windows fix for cURL error 60)
        // WARNING: Only use this in development! Remove for production!
        Config::$curlOptions = [
            CURLOPT_SSL_VERIFYHOST => 0,
            CURLOPT_SSL_VERIFYPEER => 0,
        ];
    }

    public function createTransaction(Order $order)
    {
        $itemDetails = $this->getItemDetails($order);
        
        // Calculate total from items
        $itemsTotal = 0;
        foreach ($itemDetails as $item) {
            $itemsTotal += $item['price'] * $item['quantity'];
        }

        // Calculate discount (difference between items total and order total)
        $grossAmount = (int)$order->total_price;
        $discountAmount = $itemsTotal - $grossAmount;

        // If there is a discount, add it as a negative item
        if ($discountAmount > 0) {
            $itemDetails[] = [
                'id' => 'DISCOUNT',
                'price' => -$discountAmount,
                'quantity' => 1,
                'name' => 'Diskon Promo',
            ];
        }

        // Prepare customer details
        $customerDetails = [
            'first_name' => $order->user->name,
            'email' => $order->user->email,
        ];
        
        if (!empty($order->user->phone)) {
            $customerDetails['phone'] = $order->user->phone;
        }

        $params = [
            'transaction_details' => [
                'order_id' => $order->id,
                'gross_amount' => $grossAmount,
            ],
            'customer_details' => $customerDetails,
            'item_details' => $itemDetails,
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            
            // Update or create payment record
            $this->savePaymentRecord($order, $snapToken);

            return $snapToken;
        } catch (\Exception $e) {
            // Log detailed error information
            Log::error('Midtrans Full Details Error for Order #' . $order->id);
            Log::error('Error Message: ' . $e->getMessage());
            Log::error('Error Code: ' . $e->getCode());
            Log::error('Params sent: ' . json_encode($params, JSON_PRETTY_PRINT));
            
            // Check if it's a Midtrans API error with response body
            if (method_exists($e, 'getResponseBody')) {
                Log::error('Midtrans Response: ' . $e->getResponseBody());
            }
            
            Log::warning('Retrying with minimal params...');
            
            // Fallback: Try with minimal parameters (only transaction_details)
            $minimalParams = [
                'transaction_details' => [
                    'order_id' => $order->id,
                    'gross_amount' => $grossAmount,
                ],
            ];

            try {
                $snapToken = Snap::getSnapToken($minimalParams);
                $this->savePaymentRecord($order, $snapToken);
                Log::info('Midtrans transaction created successfully with minimal params for Order #' . $order->id);
                return $snapToken;
            } catch (\Exception $retryException) {
                Log::error('Midtrans Minimal Params ALSO Failed for Order #' . $order->id);
                Log::error('Retry Error Message: ' . $retryException->getMessage());
                Log::error('Retry Error Code: ' . $retryException->getCode());
                
                if (method_exists($retryException, 'getResponseBody')) {
                    Log::error('Retry Midtrans Response: ' . $retryException->getResponseBody());
                }
                
                throw new \Exception('Gagal membuat transaksi pembayaran. Silakan periksa konfigurasi Midtrans atau hubungi administrator.');
            }
        }
    }

    private function savePaymentRecord(Order $order, string $snapToken)
    {
        Payment::updateOrCreate(
            ['order_id' => $order->id],
            [
                'user_id' => $order->user_id,
                'amount' => $order->total_price,
                'payment_method' => 'midtrans',
                'payment_status' => 'pending',
                'snap_token' => $snapToken,
            ]
        );
    }

    private function getItemDetails(Order $order)
    {
        $items = [];
        foreach ($order->orderItems as $item) {
            $items[] = [
                'id' => $item->product_id,
                'price' => (int)$item->product->price, // Ensure integer
                'quantity' => $item->quantity,
                'name' => substr($item->product->name, 0, 50), // Limit name length
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