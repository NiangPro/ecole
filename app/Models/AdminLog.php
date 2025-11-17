<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLog extends Model
{
    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Créer un log d'action admin
     */
    public static function log(string $action, $model = null, ?string $description = null, array $oldValues = [], array $newValues = [])
    {
        $request = request();
        
        return self::create([
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model ? $model->id : null,
            'description' => $description ?? self::generateDescription($action, $model),
            'old_values' => !empty($oldValues) ? $oldValues : null,
            'new_values' => !empty($newValues) ? $newValues : null,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);
    }

    /**
     * Générer une description automatique
     */
    private static function generateDescription(string $action, $model = null): string
    {
        if (!$model) {
            return ucfirst($action);
        }

        $modelName = class_basename($model);
        
        switch ($action) {
            case 'create':
                return "Création de {$modelName} #{$model->id}";
            case 'update':
                return "Modification de {$modelName} #{$model->id}";
            case 'delete':
                return "Suppression de {$modelName} #{$model->id}";
            case 'approve':
                return "Approbation de {$modelName} #{$model->id}";
            case 'reject':
                return "Rejet de {$modelName} #{$model->id}";
            default:
                return ucfirst($action) . " de {$modelName} #{$model->id}";
        }
    }

    /**
     * Relation avec le modèle concerné
     */
    public function model()
    {
        return $this->morphTo();
    }
}

