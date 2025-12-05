<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
     * Display the specified resource (Payment Page / Upload Proof).
     */
    public function show(string $orderId)
    {
        // Find order with payment
        $order = Order::where('user_id', Auth::id())
            ->with(['orderItems.product', 'payment'])
            ->findOrFail($orderId);

        // Check if payment exists
        if (!$order->payment) {
            return redirect()->route('orders.index')
                ->with('error', 'Informasi pembayaran tidak ditemukan.');
        }

        // Jika COD, tidak perlu upload bukti transfer
        if ($order->payment->payment_method === 'cod') {
            return redirect()->route('orders.show', $order->id);
        }

        return view('payments.show', compact('order'));
    }

    /**
     * Handle Upload Proof of Payment
     */
    public function uploadProof(Request $request, string $id)
    {
        $request->validate([
            'proof_of_payment' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'bank_name' => 'nullable|string|max:100',
            'account_name' => 'nullable|string|max:100',
        ]);

        $payment = Payment::findOrFail($id);

        // Security check
        if ($payment->user_id !== Auth::id()) {
            abort(403);
        }

        try {
            if ($request->hasFile('proof_of_payment')) {
                // Delete old proof if exists
                if ($payment->proof_of_payment) {
                    Storage::disk('public')->delete($payment->proof_of_payment);
                }

                // Store new proof
                $path = $request->file('proof_of_payment')->store('payment_proofs', 'public');
                
                $payment->update([
                    'proof_of_payment' => $path,
                    'bank_name' => $request->bank_name,
                    'account_name' => $request->account_name,
                    'payment_status' => 'pending', // Tetap pending menunggu konfirmasi admin
                ]);

                return redirect()->route('orders.show', $payment->order_id)
                    ->with('success', 'Bukti pembayaran berhasil diupload! Mohon tunggu konfirmasi admin.');
            }

            return redirect()->back()->with('error', 'Gagal mengupload bukti pembayaran.');

        } catch (\Exception $e) {
            Log::error('Upload Proof Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupload bukti pembayaran.');
        }
    }

    /**
     * Confirm Payment (Admin Only)
     */
    public function confirm(string $id)
    {
        // Hanya admin yang boleh konfirmasi
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'payment_status' => 'success',
        ]);

        $payment->order->update([
            'status' => 'paid',
        ]);

        return redirect()->back()->with('success', 'Pembayaran berhasil dikonfirmasi.');
    }
    
    /**
     * Reject Payment (Admin Only)
     */
    public function reject(string $id)
    {
        // Hanya admin yang boleh tolak
        if (!Auth::user()->isAdmin()) {
            abort(403);
        }

        $payment = Payment::findOrFail($id);
        
        $payment->update([
            'payment_status' => 'failed',
        ]);
        
        // Opsional: Order status bisa diubah ke cancelled atau tetap pending
        // $payment->order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pembayaran ditolak.');
    }
}
