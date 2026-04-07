<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Ayah extends Model
{
    protected $fillable = ['surah_id', 'number_in_surah', 'number_in_quran', 'text_uthmani', 'text_imlaei', 'audio_url'];

    public function surah() {
        return $this->belongsTo(Surah::class);
    }

    public function memorizationProgress() {
        return $this->hasMany(UserMemorizationProgress::class);
    }

    public function recitationAttempts() {
        return $this->hasMany(RecitationAttempt::class);
    }
}
