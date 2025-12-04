<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserGoal;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserGoalController extends Controller
{
    /**
     * Créer un nouvel objectif
     */
    public function store(Request $request)
    {
        $request->validate([
            'goal_type' => 'required|in:formation,exercise,quiz,time,score',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_value' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:20',
            'deadline' => 'nullable|date|after:today',
        ]);

        $user = Auth::user();

        $goal = UserGoal::create([
            'user_id' => $user->id,
            'goal_type' => $request->goal_type,
            'title' => $request->title,
            'description' => $request->description,
            'target_value' => $request->target_value,
            'current_value' => 0,
            'unit' => $request->unit ?? '%',
            'deadline' => $request->deadline ? Carbon::parse($request->deadline) : null,
            'completed' => false,
        ]);

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache($user->id);

        return redirect()->route('dashboard.goals')->with('success', trans('app.profile.goal.created') ?? 'Objectif créé avec succès !');
    }

    /**
     * Mettre à jour un objectif
     */
    public function update(Request $request, $id)
    {
        $goal = UserGoal::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'target_value' => 'required|integer|min:1',
            'unit' => 'nullable|string|max:20',
            'deadline' => 'nullable|date',
        ]);

        $goal->update([
            'title' => $request->title,
            'description' => $request->description,
            'target_value' => $request->target_value,
            'unit' => $request->unit ?? $goal->unit,
            'deadline' => $request->deadline ? Carbon::parse($request->deadline) : null,
        ]);

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache(Auth::id());

        return redirect()->route('dashboard.goals')->with('success', trans('app.profile.goal.updated') ?? 'Objectif mis à jour avec succès !');
    }

    /**
     * Supprimer un objectif
     */
    public function destroy($id)
    {
        $goal = UserGoal::where('user_id', Auth::id())->findOrFail($id);
        $userId = $goal->user_id;
        $goal->delete();

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache($userId);

        return redirect()->route('dashboard.goals')->with('success', trans('app.profile.goal.deleted') ?? 'Objectif supprimé avec succès !');
    }

    /**
     * Marquer un objectif comme complété
     */
    public function complete($id)
    {
        $goal = UserGoal::where('user_id', Auth::id())->findOrFail($id);
        
        $goal->update([
            'completed' => true,
            'completed_at' => now(),
            'current_value' => $goal->target_value,
        ]);

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache(Auth::id());

        return redirect()->route('dashboard.goals')->with('success', trans('app.profile.goal.completed_success') ?? 'Objectif marqué comme complété !');
    }

    /**
     * Mettre à jour la progression d'un objectif
     */
    public function updateProgress(Request $request, $id)
    {
        $goal = UserGoal::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'current_value' => 'required|integer|min:0',
        ]);

        $goal->updateProgress($request->current_value);

        // Invalider le cache du dashboard
        \App\Http\Controllers\ProfileController::clearCache(Auth::id());

        return response()->json([
            'success' => true,
            'progress_percentage' => $goal->progress_percentage,
            'completed' => $goal->completed,
        ]);
    }
}
