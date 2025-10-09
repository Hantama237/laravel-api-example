<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        'exam_id',
        'question_text',
        'type',
        'category_id',
        'image_url',
        'video_url',
        'sequence',
        'passed_count',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'exam_id' => 'integer',
            'category_id' => 'integer',
            'sequence' => 'integer',
            'passed_count' => 'integer',
            'type' => 'string',
        ];
    }

    /**
     * Get the exam that owns the question.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the category that owns the question.
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the answers for the question.
     */
    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    /**
     * Get the student answers for the question.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
