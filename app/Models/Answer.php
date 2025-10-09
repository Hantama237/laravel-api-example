<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Answer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'question_id',
        'answer_text',
        'image_url',
        'sequence',
        'is_correct',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'question_id' => 'string',
            'sequence' => 'integer',
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Get the question that owns the answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
