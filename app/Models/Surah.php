<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Surah extends Model
{
    protected $fillable = ['number', 'name_ar', 'name_en', 'revelation_type', 'total_ayahs'];

    public function ayahs() {
        return $this->hasMany(Ayah::class);
    }

    public function generatedQuestions() {
        return $this->hasMany(GeneratedQuestion::class);
    }
}
