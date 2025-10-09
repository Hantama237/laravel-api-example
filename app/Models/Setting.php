<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'id',
        'key',
        'value',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'key' => 'string',
        ];
    }
}
