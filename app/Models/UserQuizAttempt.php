<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserQuizAttempt extends Model
{
    protected $fillable = ['user_id', 'question_id', 'user_answer', 'is_correct'];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function question() {
        return $this->belongsTo(GeneratedQuestion::class, 'question_id');
    }
}
