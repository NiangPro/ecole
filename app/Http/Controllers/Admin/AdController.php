<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ad;
use Illuminate\Http\Request;

class AdController extends Controller
{
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Le middleware AdminAuth gère déjà l'authentification

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'link_url' => 'nullable|url',
            'position' => 'required|in:sidebar,content,header,footer',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            $path = $request->file('image_file')->store('ads', 'public');
            $validated['image'] = $path;
        } elseif ($validated['image_type'] === 'external' && $request->has('image_url')) {
            $validated['image'] = $request->input('image_url');
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

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|string',
            'image_type' => 'required|in:internal,external',
            'link_url' => 'nullable|url',
            'position' => 'required|in:sidebar,content,header,footer',
            'location' => 'nullable|string|max:255',
            'status' => 'required|in:active,inactive',
            'order' => 'nullable|integer|min:0',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        $validated['order'] = $validated['order'] ?? 0;

        // Gérer l'upload d'image si c'est une image interne
        if ($validated['image_type'] === 'internal' && $request->hasFile('image_file')) {
            // Supprimer l'ancienne image si elle existe
            if ($ad->image_type === 'internal' && $ad->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($ad->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ad->image);
            }
            $path = $request->file('image_file')->store('ads', 'public');
            $validated['image'] = $path;
        } elseif ($validated['image_type'] === 'external' && $request->has('image_url')) {
            // Supprimer l'ancienne image interne si on passe à externe
            if ($ad->image_type === 'internal' && $ad->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($ad->image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($ad->image);
            }
            $validated['image'] = $request->input('image_url');
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
}
