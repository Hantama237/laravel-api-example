<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'type',
    ];

    protected function casts(): array
    {
        return [
            'type' => 'string',
        ];
    }

    /**
     * Get the questions for the category.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
