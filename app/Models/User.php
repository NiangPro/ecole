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
        'is_premium',
        'premium_until',
        'current_subscription_id',
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
            'is_premium' => 'boolean',
            'premium_until' => 'datetime',
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

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class)->orderBy('created_at', 'desc');
    }

    public function unreadNotifications()
    {
        return $this->notifications()->where('is_read', false);
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

    // Relations de monétisation
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function currentSubscription()
    {
        return $this->belongsTo(Subscription::class, 'current_subscription_id');
    }

    public function coursePurchases()
    {
        return $this->hasMany(CoursePurchase::class);
    }

    public function donations()
    {
        return $this->hasMany(Donation::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function affiliate()
    {
        return $this->hasOne(Affiliate::class);
    }

    /**
     * Vérifier si l'utilisateur a un abonnement premium actif
     */
    public function hasActivePremium(): bool
    {
        if (!$this->is_premium) {
            return false;
        }

        if ($this->premium_until && $this->premium_until->isPast()) {
            return false;
        }

        if ($this->currentSubscription) {
            return $this->currentSubscription->isActive();
        }

        return true;
    }

    /**
     * Vérifier si l'utilisateur a acheté un cours
     */
    public function hasPurchasedCourse(int $courseId): bool
    {
        return $this->coursePurchases()
            ->where('paid_course_id', $courseId)
            ->where('status', 'completed')
            ->exists();
    }
}
