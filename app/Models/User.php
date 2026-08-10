<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'google_id',
        'github_id',
        'facebook_id',
        'avatar',
        'role',
        'phone',
        'bio',
        'country',
        'region',
        'city',
        'gender',
        'date_of_birth',
        'occupation',
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
            'date_of_birth' => 'date',
        ];
    }

    /**
     * Prénom, avec repli sur le premier mot de `name` pour les comptes existants
     * créés avant l'ajout des colonnes first_name/last_name.
     */
    public function getFirstNameAttribute(): ?string
    {
        $value = $this->attributes['first_name'] ?? null;
        if ($value) {
            return $value;
        }

        return $this->name ? trim(explode(' ', trim($this->name), 2)[0]) : null;
    }

    /**
     * Nom de famille, avec repli sur le reste de `name` pour les comptes existants.
     */
    public function getLastNameAttribute(): ?string
    {
        $value = $this->attributes['last_name'] ?? null;
        if ($value) {
            return $value;
        }

        if (!$this->name) {
            return null;
        }

        $parts = explode(' ', trim($this->name), 2);
        return $parts[1] ?? null;
    }

    /**
     * Champs obligatoires pour accéder aux certificats et cours payants
     * ("Informations personnelles" + "Informations complémentaires", bio exclue).
     * On lit les colonnes brutes (pas les accesseurs avec repli) pour forcer
     * une confirmation explicite de l'utilisateur, même sur les anciens comptes.
     */
    public function missingRequiredProfileFields(): array
    {
        $required = [
            'first_name' => $this->attributes['first_name'] ?? null,
            'last_name' => $this->attributes['last_name'] ?? null,
            'phone' => $this->phone,
            'occupation' => $this->occupation,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
        ];

        return array_keys(array_filter($required, fn ($value) => $value === null || $value === ''));
    }

    public function hasCompletedRequiredProfile(): bool
    {
        return empty($this->missingRequiredProfileFields());
    }

    /**
     * Pourcentage de complétion du profil (champs facultatifs pris en compte).
     */
    public function getProfileCompletionAttribute(): int
    {
        $fields = [
            $this->name,
            $this->email,
            $this->phone,
            $this->avatar,
            $this->bio,
            $this->country,
            $this->region,
            $this->city,
            $this->gender,
            $this->date_of_birth,
            $this->occupation,
        ];

        $filled = count(array_filter($fields, fn ($value) => !is_null($value) && $value !== ''));

        return (int) round(($filled / count($fields)) * 100);
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

    public function documentPurchases()
    {
        return $this->hasMany(DocumentPurchase::class);
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

    // Relations Forum
    public function forumTopics()
    {
        return $this->hasMany(ForumTopic::class);
    }

    public function forumReplies()
    {
        return $this->hasMany(ForumReply::class);
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
