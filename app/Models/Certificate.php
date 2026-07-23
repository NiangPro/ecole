<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class Certificate extends Model
{
    protected $fillable = [
        'user_id',
        'formation_slug',
        'paid_course_id',
        'certificate_number',
        'completed_date',
        'score',
        'pdf_path',
        'generated_at',
    ];

    protected $casts = [
        'completed_date' => 'date',
        'generated_at' => 'datetime',
        'score' => 'integer',
    ];

    /**
     * Relation avec l'utilisateur
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation avec le cours payant (si le certificat concerne un cours payant)
     */
    public function paidCourse(): BelongsTo
    {
        return $this->belongsTo(PaidCourse::class, 'paid_course_id');
    }

    /**
     * Nom lisible de la formation/cours pour affichage (liste, PDF, ...)
     */
    public function getDisplayNameAttribute(): string
    {
        if ($this->paid_course_id && $this->paidCourse) {
            return $this->paidCourse->title;
        }

        return ucfirst(str_replace('-', ' ', $this->formation_slug));
    }

    /**
     * Générer un numéro de certificat unique
     */
    public static function generateCertificateNumber(): string
    {
        do {
            $number = 'CERT-' . strtoupper(Str::random(8)) . '-' . date('Y');
        } while (self::where('certificate_number', $number)->exists());

        return $number;
    }

    /**
     * Vérifier si le PDF existe
     */
    public function hasPdf(): bool
    {
        return $this->pdf_path && \Storage::exists($this->pdf_path);
    }

    /**
     * Obtenir l'URL du PDF
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }
        return route('dashboard.certificates.download', $this->id);
    }
}
