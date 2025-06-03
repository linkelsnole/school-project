<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_id',
        'category_id',
        'question_text',
        'question_type',
        'order_index',
        'is_required'
    ];

    protected $casts = [
        'is_required' => 'boolean'
    ];

    public function test()
    {
        return $this->belongsTo(Test::class);
    }

    public function category()
    {
        return $this->belongsTo(QuestionCategory::class, 'category_id');
    }

    public function options()
    {
        return $this->hasMany(TestQuestionOption::class, 'question_id')->orderBy('order_index');
    }
}
