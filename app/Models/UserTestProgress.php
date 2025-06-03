<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestProgress extends Model
{
    use HasFactory;

    protected $table = 'user_test_progress';

    protected $fillable = [
        'user_id',
        'test_id',
        'test_title',
        'test_category',
        'status',
        'score',
        'completed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeVerified($query)
    {
        return $query->where('status', 'verified');
    }

    public function isCompleted()
    {
        return $this->status === 'completed' || $this->status === 'verified';
    }
}
