@push('scripts')
    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
@endpush

<div class="bg-white rounded-lg shadow-md p-6 mt-4">
    <h3 class="text-lg font-semibold mb-4">Payment</h3>
    
    @if($order->payment && $order->payment->payment_status === 'success')
        <div class="bg-green-100 text-green-700 p-4 rounded-md">
            Payment has been completed
        </div>
    @else
        @if($order->payment && $order->payment->snap_token)
            <button type="button" 
                    class="pay-button bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700"
                    data-snap-token="{{ $order->payment->snap_token }}">
                Pay Now
            </button>
        @else
            <form action="{{ route('payments.store') }}" method="POST" id="payment-form">
                @csrf
                <input type="hidden" name="order_id" value="{{ $order->id }}">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Process Payment
                </button>
            </form>
        @endif
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle payment form submission
    const paymentForm = document.getElementById('payment-form');
    if (paymentForm) {
        paymentForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        order_id: this.querySelector('input[name="order_id"]').value
                    })
                });

                const data = await response.json();
                
                if (data.snap_token) {
                    window.snap.pay(data.snap_token, {
                        onSuccess: function(result) {
                            window.location.reload();
                        },
                        onPending: function(result) {
                            window.location.reload();
                        },
                        onError: function(result) {
                            alert('Payment failed! Please try again.');
                        },
                        onClose: function() {
                            alert('You closed the payment window without completing the payment.');
                        }
                    });
                } else {
                    alert('Failed to initialize payment!');
                }
            } catch (error) {
                console.error('Payment error:', error);
                alert('Failed to process payment. Please try again.');
            }
        });
    }

    // Handle pay now button
    const payButton = document.querySelector('.pay-button');
    if (payButton) {
        payButton.addEventListener('click', function() {
            const snapToken = this.dataset.snapToken;
            window.snap.pay(snapToken, {
                onSuccess: function(result) {
                    window.location.reload();
                },
                onPending: function(result) {
                    window.location.reload();
                },
                onError: function(result) {
                    alert('Payment failed! Please try again.');
                },
                onClose: function() {
                    alert('You closed the payment window without completing the payment.');
                }
            });
        });
    }
});
</script>
@endpush