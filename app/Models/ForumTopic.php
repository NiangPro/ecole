<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modèle représentant un topic du forum
 * 
 * Un topic est un sujet de discussion créé par un utilisateur dans une catégorie.
 * Il peut être épinglé, verrouillé, et contient plusieurs réponses.
 * 
 * @property int $id
 * @property int $category_id ID de la catégorie
 * @property int $user_id ID de l'utilisateur créateur
 * @property string $title Titre du topic
 * @property string $slug Slug unique du topic
 * @property string $body Contenu du topic
 * @property int $views Nombre de vues
 * @property int $replies_count Nombre de réponses
 * @property bool $is_pinned Indique si le topic est épinglé
 * @property bool $is_locked Indique si le topic est verrouillé
 * @property \Carbon\Carbon|null $last_reply_at Date de la dernière réponse
 * @property int|null $last_reply_user_id ID de l'utilisateur de la dernière réponse
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read ForumCategory $category Catégorie du topic
 * @property-read \App\Models\User $user Utilisateur créateur
 * @property-read \App\Models\User|null $lastReplyUser Utilisateur de la dernière réponse
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumReply[] $replies Réponses du topic
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'user_id',
        'title',
        'slug',
        'body',
        'views',
        'replies_count',
        'is_pinned',
        'is_locked',
        'last_reply_at',
        'last_reply_user_id',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'last_reply_at' => 'datetime',
        'views' => 'integer',
        'replies_count' => 'integer',
    ];

    /**
     * Génère automatiquement le slug à partir du titre lors de la création
     * 
     * Vérifie l'unicité du slug et ajoute un suffixe numérique si nécessaire.
     * Incrémente également le compteur de topics de la catégorie.
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($topic) {
            if (empty($topic->slug)) {
                $topic->slug = Str::slug($topic->title);
                
                // Vérifier l'unicité du slug
                $count = static::where('slug', $topic->slug)->count();
                if ($count > 0) {
                    $topic->slug = $topic->slug . '-' . ($count + 1);
                }
            }
        });

        static::created(function ($topic) {
            $topic->category->incrementTopicsCount();
        });

        static::deleted(function ($topic) {
            $topic->category->decrementTopicsCount();
        });
    }

    /**
     * Relation avec la catégorie du topic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $category = $topic->category; // Retourne ForumCategory
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(ForumCategory::class, 'category_id');
    }

    /**
     * Relation avec l'utilisateur créateur du topic
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $author = $topic->user; // Retourne User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Relation avec l'utilisateur ayant posté la dernière réponse
     * 
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $lastUser = $topic->lastReplyUser; // Retourne User ou null
     */
    public function lastReplyUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'last_reply_user_id');
    }

    /**
     * Relation avec les réponses du topic
     * 
     * Les réponses sont triées par date de création (plus anciennes en premier).
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $replies = $topic->replies; // Collection de ForumReply
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'topic_id')->orderBy('created_at', 'asc');
    }

    /**
     * Récupère les réponses du topic avec pagination
     * 
     * @param int $perPage Nombre de réponses par page (défaut: 15)
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $paginatedReplies = $topic->paginatedReplies(20);
     */
    public function paginatedReplies($perPage = 15)
    {
        return $this->replies()->paginate($perPage);
    }

    /**
     * Incrémente le compteur de vues du topic
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->incrementViews(); // Incrémente $topic->views
     */
    public function incrementViews()
    {
        $this->increment('views');
    }

    /**
     * Incrémente le compteur de réponses et met à jour la date de dernière réponse
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->incrementRepliesCount(); // Incrémente replies_count et met à jour last_reply_at
     */
    public function incrementRepliesCount()
    {
        $this->increment('replies_count');
        $this->update(['last_reply_at' => now()]);
    }

    /**
     * Décrémente le compteur de réponses
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->decrementRepliesCount(); // Décrémente replies_count
     */
    public function decrementRepliesCount()
    {
        $this->decrement('replies_count');
    }

    /**
     * Épingle le topic en haut de la liste
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->pin(); // Met is_pinned à true
     */
    public function pin()
    {
        $this->update(['is_pinned' => true]);
    }

    /**
     * Désépingle le topic
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->unpin(); // Met is_pinned à false
     */
    public function unpin()
    {
        $this->update(['is_pinned' => false]);
    }

    /**
     * Verrouille le topic pour empêcher de nouvelles réponses
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->lock(); // Met is_locked à true
     */
    public function lock()
    {
        $this->update(['is_locked' => true]);
    }

    /**
     * Déverrouille le topic pour permettre de nouvelles réponses
     * 
     * @return void
     * 
     * @example
     * $topic = ForumTopic::find(1);
     * $topic->unlock(); // Met is_locked à false
     */
    public function unlock()
    {
        $this->update(['is_locked' => false]);
    }
}
