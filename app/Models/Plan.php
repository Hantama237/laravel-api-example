<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'type',
        'slot',
        'duration',
        'base_price',
        'description',
        'is_enable',
        'is_built_in',
    ];

    protected function casts(): array
    {
        return [
            'slot' => 'integer',
            'base_price' => 'decimal:2',
            'is_enable' => 'boolean',
            'is_built_in' => 'boolean',
            'type' => 'string',
        ];
    }

    /**
     * Get the subscriptions for the plan.
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}
