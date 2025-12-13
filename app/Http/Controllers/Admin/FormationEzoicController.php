<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FormationEzoicUnit;
use App\Models\EzoicUnit;
use Illuminate\Http\Request;

class FormationEzoicController extends Controller
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

        // Récupérer toutes les unités Ezoic actives
        $ezoicUnits = EzoicUnit::active()->ordered()->get();

        // Récupérer les associations pour chaque formation
        $formationAds = [];
        foreach ($formations as $slug => $name) {
            $formationAds[$slug] = [
                'name' => $name,
                'ads' => FormationEzoicUnit::active()
                    ->forFormation($slug)
                    ->with('ezoicUnit')
                    ->orderBy('position')
                    ->orderBy('order')
                    ->get()
            ];
        }

        return view('admin.formation-ezoic.index', compact('formations', 'ezoicUnits', 'formationAds'));
    }

    /**
     * Afficher/modifier les annonces pour une formation spécifique
     */
    public function show($slug)
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

        if (!isset($formations[$slug])) {
            abort(404, 'Formation non trouvée');
        }

        $formationName = $formations[$slug];
        
        // Récupérer toutes les unités Ezoic actives
        $ezoicUnits = EzoicUnit::active()->ordered()->get();

        // Récupérer les annonces existantes pour cette formation
        $existingAds = FormationEzoicUnit::where('formation_slug', $slug)
            ->with('ezoicUnit')
            ->orderBy('position')
            ->orderBy('order')
            ->get()
            ->groupBy('position');

        return view('admin.formation-ezoic.show', compact('slug', 'formationName', 'ezoicUnits', 'existingAds'));
    }

    /**
     * Enregistrer les annonces pour une formation
     */
    public function store(Request $request)
    {
        $request->validate([
            'formation_slug' => 'required|string',
            'ads' => 'nullable|array',
            'ads.*.ezoic_unit_id' => 'required|exists:ezoic_units,id',
            'ads.*.position' => 'required|in:header,content,sidebar,footer',
            'ads.*.order' => 'nullable|integer|min:0',
        ]);

        $formationSlug = $request->formation_slug;

        // Supprimer les anciennes associations pour cette formation
        FormationEzoicUnit::where('formation_slug', $formationSlug)->delete();

        // Créer les nouvelles associations
        if ($request->has('ads') && is_array($request->ads)) {
            foreach ($request->ads as $ad) {
                FormationEzoicUnit::create([
                    'formation_slug' => $formationSlug,
                    'ezoic_unit_id' => $ad['ezoic_unit_id'],
                    'position' => $ad['position'],
                    'order' => $ad['order'] ?? 0,
                    'status' => 'active',
                ]);
            }
        }

        return redirect()->route('admin.formation-ezoic.index')
            ->with('success', 'Annonces Ezoic configurées avec succès pour ' . $formationSlug);
    }
}
