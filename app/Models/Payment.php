<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id',
        'order_id',
        'payment_method',
        'payment_status',
        'amount',
        'transaction_id',
        'snap_token',
        'proof_of_payment',
        'bank_name',
        'account_name',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
