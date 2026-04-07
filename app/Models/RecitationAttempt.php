<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class RecitationAttempt extends Model
{
    protected $fillable = [
        'user_id', 'ayah_id', 'audio_file_path', 'transcribed_text',
        'similarity_score', 'mistakes_count', 'is_passed'
    ];

    protected $casts = [
        'is_passed' => 'boolean',
        'similarity_score' => 'decimal:2',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ayah() {
        return $this->belongsTo(Ayah::class);
    }
}
