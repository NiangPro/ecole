<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $table = 'download_logs';
    public $timestamps = true;

    protected $fillable = [
        'user_id',
        'file_id',
        'file_type',
        'ip_address',
        'user_agent',
        'identifier_hash',
        'is_suspicious',
        'reason',
        'blocked',
    ];

    protected $casts = [
        'is_suspicious' => 'boolean',
        'blocked' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Relation avec User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Enregistrer un téléchargement
     */
    public static function recordDownload($fileId, $fileType = 'epreuve', $isSuspicious = false, $reason = null, $blocked = false)
    {
        $request = request();
        
        return static::create([
            'user_id' => auth()->id(),
            'file_id' => $fileId,
            'file_type' => $fileType,
            'ip_address' => $request->ip(),
            'user_agent' => substr($request->userAgent(), 0, 255),
            'identifier_hash' => $request->header('X-Client-Identifier'),
            'is_suspicious' => $isSuspicious,
            'reason' => $reason,
            'blocked' => $blocked,
        ]);
    }

    /**
     * Obtenir les téléchargements suspects
     */
    public static function getSuspiciousActivity($hours = 24)
    {
        return static::where('is_suspicious', true)
            ->where('created_at', '>=', now()->subHours($hours))
            ->groupBy('identifier_hash')
            ->selectRaw('identifier_hash, COUNT(*) as count, MAX(created_at) as last_attempt')
            ->orderByDesc('count')
            ->get();
    }
}
