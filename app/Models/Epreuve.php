<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Epreuve extends Model
{
    protected $table = 'epreuves';

    protected $fillable = [
        'title',
        'slug',
        'type',
        'exam',
        'level',
        'matiere_id',
        'serie',
        'year',
        'description',
        'file_path',
        'file_name',
        'file_size',
        'corrige_file_path',
        'downloads_count',
        'views_count',
        'is_featured',
        'status',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'year' => 'integer',
        'downloads_count' => 'integer',
        'views_count' => 'integer',
    ];

    /**
     * Examens nationaux du Sénégal.
     */
    public const EXAMS = [
        'cfee' => 'CFEE',
        'bfem' => 'BFEM',
        'bac'  => 'BAC',
        'bts'  => 'BTS',
        'cap'  => 'CAP',
    ];

    /**
     * Niveaux scolaires, groupés par cycle.
     */
    public const LEVELS = [
        'primaire' => [
            'ci' => 'CI', 'cp' => 'CP', 'ce1' => 'CE1', 'ce2' => 'CE2', 'cm1' => 'CM1', 'cm2' => 'CM2',
        ],
        'college' => [
            '6eme' => '6ème', '5eme' => '5ème', '4eme' => '4ème', '3eme' => '3ème',
        ],
        'lycee' => [
            'seconde' => 'Seconde', 'premiere' => 'Première', 'terminale' => 'Terminale',
        ],
    ];

    public const TYPES = [
        'epreuve'         => 'Épreuve',
        'corrige'         => 'Corrigé',
        'epreuve_corrige' => 'Épreuve + Corrigé',
        'examen_blanc'    => 'Examen blanc',
        'devoir'          => 'Devoir',
        'composition'     => 'Composition',
    ];

    public function matiere(): BelongsTo
    {
        return $this->belongsTo(EpreuveMatiere::class, 'matiere_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    /**
     * Liste plate des niveaux (slug => label), sans les groupes.
     */
    public static function flatLevels(): array
    {
        return array_merge(...array_values(self::LEVELS));
    }

    public function getExamLabelAttribute(): ?string
    {
        return $this->exam ? (self::EXAMS[$this->exam] ?? strtoupper($this->exam)) : null;
    }

    public function getLevelLabelAttribute(): ?string
    {
        return $this->level ? (self::flatLevels()[$this->level] ?? $this->level) : null;
    }

    public function getTypeLabelAttribute(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    /**
     * Taille du fichier lisible (ex : "1,2 Mo").
     */
    public function getFileSizeHumanAttribute(): ?string
    {
        if (!$this->file_size) {
            return null;
        }
        if ($this->file_size >= 1048576) {
            return number_format($this->file_size / 1048576, 1, ',', ' ') . ' Mo';
        }
        return number_format($this->file_size / 1024, 0, ',', ' ') . ' Ko';
    }

    /**
     * Devine les métadonnées (examen, matière, année, série, type) depuis un
     * nom de fichier ou un titre, pour pré-remplir le formulaire admin.
     */
    public static function guessMetadata(string $name): array
    {
        $normalized = Str::lower(Str::ascii(str_replace(['_', '-', '.'], ' ', $name)));
        $guess = [];

        if (preg_match('/\b(19[8-9]\d|20[0-4]\d)\b/', $normalized, $m)) {
            $guess['year'] = (int) $m[1];
        }

        foreach (array_keys(self::EXAMS) as $exam) {
            if (preg_match('/\b' . $exam . '\b/', $normalized) || ($exam === 'bac' && str_contains($normalized, 'baccalaureat'))) {
                $guess['exam'] = $exam;
                break;
            }
        }
        if (str_contains($normalized, 'cfee') || preg_match('/\bcep\b/', $normalized)) {
            $guess['exam'] = 'cfee';
        }

        foreach (self::flatLevels() as $slug => $label) {
            if (preg_match('/\b' . preg_quote(Str::lower(Str::ascii($label)), '/') . '\b/', $normalized)) {
                $guess['level'] = $slug;
                break;
            }
        }

        if (str_contains($normalized, 'blanc')) {
            $guess['type'] = 'examen_blanc';
        } elseif (str_contains($normalized, 'corrige') && (str_contains($normalized, 'epreuve') || str_contains($normalized, 'sujet'))) {
            $guess['type'] = 'epreuve_corrige';
        } elseif (str_contains($normalized, 'corrige')) {
            $guess['type'] = 'corrige';
        } elseif (str_contains($normalized, 'composition')) {
            $guess['type'] = 'composition';
        } elseif (str_contains($normalized, 'devoir')) {
            $guess['type'] = 'devoir';
        }

        if (preg_match('/\bserie[s]?\s+([a-z][a-z0-9 ]{0,8}?)(?:\s+senegal|\s+pdf|$)/', $normalized, $m)) {
            $guess['serie'] = strtoupper(trim($m[1]));
        }

        $matiere = EpreuveMatiere::all()->first(function ($m) use ($normalized) {
            return str_contains($normalized, Str::lower(Str::ascii($m->name)));
        });
        if ($matiere) {
            $guess['matiere_id'] = $matiere->id;
        } elseif (str_contains($normalized, 'math')) {
            $guess['matiere_id'] = EpreuveMatiere::where('slug', 'mathematiques')->value('id');
        } elseif (str_contains($normalized, 'physique') || str_contains($normalized, 'chimie')) {
            $guess['matiere_id'] = EpreuveMatiere::where('slug', 'physique-chimie')->value('id');
        } elseif (str_contains($normalized, 'histoire') || str_contains($normalized, 'geographie')) {
            $guess['matiere_id'] = EpreuveMatiere::where('slug', 'histoire-geographie')->value('id');
        }

        return array_filter($guess, fn ($v) => $v !== null);
    }
}
