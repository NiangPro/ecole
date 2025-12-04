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
        return $this->pdf_path && file_exists(storage_path('app/' . $this->pdf_path));
    }

    /**
     * Obtenir l'URL du PDF
     */
    public function getPdfUrlAttribute(): ?string
    {
        if (!$this->pdf_path) {
            return null;
        }
        return route('certificates.download', $this->id);
    }
}
