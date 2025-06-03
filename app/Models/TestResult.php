<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'test_code',
        'test_title',
        'answers',
        'score',
        'created_at'
    ];

    protected $casts = [
        'answers' => 'json',
        'score' => 'json',
        'created_at' => 'datetime',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
