<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'partner_name',
        'birth_date',
        'kvk',
        'address_one',
        'address_two',
        'city',
        'country',
        'contact_name',
        'contact_email',
        'contact_phone',
        'mobile',
        'avatar_url',
        'logo_url',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'string',
            'birth_date' => 'date',
        ];
    }

    /**
     * Get the user that owns the profile.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
