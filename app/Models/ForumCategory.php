<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

/**
 * Modèle représentant une catégorie du forum
 * 
 * Une catégorie regroupe plusieurs topics. Elle peut être activée/désactivée
 * et possède un ordre d'affichage, une icône et une couleur personnalisée.
 * 
 * @property int $id
 * @property string $name Nom de la catégorie
 * @property string $slug Slug unique de la catégorie
 * @property string|null $description Description de la catégorie
 * @property string|null $icon Classe Font Awesome de l'icône
 * @property string $color Code couleur hexadécimal (défaut: #06b6d4)
 * @property int $order Ordre d'affichage (défaut: 0)
 * @property bool $is_active Indique si la catégorie est active
 * @property int $topics_count Nombre de topics dans la catégorie
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * 
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumTopic[] $topics Topics de la catégorie
 * @property-read \Illuminate\Database\Eloquent\Collection|ForumTopic[] $activeTopics Topics actifs (non verrouillés)
 * 
 * @package App\Models
 * @author NiangProgrammeur
 * @since 2024-12-25
 */
class ForumCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'icon',
        'color',
        'order',
        'is_active',
        'topics_count',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer',
        'topics_count' => 'integer',
    ];

    /**
     * Génère automatiquement le slug à partir du nom lors de la création
     * 
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }

    /**
     * Relation avec tous les topics de la catégorie
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $category = ForumCategory::find(1);
     * $allTopics = $category->topics; // Collection de tous les ForumTopic
     */
    public function topics(): HasMany
    {
        return $this->hasMany(ForumTopic::class, 'category_id');
    }

    /**
     * Relation avec les topics actifs (non verrouillés) de la catégorie
     * 
     * Les topics sont triés par : épinglés en premier, puis par date de dernière réponse.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     * 
     * @example
     * $category = ForumCategory::find(1);
     * $activeTopics = $category->activeTopics; // Collection de ForumTopic non verrouillés
     */
    public function activeTopics(): HasMany
    {
        return $this->hasMany(ForumTopic::class, 'category_id')
            ->where('is_locked', false)
            ->orderBy('is_pinned', 'desc')
            ->orderBy('last_reply_at', 'desc');
    }

    /**
     * Incrémente le compteur de topics de la catégorie
     * 
     * @return void
     * 
     * @example
     * $category = ForumCategory::find(1);
     * $category->incrementTopicsCount(); // Incrémente topics_count
     */
    public function incrementTopicsCount()
    {
        $this->increment('topics_count');
    }

    /**
     * Décrémente le compteur de topics de la catégorie
     * 
     * @return void
     * 
     * @example
     * $category = ForumCategory::find(1);
     * $category->decrementTopicsCount(); // Décrémente topics_count
     */
    public function decrementTopicsCount()
    {
        $this->decrement('topics_count');
    }
}
