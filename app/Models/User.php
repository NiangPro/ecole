<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
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
        'role',
        'phone',
        'is_active',
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
            'is_active' => 'boolean',
        ];
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function formationProgress()
    {
        return $this->hasMany(FormationProgress::class);
    }

    public function getProgressForFormation(string $formationSlug): ?FormationProgress
    {
        return $this->formationProgress()->where('formation_slug', $formationSlug)->first();
    }

    public function exerciseProgress()
    {
        return $this->hasMany(ExerciseProgress::class);
    }

    public function quizResults()
    {
        return $this->hasMany(QuizResult::class);
    }

    public function activities()
    {
        return $this->hasMany(UserActivity::class);
    }

    public function goals()
    {
        return $this->hasMany(UserGoal::class);
    }

    public function badges()
    {
        return $this->belongsToMany(Badge::class, 'user_badges')
            ->withPivot('earned_at', 'metadata')
            ->withTimestamps()
            ->orderBy('user_badges.earned_at', 'desc');
    }

    public function userBadges()
    {
        return $this->hasMany(UserBadge::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    /**
     * Vérifier si l'utilisateur a un badge spécifique
     */
    public function hasBadge(string $badgeCode): bool
    {
        return $this->badges()->where('code', $badgeCode)->exists();
    }

    /**
     * Obtenir un badge spécifique de l'utilisateur
     */
    public function getBadge(string $badgeCode): ?Badge
    {
        return $this->badges()->where('code', $badgeCode)->first();
    }
}
