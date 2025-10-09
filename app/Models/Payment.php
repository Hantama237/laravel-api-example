<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'nop',
        'invoice_id',
        'amount',
        'tax',
        'amount_w_tax',
        'method',
        'payment_date',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'invoice_id' => 'string',
            'amount' => 'decimal:2',
            'tax' => 'float',
            'amount_w_tax' => 'decimal:2',
            'payment_date' => 'date',
            'method' => 'string',
        ];
    }

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get the payment gateway callbacks for the payment.
     */
    public function paymentGatewayCallbacks()
    {
        return $this->hasMany(PaymentGatewayCallback::class);
    }
}
