<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentAnswer extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'student_exam_id',
        'question_id',
        'is_correct',
        'created_at',
    ];

    protected function casts(): array
    {
        return [
            'student_exam_id' => 'string',
            'question_id' => 'string',
            'is_correct' => 'boolean',
        ];
    }

    /**
     * Get the student exam that owns the student answer.
     */
    public function studentExam()
    {
        return $this->belongsTo(StudentExam::class);
    }

    /**
     * Get the question that owns the student answer.
     */
    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
