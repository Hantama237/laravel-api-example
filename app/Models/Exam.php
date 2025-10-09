<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Exam extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image_url',
        'sequence',
        'passed_count',
    ];

    protected function casts(): array
    {
        return [
            'sequence' => 'integer',
            'passed_count' => 'integer',
        ];
    }

    /**
     * Get the questions for the exam.
     */
    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    /**
     * Get the student exams for the exam.
     */
    public function studentExams()
    {
        return $this->hasMany(StudentExam::class);
    }
}
