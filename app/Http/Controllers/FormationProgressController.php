<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormationProgress;
use Illuminate\Support\Facades\Auth;
use App\Models\UserActivity;

class FormationProgressController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'formation_slug' => 'required|string',
            'section_id' => 'nullable|string',
            'completed' => 'boolean',
            'time_spent' => 'nullable|integer|min:0',
        ]);

        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Non authentifié'], 401);
        }

        $progress = FormationProgress::getOrCreate($user->id, $request->formation_slug);

        if ($request->has('section_id') && $request->has('completed')) {
            if ($request->completed) {
                $progress->markSectionAsCompleted($request->section_id);
            }
            $progress->section_id = $request->section_id;
        }

        if ($request->has('time_spent')) {
            $progress->addTimeSpent($request->time_spent);
        }

        // Calculer le pourcentage (basé sur les sections complétées)
        // Ici on suppose qu'il y a environ 10 sections par formation
        $completedSections = count($progress->completed_sections ?? []);
        $totalSections = 10; // À ajuster selon la formation
        $previousPercentage = $progress->progress_percentage;
        $progress->updateProgress($totalSections, $completedSections);
        
        // Enregistrer l'activité si la progression a changé significativement
        if ($progress->progress_percentage > $previousPercentage) {
            $formationName = ucfirst(str_replace('-', ' ', $request->formation_slug));
            
            // Enregistrer l'activité
            UserActivity::log(
                $user->id,
                'formation',
                'Formation : ' . $formationName,
                "formations/{$request->formation_slug}",
                [
                    'progress_percentage' => $progress->progress_percentage,
                    'sections_completed' => $completedSections,
                    'total_sections' => $totalSections,
                    'time_spent_minutes' => $progress->time_spent_minutes,
                ]
            );
            
            // Invalider le cache du dashboard
            \App\Http\Controllers\ProfileController::clearCache($user->id);
        }

        return response()->json([
            'progress_percentage' => $progress->progress_percentage,
            'completed_sections' => $progress->completed_sections,
            'time_spent_minutes' => $progress->time_spent_minutes,
        ]);
    }

    public function get($formationSlug)
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['progress_percentage' => 0]);
        }

        $progress = $user->getProgressForFormation($formationSlug);

        if (!$progress) {
            return response()->json([
                'progress_percentage' => 0,
                'completed_sections' => [],
                'time_spent_minutes' => 0,
            ]);
        }

        return response()->json([
            'progress_percentage' => $progress->progress_percentage,
            'completed_sections' => $progress->completed_sections ?? [],
            'time_spent_minutes' => $progress->time_spent_minutes,
            'started_at' => $progress->started_at,
            'completed_at' => $progress->completed_at,
        ]);
    }
}
