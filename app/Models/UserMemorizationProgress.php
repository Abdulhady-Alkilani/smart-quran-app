<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class UserMemorizationProgress extends Model
{
    // تحديد اسم الجدول لأن الاسم لا يتبع صيغة الجمع الافتراضية
    protected $table = 'user_memorization_progress';

    protected $fillable = [
        'user_id', 'ayah_id', 'status', 'repetition_count',
        'easiness_factor', 'interval_days', 'last_review_date', 'next_review_date'
    ];

    protected $casts = [
        'last_review_date' => 'date',
        'next_review_date' => 'date',
        'easiness_factor' => 'decimal:4',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function ayah() {
        return $this->belongsTo(Ayah::class);
    }
}
