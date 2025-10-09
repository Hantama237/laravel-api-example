<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentGatewayCallback extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'payment_id',
        'gateway_name',
        'gateway_transaction_id',
        'raw_data',
        'status_code',
        'is_proceed',
        'proceed_at',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'payment_id' => 'string',
            'raw_data' => 'array',
            'status_code' => 'integer',
            'is_proceed' => 'boolean',
            'proceed_at' => 'datetime',
        ];
    }

    /**
     * Get the payment that owns the callback.
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }
}
