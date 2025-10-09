<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'id',
        'subscription_id',
        'noi',
        'amount',
        'tax',
        'amount_w_tax',
        'due_date',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'subscription_id' => 'string',
            'amount' => 'decimal:2',
            'tax' => 'float',
            'amount_w_tax' => 'decimal:2',
            'due_date' => 'date',
            'status' => 'string',
        ];
    }

    /**
     * Get the subscription that owns the invoice.
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
