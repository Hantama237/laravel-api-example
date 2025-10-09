<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebhookLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'gateway_name',
        'endpoint',
        'request_header',
        'request_body',
        'response_status',
        'response_body',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'response_status' => 'integer',
        ];
    }
}
