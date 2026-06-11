<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class EpreuveMatiere extends Model
{
    protected $table = 'epreuve_matieres';

    protected $fillable = [
        'name',
        'slug',
        'icon',
        'order',
    ];

    public function epreuves(): HasMany
    {
        return $this->hasMany(Epreuve::class, 'matiere_id');
    }

    public function publishedEpreuves(): HasMany
    {
        return $this->epreuves()->where('status', 'published');
    }
}
