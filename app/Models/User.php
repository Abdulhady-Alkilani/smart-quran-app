<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function roles() {
    return $this->belongsToMany(Role::class);
}

public function profile() {
    return $this->hasOne(Profile::class);
}

public function memorizationProgress() {
    return $this->hasMany(UserMemorizationProgress::class);
}

public function recitationAttempts() {
    return $this->hasMany(RecitationAttempt::class);
}

public function quizAttempts() {
    return $this->hasMany(UserQuizAttempt::class);
}

public function canAccessPanel(Panel $panel): bool
{
    return $this->roles()->where('name', 'admin')->exists();
}
}
