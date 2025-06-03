<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'fio',
        'date_of_birth'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
    ];

    public function testResults()
    {
        return $this->hasMany(TestResult::class);
    }

    public function getCompletedTestsCount()
    {
        return $this->testResults()->count();
    }

    public function hasCompletedTest($testCode)
    {
        return $this->testResults()->where('test_code', $testCode)->exists();
    }
}
