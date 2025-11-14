<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promo extends Model
{
    protected $fillable = [
        'code',
        'discount',
        'valid_until',
    ];

    protected function casts(): array
    {
        return [
            'valid_until' => 'date',
        ];
    }
}
