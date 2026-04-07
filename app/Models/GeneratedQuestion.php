<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class GeneratedQuestion extends Model
{
    protected $fillable = ['surah_id', 'ayah_id', 'question_text', 'options', 'correct_answer'];

    protected $casts = [
        'options' => 'array',
    ];

    public function surah() {
        return $this->belongsTo(Surah::class);
    }

    public function ayah() {
        return $this->belongsTo(Ayah::class);
    }

    public function quizAttempts() {
        return $this->hasMany(UserQuizAttempt::class, 'question_id');
    }
}
