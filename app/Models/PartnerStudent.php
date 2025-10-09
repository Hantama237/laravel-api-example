<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PartnerStudent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'partner_id',
        'student_id',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'partner_id' => 'string',
            'student_id' => 'string',
        ];
    }

    /**
     * Get the partner (user) that owns the partner student relationship.
     */
    public function partner()
    {
        return $this->belongsTo(User::class, 'partner_id');
    }

    /**
     * Get the student (user) that owns the partner student relationship.
     */
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
}
