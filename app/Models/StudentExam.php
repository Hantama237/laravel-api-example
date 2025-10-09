<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentExam extends Model
{
    protected $fillable = [
        'id',
        'user_id',
        'exam_id',
        'first_intaken',
        'last_intaken',
        'total_time_intake',
        'is_passed',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'string',
            'user_id' => 'string',
            'exam_id' => 'integer',
            'first_intaken' => 'datetime',
            'last_intaken' => 'datetime',
            'is_passed' => 'boolean',
            'status' => 'string',
        ];
    }

    /**
     * Get the user that owns the student exam.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exam that owns the student exam.
     */
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    /**
     * Get the student answers for the student exam.
     */
    public function studentAnswers()
    {
        return $this->hasMany(StudentAnswer::class);
    }
}
