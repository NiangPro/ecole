<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SecurityAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_type',
        'severity',
        'ip_address',
        'user_agent',
        'user_id',
        'route',
        'method',
        'request_data',
        'response_code',
        'message',
        'metadata',
    ];

    protected $casts = [
        'request_data' => 'array',
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Types d'événements de sécurité
     */
    const EVENT_TYPES = [
        'csrf_attack' => 'Tentative d\'attaque CSRF',
        'rate_limit_exceeded' => 'Limite de taux dépassée',
        'invalid_origin' => 'Origine invalide',
        'suspicious_activity' => 'Activité suspecte',
        'failed_login' => 'Tentative de connexion échouée',
        'unauthorized_access' => 'Tentative d\'accès non autorisé',
        'sql_injection_attempt' => 'Tentative d\'injection SQL',
        'xss_attempt' => 'Tentative d\'attaque XSS',
        'file_upload_abuse' => 'Abus de téléchargement de fichier',
        'admin_action' => 'Action administrateur',
    ];

    /**
     * Niveaux de sévérité
     */
    const SEVERITY_LOW = 'low';
    const SEVERITY_MEDIUM = 'medium';
    const SEVERITY_HIGH = 'high';
    const SEVERITY_CRITICAL = 'critical';

    /**
     * Enregistrer un événement de sécurité
     */
    public static function log(
        string $eventType,
        string $severity = self::SEVERITY_MEDIUM,
        array $data = []
    ): self {
        $request = request();

        return self::create([
            'event_type' => $eventType,
            'severity' => $severity,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => auth()->id(),
            'route' => $request->route()?->getName(),
            'method' => $request->method(),
            'request_data' => self::sanitizeRequestData($request->all()),
            'response_code' => $data['response_code'] ?? null,
            'message' => $data['message'] ?? self::EVENT_TYPES[$eventType] ?? 'Événement de sécurité',
            'metadata' => $data['metadata'] ?? [],
        ]);
    }

    /**
     * Nettoyer les données de requête pour l'audit (enlever les mots de passe, etc.)
     */
    protected static function sanitizeRequestData(array $data): array
    {
        $sensitiveFields = ['password', 'password_confirmation', 'current_password', 'new_password', 'token', 'api_key'];

        foreach ($sensitiveFields as $field) {
            if (isset($data[$field])) {
                $data[$field] = '***REDACTED***';
            }
        }

        return $data;
    }

    /**
     * Relation avec l'utilisateur
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope pour filtrer par sévérité
     */
    public function scopeBySeverity($query, string $severity)
    {
        return $query->where('severity', $severity);
    }

    /**
     * Scope pour filtrer par type d'événement
     */
    public function scopeByEventType($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope pour les événements récents
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }

    /**
     * Scope pour les événements critiques
     */
    public function scopeCritical($query)
    {
        return $query->where('severity', self::SEVERITY_CRITICAL)
            ->orWhere('severity', self::SEVERITY_HIGH);
    }
}

