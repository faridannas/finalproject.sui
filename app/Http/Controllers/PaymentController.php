<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $payments = Payment::where('user_id', Auth::id())->with('order')->paginate(10);
        return view('payments.index', compact('payments'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
        ]);

        try {
            $order = Order::with(['user', 'orderItems.product'])->findOrFail($request->order_id);
            
            if ($order->user_id !== Auth::id()) {
                abort(403);
            }

            $midtrans = new MidtransService();
            $snapToken = $midtrans->createTransaction($order);

            return response()->json([
                'snap_token' => $snapToken,
            ]);
        } catch (\Exception $e) {
            Log::error('Payment Error: ' . $e->getMessage());
            return response()->json([
                'error' => 'Failed to process payment'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $payment = Payment::where('user_id', Auth::id())->with('order.orderItems.product')->findOrFail($id);
        return view('payments.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Manual payment confirmation (for admin or user)
        $payment = Payment::findOrFail($id);
        if ($payment->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment->update([
            'payment_status' => 'success',
        ]);

        $payment->order->update([
            'status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Payment confirmed successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function notification(Request $request)
    {
        try {
            $notification = $request->all();
            
            $midtrans = new MidtransService();
            $payment = $midtrans->handleNotification($notification);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            Log::error('Notification Error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
