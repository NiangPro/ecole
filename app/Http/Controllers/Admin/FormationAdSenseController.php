<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormationAdSenseUnit;
use App\Models\AdSenseUnit;
use Illuminate\Http\Request;

class FormationAdSenseController extends Controller
{
    /**
     * Liste des formations avec leurs annonces
     */
    public function index()
    {
        // Liste des formations disponibles
        $formations = [
            'all' => 'Toutes les formations (Global)',
            'html5' => 'HTML5',
            'css3' => 'CSS3',
            'javascript' => 'JavaScript',
            'php' => 'PHP',
            'python' => 'Python',
            'java' => 'Java',
            'sql' => 'SQL',
            'c' => 'C',
            'bootstrap' => 'Bootstrap',
            'git' => 'Git',
            'wordpress' => 'WordPress',
            'ia' => 'Intelligence Artificielle',
            'cpp' => 'C++',
            'csharp' => 'C#',
            'dart' => 'Dart',
        ];

        // Récupérer toutes les unités AdSense actives
        $adsenseUnits = AdSenseUnit::active()->ordered()->get();

        // Récupérer les associations pour chaque formation
        $formationAds = [];
        foreach ($formations as $slug => $name) {
            $formationAds[$slug] = [
                'name' => $name,
                'ads' => FormationAdSenseUnit::active()
                    ->forFormation($slug)
                    ->with('adsenseUnit')
                    ->orderBy('position')
                    ->orderBy('order')
                    ->get()
            ];
        }

        return view('admin.formation-adsense.index', compact('formations', 'adsenseUnits', 'formationAds'));
    }

    /**
     * Afficher/modifier les annonces pour une formation spécifique
     */
    public function show($slug)
    {
        $formations = [
            'all' => 'Toutes les formations (Global)',
            'html5' => 'HTML5',
            'css3' => 'CSS3',
            'javascript' => 'JavaScript',
            'php' => 'PHP',
            'python' => 'Python',
            'java' => 'Java',
            'sql' => 'SQL',
            'c' => 'C',
            'bootstrap' => 'Bootstrap',
            'git' => 'Git',
            'wordpress' => 'WordPress',
            'ia' => 'Intelligence Artificielle',
            'cpp' => 'C++',
            'csharp' => 'C#',
            'dart' => 'Dart',
        ];

        if (!isset($formations[$slug])) {
            return redirect()->route('admin.formation-adsense.index')
                ->with('error', 'Formation non trouvée');
        }

        $formationName = $formations[$slug];
        $adsenseUnits = AdSenseUnit::active()->ordered()->get();
        
        // Récupérer les annonces actuelles pour cette formation
        $currentAds = FormationAdSenseUnit::active()
            ->forFormation($slug)
            ->with('adsenseUnit')
            ->orderBy('position')
            ->orderBy('order')
            ->get();

        return view('admin.formation-adsense.show', compact('slug', 'formationName', 'adsenseUnits', 'currentAds'));
    }

    /**
     * Associer une unité AdSense à une formation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'formation_slug' => 'required|string',
            'adsense_unit_id' => 'required|exists:adsense_units,id',
            'position' => 'required|in:header,content,sidebar,footer',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        // Vérifier si l'association existe déjà
        $existing = FormationAdSenseUnit::where('formation_slug', $validated['formation_slug'])
            ->where('adsense_unit_id', $validated['adsense_unit_id'])
            ->where('position', $validated['position'])
            ->first();

        if ($existing) {
            return redirect()->back()
                ->with('error', 'Cette unité est déjà associée à cette formation à cette position');
        }

        FormationAdSenseUnit::create($validated);

        return redirect()->route('admin.formation-adsense.show', $validated['formation_slug'])
            ->with('success', 'Annonce associée avec succès');
    }

    /**
     * Mettre à jour une association
     */
    public function update(Request $request, $id)
    {
        $association = FormationAdSenseUnit::findOrFail($id);

        $validated = $request->validate([
            'adsense_unit_id' => 'required|exists:adsense_units,id',
            'position' => 'required|in:header,content,sidebar,footer',
            'order' => 'nullable|integer|min:0',
            'status' => 'required|in:active,inactive',
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        $association->update($validated);

        return redirect()->route('admin.formation-adsense.show', $association->formation_slug)
            ->with('success', 'Association mise à jour avec succès');
    }

    /**
     * Supprimer une association
     */
    public function destroy($id)
    {
        $association = FormationAdSenseUnit::findOrFail($id);
        $slug = $association->formation_slug;
        $association->delete();

        return redirect()->route('admin.formation-adsense.show', $slug)
            ->with('success', 'Association supprimée avec succès');
    }
}
