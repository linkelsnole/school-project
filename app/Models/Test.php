<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'description',
        'status'
    ];

    public function categories()
    {
        return $this->hasMany(QuestionCategory::class)->orderBy('order_index');
    }

    public function questions()
    {
        return $this->hasMany(TestQuestion::class)->orderBy('order_index');
    }

    public function results()
    {
        return $this->hasMany(TestResult::class, 'test_code', 'code');
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
