<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AchievementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $achievements = Achievement::ordered()->get();
        $showSection = SiteSetting::get('show_achievements_section', true);
        return view('admin.achievements.index', compact('achievements', 'showSection'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Le middleware AdminAuth gère déjà l'authentification

        return view('admin.achievements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
        ]);

        $validated['order'] = $validated['order'] ?? 0;
        $validated['is_visible'] = $request->has('is_visible');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('achievements', 'public');
            $validated['image'] = $path;
        } elseif ($validated['image_type'] === 'external' && $request->has('image_url')) {
            $validated['image'] = $request->input('image_url');
        }

        Achievement::create($validated);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Réalisation créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $achievement = Achievement::findOrFail($id);
        return view('admin.achievements.show', compact('achievement'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $achievement = Achievement::findOrFail($id);
        return view('admin.achievements.edit', compact('achievement'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $achievement = Achievement::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'icon' => 'nullable|string|max:255',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'link_url' => 'nullable|url',
            'order' => 'nullable|integer|min:0',
            'is_visible' => 'boolean',
        ]);

        $validated['is_visible'] = $request->has('is_visible');

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            // Supprimer l'ancienne image si elle existe
            if ($achievement->image && $achievement->image_type === 'internal') {
                Storage::disk('public')->delete($achievement->image);
            }
            $path = $request->file('image_file')->store('achievements', 'public');
            $validated['image'] = $path;
        } elseif ($validated['image_type'] === 'internal' && !$request->hasFile('image_file')) {
            // Conserver l'image existante si pas de nouveau fichier
            $validated['image'] = $achievement->image;
        } elseif ($validated['image_type'] === 'external' && $request->has('image_url')) {
            // Supprimer l'ancienne image interne si on passe à externe
            if ($achievement->image && $achievement->image_type === 'internal') {
                Storage::disk('public')->delete($achievement->image);
            }
            $validated['image'] = $request->input('image_url');
        } elseif ($validated['image_type'] === 'external' && !$request->has('image_url')) {
            // Conserver l'URL existante si pas de nouvelle URL
            $validated['image'] = $achievement->image;
        }

        $achievement->update($validated);

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Réalisation mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $achievement = Achievement::findOrFail($id);

        // Supprimer l'image si elle est interne
        if ($achievement->image && $achievement->image_type === 'internal') {
            Storage::disk('public')->delete($achievement->image);
        }

        $achievement->delete();

        return redirect()->route('admin.achievements.index')
            ->with('success', 'Réalisation supprimée avec succès');
    }

    /**
     * Toggle the visibility of the achievements section on the about page.
     */
    public function toggleSection()
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $settings = SiteSetting::firstOrNew();
        $settings->show_achievements_section = !$settings->show_achievements_section;
        $settings->save();

        return redirect()->route('admin.achievements.index')
            ->with('success', $settings->show_achievements_section 
                ? 'Section "Mes Réalisations" affichée' 
                : 'Section "Mes Réalisations" masquée');
    }
}
