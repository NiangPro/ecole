<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use App\Services\YouTubeOEmbedService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{
    private string $youtubeError = '';

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $ads = Ad::orderBy('order')->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.ads.index', compact('ads'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Le middleware AdminAuth gère déjà l'authentification

        return view('admin.ads.create');
    }

    /**
     * Aperçu AJAX d'une vidéo YouTube avant enregistrement (utilisé par le formulaire
     * create/edit) : ne persiste rien, renvoie juste les métadonnées oEmbed.
     */
    public function youtubePreview(Request $request)
    {
        $request->validate(['youtube_url' => 'required|url']);

        $data = YouTubeOEmbedService::fetch($request->input('youtube_url'));

        if (!$data) {
            return response()->json([
                'success' => false,
                'message' => "Impossible de récupérer cette vidéo. Vérifiez que le lien YouTube est correct et public.",
            ], 422);
        }

        return response()->json(['success' => true, 'data' => $data]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $validated = $this->validateAd($request);

        if ($validated === null) {
            return back()->withErrors(['youtube_url' => $this->youtubeError])->withInput();
        }

        $ad = Ad::create($validated);

        // Le cache sera invalidé automatiquement par l'événement du modèle

        return redirect()->route('admin.ads.index')
            ->with('success', 'Publicité créée avec succès');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $ad = Ad::findOrFail($id);
        return view('admin.ads.show', compact('ad'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $ad = Ad::findOrFail($id);
        return view('admin.ads.edit', compact('ad'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $ad = Ad::findOrFail($id);

        $validated = $this->validateAd($request, $ad);

        if ($validated === null) {
            return back()->withErrors(['youtube_url' => $this->youtubeError])->withInput();
        }

        $ad->update($validated);

        return redirect()->route('admin.ads.index')
            ->with('success', 'Publicité mise à jour avec succès');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $ad = Ad::findOrFail($id);
        $ad->delete();

        return redirect()->route('admin.ads.index')
            ->with('success', 'Publicité supprimée avec succès');
    }

    /**
     * Valide et prépare les données communes à store()/update(). Gère le format
     * 'image' (upload/URL, comportement historique) et 'video' (lien YouTube —
     * les métadonnées oEmbed sont stockées en JSON dans 'ad_code', colonne texte
     * existante réemployée pour ne pas avoir à migrer la table 'ads').
     *
     * Retourne null si un lien YouTube invalide/injoignable a été soumis en format
     * vidéo (message d'erreur dans $this->youtubeError) — l'appelant doit alors
     * renvoyer une erreur de validation plutôt que d'enregistrer une pub cassée.
     *
     * @return array<string,mixed>|null
     */
    private function validateAd(Request $request, ?Ad $existing = null): ?array
    {
        $format = $request->input('ad_format', 'image');

        $rules = [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'link_url' => 'nullable|url',
            // 'location' pilote seule le placement réel (voir EmploiController/PageController) ;
            // 'position' n'est plus exposé au formulaire — aucune requête d'affichage ne filtre
            // sur autre chose que position='content', donc on le fixe en dur ci-dessous plutôt
            // que de laisser un champ qui, mal renseigné, rend la pub invisible partout (cf.
            // publicité #4 créée avec position='sidebar' → jamais affichée nulle part).
            'location' => 'nullable|in:,homepage_after_exercises,article_sidebar',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ];

        if ($format === 'video') {
            $rules['youtube_url'] = 'required|url';
        } else {
            $rules['image_type'] = 'required|in:internal,external';
        }

        $validated = $request->validate($rules);
        $validated['order'] = $validated['order'] ?? 0;
        $validated['position'] = 'content';

        if ($format === 'video') {
            $oembed = YouTubeOEmbedService::fetch($request->input('youtube_url'));

            if (!$oembed) {
                $this->youtubeError = "Impossible de récupérer cette vidéo. Vérifiez que le lien YouTube est correct et public.";
                return null;
            }

            // On repasse d'une éventuelle pub image existante à vidéo : nettoyer l'image interne orpheline.
            if ($existing && $existing->image_type === 'internal' && $existing->image && Storage::disk('public')->exists($existing->image)) {
                Storage::disk('public')->delete($existing->image);
            }

            $validated['ad_code'] = json_encode($oembed, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            $validated['image'] = null;
            $validated['image_type'] = 'external';
            $validated['link_url'] = $validated['link_url'] ?: $oembed['youtube_url'];

            return $validated;
        }

        // Format image (comportement historique)
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            if ($existing && $existing->image_type === 'internal' && $existing->image && Storage::disk('public')->exists($existing->image)) {
                Storage::disk('public')->delete($existing->image);
            }
            $validated['image'] = $request->file('image_file')->store('ads', 'public');
        } elseif ($validated['image_type'] === 'external' && $request->has('image_url')) {
            if ($existing && $existing->image_type === 'internal' && $existing->image && Storage::disk('public')->exists($existing->image)) {
                Storage::disk('public')->delete($existing->image);
            }
            $validated['image'] = $request->input('image_url');
        } elseif ($existing) {
            // Ni fichier ni URL soumis en édition : conserver l'image actuelle.
            $validated['image'] = $existing->image;
        }

        $validated['ad_code'] = null;

        return $validated;
    }
}
